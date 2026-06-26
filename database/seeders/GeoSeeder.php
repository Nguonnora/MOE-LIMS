<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\District;
use App\Models\Commune;
use App\Models\Village;

class GeoSeeder extends Seeder
{
    public function run()
    {
        // Insert sample provinces
        $provinces = [
            ['name' => 'Phnom Penh'],
            ['name' => 'Siem Reap'],
            ['name' => 'Battambang'],
        ];

        foreach ($provinces as $p) {
            $province = Province::create($p);
            // Sample districts
            $districts = [
                ['name' => 'Chamkar Mon'],
                ['name' => 'Toul Kork'],
            ];
            if ($province->name == 'Phnom Penh') {
                foreach ($districts as $d) {
                    $district = $province->districts()->create($d);
                    // Sample communes
                    $communes = [
                        ['name' => 'Boeng Keng Kang'],
                        ['name' => 'Toul Tom Poung'],
                    ];
                    foreach ($communes as $c) {
                        $commune = $district->communes()->create($c);
                        // Sample villages
                        $villages = [
                            ['name' => 'Village 1'],
                            ['name' => 'Village 2'],
                        ];
                        foreach ($villages as $v) {
                            $commune->villages()->create($v);
                        }
                    }
                }
            }
        }
    }
}