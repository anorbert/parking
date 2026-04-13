<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class PaymentReceivedNotification extends Notification
{
    public string $plateNumber;
    public int $amount;
    public string $method;

    public function __construct(string $plateNumber, int $amount, string $method)
    {
        $this->plateNumber = $plateNumber;
        $this->amount = $amount;
        $this->method = $method;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'icon' => 'fa-money',
            'color' => 'green',
            'title' => 'Payment Received',
            'body' => number_format($this->amount) . " RWF received for {$this->plateNumber} via " . strtoupper($this->method) . '.',
        ];
    }
}
