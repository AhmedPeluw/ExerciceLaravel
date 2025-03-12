<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use App\Events\NewPaymentNotification;


class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error("Stripe Webhook Error: " . $e->getMessage());
            return response()->json(['error' => 'Invalid Webhook Signature'], 400);
        }

        // Handle checkout session completion
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            Log::info("Stripe Webhook Received: checkout.session.completed - " . json_encode($session));

            $payment = Payment::where('stripe_session_id', $session->id)->first();

            if ($payment) {
                $payment->update(['status' => 'paid']);
                Log::info("Payment Updated: " . $payment->id);

                // Send event
                broadcast(new NewPaymentNotification($payment));
            } else {
                Log::error("No payment found for session ID: " . $session->id);
            }
        }

        return response()->json(['message' => 'Webhook received']);
    }

    public function handleExpirationWebhook(Request $request)
    {

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->all();

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error("Stripe Webhook Error: " . $e->getMessage());
            return response()->json(['error' => 'Invalid Webhook Signature'], 400);
        }


        if ($event->type === 'checkout.session.expired') {

            $session = $event->data->object;

            $sessionId = $session->id;

            $payment = Payment::where('stripe_session_id', $sessionId)->first();

            if ($payment) {
                $payment->update(['status' => 'expired']);
            } else {
                Log::error("No payment found for session ID: " . $session->id);
            }

            Log::info("Payment link expired for session: $sessionId");
        }

        return response()->json(['message' => 'Webhook received']);
    }
}
