<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Traits\FetchesGeoData;
use App\Models\Province;
use Illuminate\Support\Facades\DB;

class FetchProvinces extends Command
{
    use FetchesGeoData;

    protected $signature = 'geo:fetch:provinces';
    protected $description = 'Fetch provinces from MEF API and store in database';

    public function handle()
    {
        // Truncate provinces only
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Province::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->fetchAndStore('provinces', $this->datasets['provinces']);
        $this->info('Provinces fetched successfully.');
    }
}