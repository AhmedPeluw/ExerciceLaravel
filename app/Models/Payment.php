<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_email',
        'amount',
        'currency',
        'description',
        'payment_link',
        'status',
        'expires_at',
        'stripe_session_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
