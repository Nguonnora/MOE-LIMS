<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $clients = [
            ['name' => 'ABC Industries', 'email' => 'contact@abc.com', 'phone' => '012345678', 'organization' => 'ABC Corp', 'city' => 'Phnom Penh', 'country' => 'Cambodia'],
            ['name' => 'Green Earth Ltd.', 'email' => 'info@greenearth.com', 'phone' => '098765432', 'organization' => 'Green Earth NGO', 'city' => 'Kampong Cham', 'country' => 'Cambodia'],
            ['name' => 'Clean Water NGO', 'email' => 'test@cleanwater.org', 'phone' => '011223344', 'organization' => 'Clean Water Initiative', 'city' => 'Siem Reap', 'country' => 'Cambodia'],
            ['name' => 'Ministry of Environment', 'email' => 'moe@example.com', 'phone' => '023456789', 'organization' => 'MOE', 'city' => 'Phnom Penh', 'country' => 'Cambodia'],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}