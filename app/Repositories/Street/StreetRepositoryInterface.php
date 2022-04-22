<?php

namespace App\Repositories\Street;

use App\Models\Coordinate;
use App\Models\Street;
use Illuminate\Database\Eloquent\Collection;

interface StreetRepositoryInterface
{
    public function firstOrCreate(string $mame, string $number): Street;
    public function getStreetsByCoordinate(Coordinate $coordinate): Collection;
    public function getStreetsByRegion($regionId): Collection;
    public function getAllStreets(): Collection;
}
