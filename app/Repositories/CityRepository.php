<?php

namespace App\Repositories;

use App\Interfaces\CityRepositoryInterface;
use App\Models\City;

// Kita implements interface CityRepositoryInterface
class CityRepository implements CityRepositoryInterface
{
    // Kita jalankan fungsi yang ada didalam interface yang kita implements
    public function getAllCities()
    {
        return City::all();
    }

    public function getCityBySlug($slug)
    {
        return City::where('slug', $slug)->first();
    }
}
