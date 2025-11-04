<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index()
    {
        $payments = Payment::with(['user'])->latest()->paginate(20);
        return view('admin.payments.index', compact('payments'));
    }

    public function create(User $user)
    {
        $paymentMethods = $user->paymentMethods;
        return view('admin.payments.create', compact('user', 'paymentMethods'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => 'required|string',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $result = $this->stripeService->createPayment(
                $user->id,
                $request->amount,
                $request->payment_method_id,
                $request->description
            );

            if ($result['success']) {
                return redirect()->route('admin.payments.index')
                    ->with('success', 'Payment processed successfully');
            }

            return back()->with('error', 'Payment failed. Please try again.');
        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
}
