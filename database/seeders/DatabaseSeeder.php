<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            ClientSeeder::class,
            PurposeSeeder::class,
            TestParameterSeeder::class,
            SampleSeeder::class, // if you have sample seeder
        ]);
    }
}