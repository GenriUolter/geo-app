<?php

namespace App\Repositories\City;

use App\Models\City;

interface CityInterfaceRepository
{
    public function firstOrCreate(string $name): City;
}
