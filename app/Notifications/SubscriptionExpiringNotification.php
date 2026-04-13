<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class SubscriptionExpiringNotification extends Notification
{
    public int $daysLeft;

    public function __construct(int $daysLeft)
    {
        $this->daysLeft = $daysLeft;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'icon' => 'fa-exclamation-triangle',
            'color' => 'yellow',
            'title' => 'Subscription Expiring',
            'body' => "Your subscription expires in {$this->daysLeft} day(s). Renew to avoid service interruption.",
        ];
    }
}
