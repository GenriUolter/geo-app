<?php

namespace App\Repositories\City;

use App\Models\City;

class CityRepository implements CityInterfaceRepository
{

    public function firstOrCreate(string $name): City
    {
        return City::firstOrCreate(
            ['name' => $name]
        );
    }
}
