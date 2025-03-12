<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PaymentsExport implements FromCollection, WithHeadings
{

    public function collection()
    {

        $user = Auth::user();
        $user->role = $user->getRoleNames()->first();

        $query = Payment::select('customer_name', 'customer_email', 'amount', 'currency', 'status', 'created_at');

        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Nom du Client', 'Email', 'Montant', 'Devise', 'Statut', 'Date de Création'];
    }
}
