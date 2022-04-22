<?php

namespace App\Repositories\Street;

use App\Models\Coordinate;
use App\Models\Street;
use Illuminate\Database\Eloquent\Collection;

class StreetRepository implements StreetRepositoryInterface
{

    public function firstOrCreate(string $mame, string $number): Street
    {
        return Street::firstOrCreate(
            ['name' => $mame, 'number' => $number]
        );
    }

    public function getStreetsByCoordinate(Coordinate $coordinate): Collection
    {
        return Street::whereHas('coordinates', function ($query) use ($coordinate) {
            $query->where('id', $coordinate->id);
        })->with('city')->get();
    }

    public function getStreetsByRegion($regionId): Collection
    {
        return Street::whereHas('city', function ($query) use ($regionId) {
            $query->where('region_id', $regionId);
        })->with('city')->get();
    }

    public function getAllStreets(): Collection
    {
        return Street::with('city')->get();
    }
}
