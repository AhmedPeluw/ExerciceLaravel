<?php

namespace App\Services;

use App\Repositories\PaymentRepository;

class PaymentStatisticsService
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function getStatistics()
    {
        return $this->paymentRepository->getPaymentStatistics();
    }
}
