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
    $cities = [
        'الرياض',
        'جدة',
        'مكة المكرمة',
        'المدينة المنورة',
        'الدمام',
        'الخبر',
        'الظهران',
        'الطائف',
        'تبوك',
        'بريدة',
        'عنيزة',
        'حائل',
        'أبها',
        'خميس مشيط',
        'نجران',
        'جازان',
        'الجبيل',
        'ينبع',
        'الخرج',
        'القطيف',
        'الأحساء',
        'عرعر',
        'سكاكا',
        'القريات',
        'الباحة',
        'رابغ',
        'بيشة',
        'القنفذة',
        'محايل عسير',
    ];

      foreach ($cities as $regionName) {
        $region = City::create(['name' => $regionName]);

        City_class::insert([
            ['region_id' => $region->id, 'name' => 'محطات الإعادة'],
            ['region_id' => $region->id, 'name' => 'الهوائيات'],
            ['region_id' => $region->id, 'name' => 'الأجهزة وملحقاتها'],
            ['region_id' => $region->id, 'name' => 'السنترالات'],
            ['region_id' => $region->id, 'name' => 'الألياف البصرية'],
            ['region_id' => $region->id, 'name' => 'الورش والطاقم الفني'],
        ]);
    }
}
}
