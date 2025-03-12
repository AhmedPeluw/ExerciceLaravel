<?php

namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentRepository
{

    public function getPaymentById($id)
    {
        return Payment::findOrFail($id);
    }

    public function createPayment(array $data)
    {
        return Payment::create([
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'description' => $data['description'],
            'payment_link' => $data['payment_link'],
            'expires_at' => $data['expires_at'],
            'stripe_session_id' => $data['stripe_session_id'],
            'user_id' => Auth::id()
        ]);
    }

    public function getPayments($filters, $user)
    {
        return Payment::when(!$user->hasRole('admin'), function ($query) use ($user) {
            // Regular users can only see their own payments
            $query->where('user_id', $user->id);
        })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(isset($filters['email']), function ($query) use ($filters) {
                $query->where('customer_email', 'LIKE', '%' . $filters['email'] . '%');
            })
            ->when(isset($filters['amount']), function ($query) use ($filters) {
                $query->where('amount', $filters['amount']);
            })
            ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_order'] ?? 'desc')
            ->get();
    }



    public function getPaymentStatistics()
    {

        $user = Auth::user();

        $query = Payment::query();


        $query = Payment::query();

        if (!$user->hasRole('admin')) {
            $query->where('user_id', $user->id);
        }


        $totalStats = $query->selectRaw("
            COUNT(*) as total_transactions,
            SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) as total_amount,
            ROUND(AVG(CASE WHEN status = 'paid' THEN amount ELSE NULL END), 2) as average_payment
        ")->first()->toArray();

        // 🔹 Reset query for monthly transactions
        $monthlyTransactions = Payment::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->when(!$user->hasRole('admin'), function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->groupBy('month')
            ->get();

        // 🔹 Reset query for status distribution
        $statusDistribution = Payment::selectRaw("status, COUNT(*) as count")
            ->when(!$user->hasRole('admin'), function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->groupBy('status')
            ->get();

        // 🔹 Return the data
        return array_merge($totalStats, [
            'monthly_transactions' => $monthlyTransactions,
            'status_distribution' => $statusDistribution
        ]);
    }


    public function deletePayment($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status === 'paid') {
            return false;
        }

        return $payment->delete();
    }
}
