<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParkingRate extends Model
{
    //
    use HasFactory;
    protected $fillable = ['zone_id', 'duration_minutes', 'rate'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
