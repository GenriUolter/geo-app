<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeolocationAddressesRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'lat' => 'required|between:-90,90',
            'long' => 'required|between:-180,180'
        ];
    }
}
