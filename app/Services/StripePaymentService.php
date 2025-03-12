<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Carbon;

class StripePaymentService
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function createPaymentLink(array $data)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Convert amount to cents (Stripe requires amount in cents)
        $amountInCents = $data['amount'] * 100;

        // Create a Stripe Checkout Session
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $data['currency'],
                    'product_data' => ['name' => $data['description']],
                    'unit_amount' => $amountInCents,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => env('APP_URL') . '/success',
            'cancel_url' => env('APP_URL') . '/cancel',
            'expires_at' => Carbon::parse($data['expires_at'])->timestamp,
        ]);

        // Enregistrer le paiement dans la base de données
        return $this->paymentRepository->createPayment([
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'description' => $data['description'],
            'payment_link' => $session->url,
            'expires_at' => $data['expires_at'],
            'stripe_session_id' => $session->id,
        ]);
    }
}
