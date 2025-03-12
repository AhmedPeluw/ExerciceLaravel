<?php

namespace App\Services;

use App\Repositories\PaymentRepository;

class PaymentService
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getFilteredPayments($filters, $user)
    {
        return $this->paymentRepository->getPayments($filters, $user);
    }

    public function getPaymentDetails($id)
    {
        return $this->paymentRepository->getPaymentById($id);
    }

    public function cancelPayment($id)
    {
        return $this->paymentRepository->deletePayment($id);
    }
}
