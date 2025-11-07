<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\CastingApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $profile = $request->user('talent')->talentProfile;

        if (!$profile) {
            return redirect()->route('talent.dashboard')->withErrors(['error' => 'Please complete your profile first.']);
        }

        $applications = CastingApplication::with('casting_requirement')
            ->where('talent_profile_id', $profile->id)
            ->where('status', 'selected')
            ->latest()
            ->get();

        return view('talent.payments.index', compact('applications', 'profile'));
    }

    /**
     * Request payment from super admin
     */
    public function requestPayment(CastingApplication $castingApplication)
    {
        $talent = auth('talent')->user();
        $profile = $talent->talentProfile;

        // Verify ownership
        if ($castingApplication->talent_profile_id !== $profile->id) {
            abort(403, 'Unauthorized access.');
        }

        // Check if card details are provided
        if (!$profile->hasCardDetails()) {
            return back()->withErrors(['error' => 'Please add your card details before requesting payment.']);
        }

        // Check if can request payment
        if (!$castingApplication->canRequestPayment()) {
            return back()->withErrors(['error' => 'Cannot request payment for this application.']);
        }

        $castingApplication->update([
            'payment_status' => 'requested',
            'payment_requested_at' => now(),
        ]);

        return back()->with('message', 'Payment request sent successfully. You will be notified once approved.');
    }

    /**
     * Confirm payment received
     */
    public function confirmReceived(CastingApplication $castingApplication)
    {
        $talent = auth('talent')->user();
        $profile = $talent->talentProfile;

        // Verify ownership
        if ($castingApplication->talent_profile_id !== $profile->id) {
            abort(403, 'Unauthorized access.');
        }

        if ($castingApplication->payment_status !== 'released') {
            return back()->withErrors(['error' => 'Payment has not been released yet.']);
        }

        $castingApplication->update([
            'payment_status' => 'received',
            'payment_received_at' => now(),
        ]);

        return back()->with('message', 'Payment receipt confirmed. Thank you!');
    }

    /**
     * Show/Update card details
     */
    public function cardDetails()
    {
        $profile = auth('talent')->user()->talentProfile;

        if (!$profile) {
            return redirect()->route('talent.dashboard')->withErrors(['error' => 'Please complete your profile first.']);
        }

        return view('talent.payments.card-details', compact('profile'));
    }

    /**
     * Store/Update card details
     */
    public function storeCardDetails(Request $request)
    {
        $talent = auth('talent')->user();

        if (!$talent) {
            return redirect()->route('login')->withErrors(['error' => 'Please login as talent.']);
        }

        $profile = $talent->talentProfile;

        if (!$profile) {
            return redirect()->route('talent.dashboard')->withErrors(['error' => 'Please complete your profile first.']);
        }

        $validated = $request->validate([
            'card_holder_name' => 'required|string|max:255',
            'card_number' => 'required|string|min:13|max:19',
        ]);

        // Basic card number validation (remove spaces/dashes)
        $cardNumber = preg_replace('/[\s\-]/', '', $validated['card_number']);

        if (!ctype_digit($cardNumber)) {
            return back()
                ->withInput()
                ->withErrors(['card_number' => 'Card number must contain only digits.']);
        }

        try {
            // Update card details
            $profile->card_holder_name = $validated['card_holder_name'];
            $profile->card_number = $cardNumber;
            $saved = $profile->save();

            // Debug: Log the save operation
            Log::info('Card Details Save Attempt', [
                'profile_id' => $profile->id,
                'user_id' => $talent->id,
                'saved' => $saved,
                'card_holder_name' => $profile->card_holder_name,
                'card_number_length' => strlen($profile->card_number),
                'has_card_details' => $profile->hasCardDetails(),
            ]);

            if (!$saved) {
                Log::error('Card Details Save Failed', [
                    'profile_id' => $profile->id,
                    'user_id' => $talent->id,
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Failed to save card details. Please try again.']);
            }

            // Verify the save
            $profile->refresh();

            if (!$profile->hasCardDetails()) {
                Log::error('Card Details Not Found After Save', [
                    'profile_id' => $profile->id,
                    'card_holder_name' => $profile->card_holder_name,
                    'card_number' => $profile->card_number,
                ]);
                return back()
                    ->withInput()
                    ->withErrors(['error' => 'Card details saved but verification failed. Please try again.']);
            }

            return redirect()
                ->route('talent.payments.index')
                ->with('message', 'Card details saved successfully! Card ending in ' . substr($cardNumber, -4));

        } catch (\Exception $e) {
            Log::error('Card Details Save Exception', [
                'profile_id' => $profile->id,
                'user_id' => $talent->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}
