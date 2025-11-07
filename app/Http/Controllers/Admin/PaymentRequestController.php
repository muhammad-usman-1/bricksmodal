<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CastingApplication;
use App\Models\User;
use App\Notifications\PaymentApproved;
use App\Notifications\PaymentRejected;
use App\Notifications\PaymentReleased;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Stripe\Stripe;
use Stripe\Transfer;
use Stripe\Exception\ApiErrorException;

class PaymentRequestController extends Controller
{
    /**
     * Display all payment requests (super admin only)
     */
    public function index(Request $request)
    {
        $admin = auth('admin')->user();

        if (!$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can access payment requests.');
        }

        $query = CastingApplication::with([
            'casting_requirement',
            'talent_profile.user',
            'requestedByAdmin',
            'approvedBySuperAdmin'
        ])->where('status', 'selected');

        // Filter by payment status
        $status = $request->get('status');
        if ($status && in_array($status, ['requested', 'approved', 'released', 'received', 'rejected'])) {
            $query->where('payment_status', $status);
        } else {
            // By default show pending approvals and approved payments
            $query->whereIn('payment_status', ['requested', 'approved']);
        }

        $paymentRequests = $query->latest('payment_requested_at')->paginate(20);

        // Statistics
        $stats = [
            'total_requested' => CastingApplication::where('payment_status', 'requested')->count(),
            'total_approved' => CastingApplication::where('payment_status', 'approved')->count(),
            'total_released' => CastingApplication::where('payment_status', 'released')->count(),
            'total_received' => CastingApplication::where('payment_status', 'received')->count(),
            'total_amount_pending' => CastingApplication::whereIn('payment_status', ['requested', 'approved'])
                ->get()
                ->sum(function($app) {
                    return $app->getPaymentAmount();
                }),
        ];

        return view('admin.payment-requests.index', compact('paymentRequests', 'stats'));
    }

    /**
     * Approve a payment request
     */
    public function approve(CastingApplication $castingApplication)
    {
        $admin = auth('admin')->user();

        if (!$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can approve payments.');
        }

        if ($castingApplication->payment_status !== 'requested') {
            return back()->withErrors(['error' => 'This payment has not been requested for approval.']);
        }

        $castingApplication->update([
            'payment_status' => 'approved',
            'payment_approved_at' => now(),
            'payment_approved_by_super_admin_id' => $admin->id,
        ]);

        // Notify the admin who requested payment
        if ($castingApplication->requestedByAdmin) {
            $castingApplication->requestedByAdmin->notify(new PaymentApproved($castingApplication));
        }

        return back()->with('message', 'Payment request approved successfully.');
    }

    /**
     * Reject a payment request
     */
    public function reject(Request $request, CastingApplication $castingApplication)
    {
        $admin = auth('admin')->user();

        if (!$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can reject payment requests.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        if ($castingApplication->payment_status !== 'requested') {
            return back()->withErrors(['error' => 'This payment has not been requested for approval.']);
        }

        $castingApplication->update([
            'payment_status' => 'rejected',
            'payment_rejection_reason' => $validated['rejection_reason'],
        ]);

        // Notify the admin who requested payment
        if ($castingApplication->requestedByAdmin) {
            $castingApplication->requestedByAdmin->notify(new PaymentRejected($castingApplication));
        }

        return back()->with('message', 'Payment request rejected.');
    }

    /**
     * Show the release payment form
     */
    public function showReleaseForm(CastingApplication $castingApplication)
    {
        $admin = auth('admin')->user();

        if (!$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can release payments.');
        }

        if ($castingApplication->payment_status !== 'approved') {
            return redirect()->route('admin.payment-requests.index')
                ->withErrors(['error' => 'Payment must be approved before releasing.']);
        }

        // Check if talent has card details
        $talentProfile = $castingApplication->talent_profile;
        if (!$talentProfile || !$talentProfile->hasCardDetails()) {
            return redirect()->route('admin.payment-requests.index')
                ->withErrors(['error' => 'Talent has not provided card details yet.']);
        }

        $castingApplication->load([
            'casting_requirement',
            'talent_profile.user',
        ]);

        return view('admin.payment-requests.release-form', [
            'application' => $castingApplication
        ]);
    }

    /**
     * Release payment to talent via Stripe
     */
    public function release(Request $request, CastingApplication $castingApplication)
    {
        $admin = auth('admin')->user();

        if (!$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can release payments.');
        }

        if ($castingApplication->payment_status !== 'approved') {
            return back()->withErrors(['error' => 'Payment must be approved before releasing.']);
        }

        // Check if talent has card details
        $talentProfile = $castingApplication->talent_profile;
        if (!$talentProfile || !$talentProfile->hasCardDetails()) {
            return back()->withErrors(['error' => 'Talent has not provided card details yet.']);
        }

        // Validate the form data
        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:0.01',
            'card_holder_name' => 'required|string|max:255',
            'payment_method_id' => 'required|string',
            'payment_description' => 'nullable|string|max:1000',
            'confirm_payment' => 'required|accepted',
        ]);

        try {
            // Initialize Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            $amount = $validated['payment_amount'];

            // Create a PaymentIntent with the provided payment method
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => 'usd',
                'payment_method' => $validated['payment_method_id'],
                'description' => $validated['payment_description'] ?: 'Payment for casting: ' . $castingApplication->casting_requirement->project_name,
                'confirm' => true, // Immediately attempt to confirm the payment
                'metadata' => [
                    'casting_application_id' => $castingApplication->id,
                    'talent_profile_id' => $talentProfile->id,
                    'talent_name' => $talentProfile->user->name ?? 'N/A',
                    'talent_card_last4' => substr($talentProfile->card_number, -4),
                    'admin_id' => $admin->id,
                    'admin_name' => $admin->name,
                    'card_holder_name' => $validated['card_holder_name'],
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
            ]);

            // Check payment status
            if ($paymentIntent->status === 'succeeded') {
                // Update application with Stripe payment intent ID
                $castingApplication->update([
                    'payment_status' => 'released',
                    'payment_released_at' => now(),
                    'stripe_payment_intent' => $paymentIntent->id,
                    'admin_notes' => ($castingApplication->admin_notes ? $castingApplication->admin_notes . "\n\n" : '') .
                                     "Payment released via Stripe by {$admin->name} on " . now()->format('Y-m-d H:i:s') .
                                     "\nAmount: $" . number_format($amount, 2) .
                                     "\nStripe Payment Intent: {$paymentIntent->id}" .
                                     ($validated['payment_description'] ? "\nNotes: {$validated['payment_description']}" : ''),
                ]);

                // Notify the talent
                if ($talentProfile->user) {
                    $talentProfile->user->notify(new PaymentReleased($castingApplication));
                }

                return redirect()->route('admin.payment-requests.index')->with('message',
                    '✅ Payment of $' . number_format($amount, 2) . ' processed successfully via Stripe! ' .
                    'Payment Intent ID: ' . $paymentIntent->id . ' | ' .
                    'Talent Card: ' . $talentProfile->getMaskedCardNumber()
                );
            } else {
                // Payment requires action or failed
                return back()->withErrors([
                    'error' => 'Payment could not be completed. Status: ' . $paymentIntent->status .
                               '. Please try again or contact support.'
                ])->withInput();
            }

        } catch (ApiErrorException $e) {
            Log::error('Stripe Payment Error', [
                'application_id' => $castingApplication->id,
                'admin_id' => $admin->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Stripe Error: ' . $e->getMessage()])->withInput();
        } catch (\Exception $e) {
            Log::error('Payment Processing Error', [
                'application_id' => $castingApplication->id,
                'admin_id' => $admin->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['error' => 'Payment processing failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show details of a specific payment request
     */
    public function show(CastingApplication $castingApplication)
    {
        $admin = auth('admin')->user();

        if (!$admin->isSuperAdmin()) {
            abort(403, 'Only Super Admin can view payment request details.');
        }

        $castingApplication->load([
            'casting_requirement',
            'talent_profile.user',
            'requestedByAdmin',
            'approvedBySuperAdmin'
        ]);

        return view('admin.payment-requests.show', compact('castingApplication'));
    }
}
