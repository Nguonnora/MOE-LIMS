<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Commands\Traits\FetchesGeoData;
use App\Models\Commune;
use Illuminate\Support\Facades\DB;

class FetchCommunes extends Command
{
    use FetchesGeoData;

    protected $signature = 'geo:fetch:communes';
    protected $description = 'Fetch communes from MEF API and store in database';

    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Commune::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->fetchAndStore('communes', $this->datasets['communes']);
        $this->info('Communes fetched successfully.');
    }
}