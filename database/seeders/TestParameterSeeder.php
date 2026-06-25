<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestParameter;

class TestParameterSeeder extends Seeder
{
    public function run()
    {
        $parameters = [
            ['code' => 'PH-001', 'name' => 'pH', 'category' => 'Physical', 'unit' => 'pH', 'method' => 'Electrometric', 'default_price' => 20.00],
            ['code' => 'HM-002', 'name' => 'Lead (Pb)', 'category' => 'Heavy Metal', 'unit' => 'mg/L', 'method' => 'AAS', 'default_price' => 50.00],
            ['code' => 'NUT-001', 'name' => 'Total Nitrogen', 'category' => 'Nutrient', 'unit' => '%', 'method' => 'Kjeldahl', 'default_price' => 80.00],
            ['code' => 'NUT-002', 'name' => 'Available Phosphorus', 'category' => 'Nutrient', 'unit' => 'ppm', 'method' => 'Bray-1', 'default_price' => 70.00],
            ['code' => 'ORG-001', 'name' => 'BOD', 'category' => 'Organic', 'unit' => 'mg/L', 'method' => '5-Day BOD', 'default_price' => 90.00],
            ['code' => 'ORG-002', 'name' => 'COD', 'category' => 'Organic', 'unit' => 'mg/L', 'method' => 'Dichromate', 'default_price' => 80.00],
            ['code' => 'AIR-001', 'name' => 'PM2.5', 'category' => 'Particulate', 'unit' => 'µg/m³', 'method' => 'Gravimetric', 'default_price' => 150.00],
            ['code' => 'AIR-002', 'name' => 'Ozone (O3)', 'category' => 'Gas', 'unit' => 'ppb', 'method' => 'UV Photometric', 'default_price' => 130.00],
        ];

        foreach ($parameters as $param) {
            TestParameter::create($param);
        }
    }
}