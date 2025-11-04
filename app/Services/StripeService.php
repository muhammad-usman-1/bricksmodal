<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentMethod;
use Stripe\PaymentIntent;
use App\Models\Payment;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function getOrCreateStripeCustomer(User $user)
    {
        if ($user->stripe_customer_id) {
            return \Stripe\Customer::retrieve($user->stripe_customer_id);
        }

        $customer = \Stripe\Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'phone' => $user->phone_number ? $user->phone_country_code . $user->phone_number : null,
            'metadata' => [
                'user_id' => $user->id
            ]
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer;
    }

    public function createSetupIntent()
    {
        try {
            $setupIntent = \Stripe\SetupIntent::create([
                'usage' => 'off_session', // Allow the card to be charged later
            ]);

            return [
                'success' => true,
                'client_secret' => $setupIntent->client_secret,
            ];
        } catch (Exception $e) {
            Log::error('Stripe setup intent error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function savePaymentMethod($userId, $paymentMethodId)
    {
        try {
            $paymentMethod = PaymentMethod::retrieve($paymentMethodId);

            $card = $paymentMethod->card;

            return PaymentMethodModel::create([
                'user_id' => $userId,
                'stripe_payment_method_id' => $paymentMethodId,
                'card_last_four' => $card->last4,
                'card_brand' => $card->brand,
                'card_exp_month' => $card->exp_month,
                'card_exp_year' => $card->exp_year,
                'is_default' => true,
            ]);
        } catch (Exception $e) {
            Log::error('Save payment method error', [
                'error' => $e->getMessage(),
                'payment_method_id' => $paymentMethodId,
            ]);

            throw $e;
        }
    }

    public function createPayment($userId, $amount, $paymentMethodId, $description = null)
    {
        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => 'usd',
                'payment_method' => $paymentMethodId,
                'confirm' => true,
                'off_session' => true,
                'description' => $description,
            ]);

            $payment = Payment::create([
                'user_id' => $userId,
                'amount' => $amount,
                'currency' => 'USD',
                'stripe_payment_intent_id' => $paymentIntent->id,
                'stripe_payment_method_id' => $paymentMethodId,
                'status' => $paymentIntent->status,
                'description' => $description,
                'paid_at' => $paymentIntent->status === 'succeeded' ? now() : null,
            ]);

            return [
                'success' => $paymentIntent->status === 'succeeded',
                'payment' => $payment,
                'payment_intent' => $paymentIntent,
            ];
        } catch (Exception $e) {
            Log::error('Payment creation error', [
                'error' => $e->getMessage(),
                'amount' => $amount,
                'payment_method_id' => $paymentMethodId,
            ]);

            throw $e;
        }
    }
}
