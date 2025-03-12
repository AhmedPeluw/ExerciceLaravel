<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Payment;

class NewPaymentNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function broadcastOn()
    {
        return new Channel('payments'); // ✅ Vue.js listens to "payments"
    }

    public function broadcastAs()
    {
        return 'NewPaymentNotification'; // ✅ Vue.js listens for ".NewPaymentNotification"
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->payment->id,
            'customer_name' => $this->payment->customer_name,
            'amount' => $this->payment->amount,
            'status' => $this->payment->status,
        ];
    }
}
