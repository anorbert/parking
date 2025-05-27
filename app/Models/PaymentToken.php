<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentToken extends Model
{
    //    
    protected $fillable = [
        'token',
        'bank_id',
        'expired_at',
    ];

    public function bank()
	{
	    return $this->belongsTo(PaymentHook::class, 'bank_id');
	}
}
