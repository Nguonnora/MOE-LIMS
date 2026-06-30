<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Traits\FetchesGeoData;
use App\Models\Village;
use Illuminate\Support\Facades\DB;

class FetchVillages extends Command
{
    use FetchesGeoData;

    protected $signature = 'geo:fetch:villages';
    protected $description = 'Fetch villages from MEF API and store in database';

    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Village::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->fetchAndStore('villages', $this->datasets['villages']);
        $this->info('Villages fetched successfully.');
    }
}