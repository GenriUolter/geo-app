<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeolocationAddressesRequest;
use App\Services\GeolocationService;
use Illuminate\Http\JsonResponse;

class GeolocationController extends Controller
{
    private GeolocationService $geolocationService;

    public function __construct(GeolocationService $geolocationService)
    {
        $this->geolocationService = $geolocationService;
    }

    public function show(GeolocationAddressesRequest $geolocationAddressesRequest): JsonResponse
    {
        $streets = $this->geolocationService->streetsByCoordinates($geolocationAddressesRequest->validated());

        return response()->json($streets);
    }

    public function list($regionId = null): JsonResponse
    {
        $streets = $this->geolocationService->list($regionId);

        return response()->json($streets);
    }
}
