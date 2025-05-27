<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PaymentHistory extends Model
{
    //
use HasFactory;

    protected $fillable = [
        'details',
        'bank_id',
        'parking_id',
        'type',
        'trx_ref',
        'gwRef',
        'channel',
        'phone_number',
        'description',
        'status',
        'amount'
    ];
    protected $table = 'payment_histories';

    /**
     * Relationship to the Bank model.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
