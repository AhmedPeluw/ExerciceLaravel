<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripePaymentService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;


class PaymentController extends Controller
{
    protected $stripePaymentService;
    protected $paymentService;

    public function __construct(StripePaymentService $stripePaymentService, PaymentService $paymentService)
    {
        $this->stripePaymentService = $stripePaymentService;
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $payments = $this->paymentService->getFilteredPayments($request->all(), Auth::user());
        return response()->json($payments);
    }

    public function createPayment(Request $request)
    {

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => [
                'required',
                'email:rfc,dns',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            ],
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'description' => 'required|string',
            'expires_at' => 'required|date|after:now',
        ]);

        $payment = $this->stripePaymentService->createPaymentLink($request->all());

        return response()->json(['message' => 'Payment link created', 'payment' => $payment]);
    }

    public function show($id)
    {
        return response()->json($this->paymentService->getPaymentDetails($id));
    }

    public function destroy($id)
    {
        $deleted = $this->paymentService->cancelPayment($id);

        if (!$deleted) {
            return response()->json(['error' => 'Cannot delete a paid payment'], 403);
        }

        return response()->json(['message' => 'Payment canceled successfully']);
    }
}
