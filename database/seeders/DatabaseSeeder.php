<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            SampleSeeder::class,
        ]);

        $this->call([
    UserSeeder::class,
    TestParameterSeeder::class,
    SampleSeeder::class,
]);
    }

}