<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Central extends Model
{
    protected $fillable = [
        'region_id', 'type', 'version', 'analog_count', 'digital_count',
        'direct_count', 'ac_units', 'batteries', 'ups',
        'external_capacity', 'internal_capacity', 'central_capacity', 'location',
    ];
    public function city() { return $this->belongsTo(City::class, 'region_id'); }
}
