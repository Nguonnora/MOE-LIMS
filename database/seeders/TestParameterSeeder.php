<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestParameter;

class TestParameterSeeder extends Seeder
{
    public function run()
    {
        $parameters = [
            // Liquid
            ['name' => 'pH', 'category' => 'Physical', 'matrix' => 'Liquid', 'unit' => 'pH', 'method' => 'Electrometric', 'default_price' => 20.00],
            ['name' => 'Lead (Pb)', 'category' => 'Heavy Metal', 'matrix' => 'Liquid', 'unit' => 'mg/L', 'method' => 'AAS', 'default_price' => 50.00],
            ['name' => 'BOD', 'category' => 'Organic', 'matrix' => 'Liquid', 'unit' => 'mg/L', 'method' => '5-Day BOD', 'default_price' => 90.00],
            ['name' => 'COD', 'category' => 'Organic', 'matrix' => 'Liquid', 'unit' => 'mg/L', 'method' => 'Dichromate', 'default_price' => 80.00],
            // Solid
            ['name' => 'Total Nitrogen', 'category' => 'Nutrient', 'matrix' => 'Solid', 'unit' => '%', 'method' => 'Kjeldahl', 'default_price' => 80.00],
            ['name' => 'Available Phosphorus', 'category' => 'Nutrient', 'matrix' => 'Solid', 'unit' => 'ppm', 'method' => 'Bray-1', 'default_price' => 70.00],
            // Gas
            ['name' => 'PM2.5', 'category' => 'Particulate', 'matrix' => 'Gas', 'unit' => 'µg/m³', 'method' => 'Gravimetric', 'default_price' => 150.00],
            ['name' => 'Ozone (O3)', 'category' => 'Gas', 'matrix' => 'Gas', 'unit' => 'ppb', 'method' => 'UV Photometric', 'default_price' => 130.00],
            // Universal (null)
            ['name' => 'Temperature', 'category' => 'Physical', 'matrix' => null, 'unit' => '°C', 'method' => 'Thermometer', 'default_price' => 10.00],
        ];

        foreach ($parameters as $param) {
            // Generate code using the model method
            $param['code'] = TestParameter::generateCode($param['matrix']);
            TestParameter::create($param);
        }
    }
}