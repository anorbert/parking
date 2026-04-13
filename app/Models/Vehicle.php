<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Vehicle extends Model
{
    //
    protected $fillable = [
        'plate_number',
        'vehicle_type',
        'owner_name',
        'owner_contact',
        'billing_type',
        'reason',
        'expired_at',
        'company_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
