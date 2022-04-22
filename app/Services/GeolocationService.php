<?php

namespace App\Services;

use App\Http\Resources\StreetCollection;
use App\Models\Coordinate;
use App\Repositories\City\CityRepository;
use App\Repositories\Coordinate\CoordinateRepository;
use App\Repositories\Region\RegionRepository;
use App\Repositories\Street\StreetRepository;
use Illuminate\Support\Collection;

class GeolocationService
{
    private MapsGeocodingService $mapsGeocodingService;

    private CoordinateRepository $coordinateRepository;

    private StreetRepository $streetRepository;

    private CityRepository $cityRepository;

    private RegionRepository $regionRepository;

    public function __construct(
        MapsGeocodingService $mapsGeocodingService,
        CoordinateRepository $coordinateRepository,
        StreetRepository $streetRepository,
        CityRepository $cityRepository,
        RegionRepository $regionRepository,
    )
    {
        $this->mapsGeocodingService = $mapsGeocodingService;
        $this->coordinateRepository = $coordinateRepository;
        $this->streetRepository = $streetRepository;
        $this->cityRepository = $cityRepository;
        $this->regionRepository = $regionRepository;
    }

    public function streetsByCoordinates(array $requestData): StreetCollection
    {
        $coordinate = $this->getCoordinate($requestData);
        $streets = $this->getStreets($coordinate);

        return new StreetCollection($streets);
    }

    public function list($regionId = null): StreetCollection
    {
        if ($regionId) {
            $streets = $this->streetRepository->getStreetsByRegion($regionId);
        } else {
            $streets = $this->streetRepository->getAllStreets();
        }

        return new StreetCollection($streets);
    }

    private function getCoordinate(array $coordinates): Coordinate
    {
        return $this->coordinateRepository->firstOrCreate($coordinates['lat'], $coordinates['long']);
    }

    private function getStreets(Coordinate $coordinate): \Illuminate\Database\Eloquent\Collection|Collection
    {
        $streets = $this->streetRepository->getStreetsByCoordinate($coordinate);

        if ($streets->isNotEmpty()) {
            return $streets;
        }

        $mapStreets = $this->mapsGeocodingService->getStreets($coordinate->lat, $coordinate->long);

        return $this->createNewStreets($coordinate, $mapStreets);
    }

    private function createNewStreets(Coordinate $coordinate, array $streetsData): Collection
    {
        $streets = collect();

        foreach ($streetsData as $item) {
            $street = $this->streetRepository->firstOrCreate($item['street'], $item['number']);
            $city = $this->cityRepository->firstOrCreate($item['city']);
            $region = $this->regionRepository->firstOrCreate($item['region']);

            $street->coordinates()->attach($coordinate->id);
            $street->city()->associate($city);
            $street->save();

            $city->region()->associate($region);
            $city->save();

            $streets->add($street);
        }

        return $streets;
    }
}
