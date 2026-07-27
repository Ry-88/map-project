<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City_class extends Model
{
        protected $fillable = ['name','city_id'];

        public function city()
            {
                return $this->belongsTo(City::class, 'city_id');
            }

}
