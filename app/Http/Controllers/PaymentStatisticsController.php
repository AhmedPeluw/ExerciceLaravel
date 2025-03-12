<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentStatisticsService;
use App\Events\NewPaymentNotification;
use App\Models\Payment;

class PaymentStatisticsController extends Controller
{
    protected $paymentStatisticsService;

    public function __construct(PaymentStatisticsService $paymentStatisticsService)
    {
        $this->paymentStatisticsService = $paymentStatisticsService;
    }

    public function index()
    {
        //broadcast(new NewPaymentNotification(Payment::first()));
        return response()->json($this->paymentStatisticsService->getStatistics());
    }
}
