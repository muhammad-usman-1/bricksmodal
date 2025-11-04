<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\CastingApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Event;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $webhookSecret
            );
        } catch (SignatureVerificationException $e) {
            Log::error('Webhook signature verification failed.', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Webhook signature verification failed.'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handleSuccessfulPayment($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handleFailedPayment($event->data->object);
                break;
            case 'payment_method.attached':
                $this->handlePaymentMethodAttached($event->data->object);
                break;
            case 'payment_method.detached':
                $this->handlePaymentMethodDetached($event->data->object);
                break;
            default:
                Log::info('Unhandled webhook event type: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleSuccessfulPayment($paymentIntent)
    {
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($payment) {
            $payment->update([
                'status' => 'succeeded',
                'stripe_status' => $paymentIntent->status,
                'stripe_receipt_url' => $paymentIntent->charges->data[0]->receipt_url ?? null,
            ]);

            // If this payment is associated with a casting application, update its status
            if ($payment->castingApplication) {
                $payment->castingApplication->update([
                    'payment_processed' => 'paid'
                ]);
            }
        }
    }

    protected function handleFailedPayment($paymentIntent)
    {
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'stripe_status' => $paymentIntent->status,
                'last_error' => $paymentIntent->last_payment_error->message ?? null,
            ]);

            if ($payment->castingApplication) {
                $payment->castingApplication->update([
                    'payment_processed' => 'failed'
                ]);
            }
        }
    }

    protected function handlePaymentMethodAttached($paymentMethod)
    {
        // This is handled by our PaymentMethodController when adding a card
        Log::info('Payment method attached', ['payment_method_id' => $paymentMethod->id]);
    }

    protected function handlePaymentMethodDetached($paymentMethod)
    {
        // Mark the payment method as deleted in our database
        \App\Models\PaymentMethod::where('stripe_payment_method_id', $paymentMethod->id)
            ->update(['deleted_at' => now()]);
    }
}
