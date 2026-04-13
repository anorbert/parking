<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ParkingEntryNotification extends Notification
{
    public string $plateNumber;
    public string $zoneName;

    public function __construct(string $plateNumber, string $zoneName)
    {
        $this->plateNumber = $plateNumber;
        $this->zoneName = $zoneName;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'icon' => 'fa-car',
            'color' => 'blue',
            'title' => 'New Parking Entry',
            'body' => "Vehicle {$this->plateNumber} entered zone {$this->zoneName}.",
        ];
    }
}
