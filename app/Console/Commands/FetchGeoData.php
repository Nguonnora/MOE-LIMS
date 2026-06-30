<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchGeoData extends Command
{
    protected $signature = 'geo:fetch';
    protected $description = 'Fetch all geo data from MEF API and store in database';

    public function handle()
    {
        $this->call('geo:fetch:provinces');
        $this->call('geo:fetch:districts');
        $this->call('geo:fetch:communes');
        $this->call('geo:fetch:villages');

        $this->info('All geo data fetched successfully.');
    }
}