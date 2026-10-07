<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FiberOptic extends Model
{
    protected $fillable = ['region_id', 'type', 'count', 'location'];
    public function city() { return $this->belongsTo(City::class, 'region_id'); }
}
