<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class MapsGeocodingService
{
    private const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    private string $apiKey;

    private Client $client;

    private array $searchComponents = [
        'street_number' => 'number',
        'route' => 'street',
        'locality' => 'city',
        'administrative_area_level_1' => 'region'
    ];

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = config('google.map.apiKey');
    }

    public function getStreets(string $lat, string $long): array
    {
        $streets = $this->request($lat, $long);
        return $this->prepareData($streets);
    }

    private function prepareData(array $data): array
    {
        if (empty($data['results'])) {
            return [];
        }

        $streets = [];

        foreach ($data['results'] as $item) {
            if (empty($item['address_components'])) {
                continue;
            }

            $streetData = $this->getStreetData($item['address_components']);

            if (!empty($streetData) && count($streetData) === count($this->searchComponents)) {
                $streets[] = $streetData;
            }
        }

        return $streets;
    }

    private function getStreetData(array $addressComponents): array
    {
        $streetData = [];

        foreach ($addressComponents as $addressComponent) {
            if (empty($addressComponent['types'])) {
                continue;
            }

            foreach ($this->searchComponents as $searchKey => $searchComponent) {
                if (in_array($searchKey, $addressComponent['types'])) {
                    $streetData[$searchComponent] = $addressComponent['long_name'];
                }
            }
        }

        return $streetData;
    }

    private function request(string $lat, string $long)
    {
        try {
            $response = $this->client->get(
                self::API_URL,
                [
                    'query' => [
                        'latlng' => $lat . ',' . $long,
                        'key' => $this->apiKey
                    ]
                ]
            );

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $exception) {
            Log::error(
                'Send to google map api error',
                [
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                    'lat' => $lat,
                    'long' => $long
                ]
            );
        }
    }
}
