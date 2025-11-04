<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Services\StripeService;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index()
    {
        $paymentMethods = auth()->user()->paymentMethods;
        return view('talent.payments.methods', compact('paymentMethods'));
    }

    public function create()
    {
        $setupIntent = $this->stripeService->createSetupIntent();

        if (!$setupIntent['success']) {
            return back()->with('error', 'Unable to setup payment method. Please try again.');
        }

        return view('talent.payments.create', [
            'clientSecret' => $setupIntent['client_secret']
        ]);
    }

    public function store(Request $request)
    {
        try {
            $this->stripeService->savePaymentMethod(
                auth()->id(),
                $request->input('payment_method_id')
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment method added successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to add payment method: ' . $e->getMessage()
            ], 500);
        }
    }
}
