<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tin',
        'phone',
        'email',
        'address',
        'status',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function zones()
    {
        return $this->hasMany(Zone::class);
    }

    public function parkings()
    {
        return $this->hasMany(Parking::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function paymentHistories()
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('status', 'Active')
            ->where('end_date', '>=', now())
            ->latest();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }
}
