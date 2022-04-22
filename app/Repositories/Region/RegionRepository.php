<?php

namespace App\Repositories\Region;

use App\Models\Region;

class RegionRepository implements RegionInterfaceRepository
{

    public function firstOrCreate(string $name): Region
    {
        return Region::firstOrCreate(
            ['name' => $name]
        );
    }
}
