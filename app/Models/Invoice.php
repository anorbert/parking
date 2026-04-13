<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'amount',
        'status',
        'due_date',
        'reference',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
