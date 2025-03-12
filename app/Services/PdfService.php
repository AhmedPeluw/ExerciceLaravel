<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payment;

class PdfService
{
    public function generateReceipt($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);
        $pdf = Pdf::loadView('pdf.receiptFile', compact('payment'));
        return $pdf->download('receipt-' . $payment->id . '.pdf');
    }
}
