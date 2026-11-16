<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = ['code', 'name'];
    protected $table = 'cities';

    public function classes()
    {
        return $this->hasMany(City_class::class, 'city_id');
    }

    public function repeaterStations(): HasMany
    {
        return $this->hasMany(RepeaterStation::class, 'region_id');
    }

    public function antennas(): HasMany
    {
        return $this->hasMany(Antenna::class, 'region_id');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'region_id');
    }

    public function centrals(): HasMany
    {
        return $this->hasMany(Central::class, 'region_id');
    }

    public function fiberOptics(): HasMany
    {
        return $this->hasMany(FiberOptic::class, 'region_id');
    }

    public function workshops(): HasMany
    {
        return $this->hasMany(Workshop::class, 'region_id');
    }

    public function cameras(): HasMany
    {
        return $this->hasMany(Camera::class, 'region_id');
    }

    public function alarms(): HasMany
    {
        return $this->hasMany(Alarm::class, 'region_id');
    }

    public function accessControls(): HasMany
    {
        return $this->hasMany(AccessControl::class, 'region_id');
    }
}
