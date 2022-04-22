<?php

namespace App\Repositories\Coordinate;

use App\Models\Coordinate;

class CoordinateRepository implements CoordinateInterfaceRepository
{
    public function firstOrCreate(string $lat, string $long): Coordinate
    {
        return Coordinate::firstOrCreate([
            'lat' => $lat,
            'long' => $long
        ]);
    }
}
