<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    protected $fillable = [
        'region_id', 'workshop_name', 'location',
        'employees_count', 'engineers_count', 'technicians_count',
        'engineers', 'technicians',
    ];

    protected $casts = [
        'engineers' => 'array',
        'technicians' => 'array',
    ];

    public function city() { return $this->belongsTo(City::class, 'region_id'); }
}
