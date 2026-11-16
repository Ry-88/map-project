<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasAssetInfo;

class AccessControl extends Model
{
    use HasAssetInfo;

    protected $fillable = ['region_id','item','count','location','serial_number','status','image'];

    public function city() { return $this->belongsTo(City::class, 'region_id'); }
}
