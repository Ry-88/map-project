<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name'];

    public function classes()
        {
            return $this->hasMany(City_class::class, 'city_id');
        }
    protected $table = 'cities';
}
