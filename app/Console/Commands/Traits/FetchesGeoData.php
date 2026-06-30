<?php

namespace App\Console\Commands\Traits;

use App\Models\Province;
use App\Models\District;
use App\Models\Commune;
use App\Models\Village;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait FetchesGeoData
{
    protected $baseUrl = 'https://data.mef.gov.kh/api/v1/public-datasets';
    protected $datasets = [
        'provinces' => 'pd_66a8603700604c000123e144',
        'districts' => 'pd_66a8603800604c000123e145',
        'communes'  => 'pd_66a8603900604c000123e146',
        'villages'  => 'pd_66a8603a00604c000123e147',
    ];

    protected $models = [
        'provinces' => Province::class,
        'districts' => District::class,
        'communes'  => Commune::class,
        'villages'  => Village::class,
    ];

    protected $prefixMap = [
        'provinces' => 'province',
        'districts' => 'district',
        'communes'  => 'commune',
        'villages'  => 'village',
    ];

    protected $parentFieldMap = [
        'provinces' => null,
        'districts' => 'province_code',
        'communes'  => 'district_code',
        'villages'  => 'commune_code',
    ];

    /**
     * Get the priority order of keys to try for parent code.
     */
    protected function getParentKeys($level)
    {
        $map = [
            'districts' => ['province_code', 'province_id', 'parent_id'],
            'communes'  => ['district_code', 'district_id', 'parent_id'],
            'villages'  => ['commune_code', 'commune_id', 'parent_id'],
        ];
        return $map[$level] ?? ['parent_id'];
    }

    protected function fetchAndStore($level, $datasetId)
    {
        $page = 1;
        $pageSize = 100;
        $lastPage = null;
        $totalInserted = 0;
        $totalSkipped = 0;
        $model = $this->models[$level];
        $prefix = $this->prefixMap[$level];
        $parentField = $this->parentFieldMap[$level];
        $parentKeys = $this->getParentKeys($level);

        $this->info("Fetching $level from API...");

        do {
            $url = $this->baseUrl . '/' . $datasetId . '/json?page=' . $page . '&page_size=' . $pageSize;
            $this->info("Page $page...");

            // Exponential backoff for 429
            $response = null;
            $attempts = 0;
            $maxAttempts = 6;
            $baseDelay = 5;

            while ($attempts < $maxAttempts) {
                try {
                    $response = Http::withoutVerifying()->timeout(30)->get($url);
                    if ($response->status() == 429) {
                        $delay = $baseDelay * pow(2, $attempts);
                        $this->warn("Rate limit hit (429). Waiting {$delay} seconds...");
                        sleep($delay);
                        $attempts++;
                        continue;
                    }
                    break;
                } catch (\Exception $e) {
                    $this->error("Request failed: " . $e->getMessage());
                    sleep(2);
                    $attempts++;
                }
            }

            if (!$response || !$response->successful()) {
                $this->error("Failed to fetch $level page $page after multiple retries.");
                break;
            }

            $json = $response->json();
            $items = $json['data'] ?? $json['results'] ?? $json['items'] ?? [];
            if (empty($items)) {
                $this->warn("No items for $level page $page");
                break;
            }

            if ($page === 1) {
                $first = $items[0];
                $this->info("First item keys: " . implode(', ', array_keys($first)));
                Log::info("GeoFetch: First $level item", $first);
            }

            $records = [];
            $skipped = 0;
            foreach ($items as $item) {
                // Extract code
                $code = $item[$prefix . '_code'] ?? $item['code'] ?? null;
                if ($code === null) {
                    $skipped++;
                    continue;
                }

                // Extract names
                $nameKh = $item[$prefix . '_kh'] ?? $item['name_kh'] ?? null;
                $nameEn = $item[$prefix . '_en'] ?? $item['name_en'] ?? null;
                $name   = $item['name'] ?? null;

                $record = [
                    'code'     => $code,
                    'name_kh'  => $nameKh,
                    'name_en'  => $nameEn,
                    'name'     => $name,
                ];

                if ($parentField !== null) {
                    // Try parent keys in priority order
                    $parentCode = null;
                    foreach ($parentKeys as $key) {
                        if (!empty($item[$key])) {
                            $parentCode = $item[$key];
                            break;
                        }
                    }

                    // Fallback to generic lookups if still missing
                    if ($parentCode === null) {
                        $parentCode = $item[$prefix . '_parent_code'] ?? 
                                      $item['province_code'] ?? 
                                      $item['district_code'] ?? 
                                      $item['commune_code'] ?? 
                                      null;
                    }

                    if ($parentCode === null) {
                        $skipped++;
                        continue;
                    }

                    $record[$parentField] = $parentCode;
                }

                $records[] = $record;
            }

            $totalSkipped += $skipped;

            if (empty($records)) {
                $this->warn("No valid records for $level page $page (skipped $skipped)");
                break;
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach (array_chunk($records, 500) as $chunk) {
                try {
                    $model::insertOrIgnore($chunk);
                    $totalInserted += count($chunk);
                } catch (\Exception $e) {
                    $this->error("Insert error: " . $e->getMessage());
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $this->info("Inserted " . count($records) . " records for page $page (skipped $skipped)");

            $meta = $json['meta'] ?? $json['pagination'] ?? [];
            if (isset($meta['last_page'])) {
                $lastPage = $meta['last_page'];
            } elseif (isset($meta['total']) && isset($meta['per_page'])) {
                $lastPage = ceil($meta['total'] / $meta['per_page']);
            }

            if ($lastPage !== null && $page >= $lastPage) {
                break;
            }
            if (count($items) < $pageSize) {
                break;
            }

            $page++;
            $this->info("Sleeping 1 second...");
            sleep(1);
        } while (true);

        $this->info("Finished fetching $level. Total inserted: $totalInserted, total skipped: $totalSkipped");
    }
}