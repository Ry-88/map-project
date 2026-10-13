<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasAssetInfo;

class Antenna extends Model
{
    use HasAssetInfo;
    
    protected $fillable = ['region_id', 'count', 'type', 'location'];
    public function city() { return $this->belongsTo(City::class, 'region_id'); }
}
