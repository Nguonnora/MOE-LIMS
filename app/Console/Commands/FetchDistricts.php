<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Traits\FetchesGeoData;
use App\Models\District;
use Illuminate\Support\Facades\DB;

class FetchDistricts extends Command
{
    use FetchesGeoData;

    protected $signature = 'geo:fetch:districts';
    protected $description = 'Fetch districts from MEF API and store in database';

    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        District::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->fetchAndStore('districts', $this->datasets['districts']);
        $this->info('Districts fetched successfully.');
    }
}