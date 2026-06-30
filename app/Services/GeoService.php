<?php

namespace App\Services;

use App\Models\Province;
use App\Models\District;
use App\Models\Commune;
use App\Models\Village;
use Illuminate\Support\Facades\Log;

class GeoService
{
    public function getProvinces()
    {
        return Province::orderBy('code')
            ->get(['code as id', 'name_kh as name'])
            ->toArray();
    }

    public function getDistricts($provinceCode)
    {
        $provinceCode = str_pad($provinceCode, 2, '0', STR_PAD_LEFT);
        $districts = District::where('province_code', $provinceCode)
            ->orderBy('code')
            ->get(['code as id', 'name_kh as name'])
            ->toArray();

        Log::info("Districts for province $provinceCode: " . count($districts) . " found.");
        return $districts;
    }

    public function getCommunes($districtCode)
    {
        // Pad to 4 digits (e.g., "102" → "0102")
        $districtCode = str_pad($districtCode, 4, '0', STR_PAD_LEFT);
        \Log::info("GeoService: Querying communes for district code: $districtCode");

        $communes = Commune::where('district_code', $districtCode)
            ->orderBy('code')
            ->get(['code as id', 'name_kh as name'])
            ->toArray();

        \Log::info("GeoService: Found " . count($communes) . " communes for district $districtCode");
        return $communes;
    }

    public function getVillages($communeCode)
    {
        // Commune codes are 6 digits (e.g., "010201")
        $communeCode = str_pad($communeCode, 6, '0', STR_PAD_LEFT);
        $villages = Village::where('commune_code', $communeCode)
            ->orderBy('code')
            ->get(['code as id', 'name_kh as name'])
            ->toArray();

        Log::info("Villages for commune $communeCode: " . count($villages) . " found.");
        return $villages;
    }
}