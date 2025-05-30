<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parking extends Model
{
    //
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'plate_number',
        'driver_name',
        'entry_time',
        'exit_time',
        'bill',
        'user_id',
        'status',
        'zone_id',
        'phone_number',
        'payment_method',
    ];
    protected $casts = [
        'entry_time' => 'datetime',
        'exit_time' => 'datetime',
    ];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
