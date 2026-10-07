<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepeaterStation extends Model
{
    protected $table = 'stations';
    protected $fillable = ['region_id', 'count', 'type', 'location'];
    public function city() { return $this->belongsTo(City::class, 'region_id'); }
}
