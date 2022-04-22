<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StreetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $city = $this->city;
        $region = $this->city->region;

        return [
            'name' => $this->name,
            'number' => $this->number,
            'city' => $city->name ?? null,
            'region' => [
                'id' => $region->id ?? null,
                'name' => $region->name ?? null,
            ],
        ];
    }
}
