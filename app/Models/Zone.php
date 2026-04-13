<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Zone extends Model
{
    //
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'company_id', 'total_slots'];

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function parkingRates()
    {
        return $this->hasMany(ParkingRate::class);
    }
}
