<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use Illuminate\Support\Facades\Auth;

class PaymentExportController extends Controller
{
    public function exportCSV()
    {
        return Excel::download(new PaymentsExport, 'payments.csv');
    }

    public function exportExcel()
    {
        return Excel::download(new PaymentsExport, 'payments.xlsx');
    }
}
