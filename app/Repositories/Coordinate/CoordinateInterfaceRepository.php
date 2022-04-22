<?php

namespace App\Repositories\Coordinate;

use App\Models\Coordinate;

interface CoordinateInterfaceRepository
{
    public function firstOrCreate(string $lat, string $long): Coordinate;
}
