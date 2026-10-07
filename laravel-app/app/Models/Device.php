<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['region_id', 'count', 'type', 'location'];
    public function city() { return $this->belongsTo(City::class, 'region_id'); }
}
