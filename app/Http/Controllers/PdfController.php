<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PdfService;

class PdfController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function downloadReceipt($paymentId)
    {
        return $this->pdfService->generateReceipt($paymentId);
    }
}
