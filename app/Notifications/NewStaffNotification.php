<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NewStaffNotification extends Notification
{
    public string $staffName;
    public string $roleName;

    public function __construct(string $staffName, string $roleName)
    {
        $this->staffName = $staffName;
        $this->roleName = $roleName;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'icon' => 'fa-user-plus',
            'color' => 'purple',
            'title' => 'New Staff Added',
            'body' => "{$this->staffName} has been added as {$this->roleName}.",
        ];
    }
}
