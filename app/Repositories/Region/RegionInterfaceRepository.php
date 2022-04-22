<?php

namespace App\Repositories\Region;

use App\Models\Region;

interface RegionInterfaceRepository
{
    public function firstOrCreate(string $name): Region;
}
