<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use App\Models\CastingApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PaymentDashboardController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationsStats = [
            'total'    => CastingApplication::count(),
            'paid'     => CastingApplication::where('payment_processed', 'paid')->count(),
            'pending'  => CastingApplication::where('payment_processed', 'pending')->count(),
            'applied'  => CastingApplication::where('status', 'applied')->count(),
            'selected' => CastingApplication::where('status', 'selected')->count(),
        ];

        $financials = [
            'total_requested' => CastingApplication::sum('rate'),
            'total_offered'   => CastingApplication::sum('rate_offered'),
        ];

        $recentPayments = CastingApplication::with(['casting_requirement', 'talent_profile'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        $recentBankAccounts = BankDetail::with('talent_profile')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboards.payments', compact(
            'applicationsStats',
            'financials',
            'recentPayments',
            'recentBankAccounts'
        ));
    }

    public function dashboard()
    {
        abort_if(Gate::denies('user_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get all casting applications that have been selected and have payment amounts
        $payments = CastingApplication::with(['casting_requirement', 'talent_profile'])
            ->where('status', 'selected') // Only selected talents
            ->where(function ($query) {
                $query->whereNotNull('rate_offered')
                      ->orWhereNotNull('rate');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.dashboards.payments', compact('payments'));
    }

    public function requestPayment(Request $request)
    {
        abort_if(Gate::denies('user_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check if user is regular admin and prevent direct payment processing
        if (!auth()->user()->is_super_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Payment requests must be approved by Super Admin. Please contact Super Admin for payment processing.'
            ], 403);
        }

        try {
            $paymentId = $request->input('payment_id');
            $notes = $request->input('notes');
            $action = $request->input('action', 'requested'); // Default to 'requested'

            // Find the casting application
            $application = CastingApplication::findOrFail($paymentId);

            $updateData = [
                'payment_notes' => $notes,
                'processed_by' => auth()->id()
            ];

            if ($action === 'released') {
                $updateData['payment_processed'] = 'released';
                $updateData['payment_released_at'] = now();
                $message = 'Payment approved and released successfully!';
            } else {
                $updateData['payment_processed'] = 'requested';
                $updateData['payment_requested_at'] = now();
                $message = 'Payment request submitted successfully!';
            }

            $application->update($updateData);

            // Here you can add additional logic like:
            // - Send notification to talent
            // - Log the payment action
            // - Send email notification
            // - Process actual payment via Stripe

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage()
            ], 500);
        }
    }
}
