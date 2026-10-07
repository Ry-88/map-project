<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\City_class;


class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $regions = [
            ['code' => 'SA01', 'name' => 'منطقة الرياض'],
            ['code' => 'SA02', 'name' => 'منطقة مكة المكرمة'],
            ['code' => 'SA03', 'name' => 'منطقة المدينة المنورة'],
            ['code' => 'SA04', 'name' => 'المنطقة الشرقية'],
            ['code' => 'SA05', 'name' => 'منطقة القصيم'],
            ['code' => 'SA06', 'name' => 'منطقة عسير'],
            ['code' => 'SA07', 'name' => 'منطقة تبوك'],
            ['code' => 'SA08', 'name' => 'منطقة حائل'],
            ['code' => 'SA09', 'name' => 'منطقة الحدود الشمالية'],
            ['code' => 'SA10', 'name' => 'منطقة جازان'],
            ['code' => 'SA11', 'name' => 'منطقة نجران'],
            ['code' => 'SA12', 'name' => 'منطقة الباحة'],
            ['code' => 'SA13', 'name' => 'منطقة الجوف'],
        ];

      foreach ($regions as $regionData) {
        $region = City::create($regionData);

        City_class::insert([
            ['city_id' => $region->id, 'name' => 'محطات الإعادة'],
            ['city_id' => $region->id, 'name' => 'الهوائيات'],
            ['city_id' => $region->id, 'name' => 'الأجهزة وملحقاتها'],
            ['city_id' => $region->id, 'name' => 'السنترالات'],
            ['city_id' => $region->id, 'name' => 'الألياف البصرية'],
            ['city_id' => $region->id, 'name' => 'الورش والطاقم الفني'],
        ]);
    }
}
}
