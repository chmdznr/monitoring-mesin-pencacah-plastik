<?php

namespace App\Http\Requests;

use App\Models\PembacaanSensor;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePembacaanSensorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pembacaan_sensor_create');
    }

    public function rules()
    {
        return [
            'energi' => [
                'numeric',
            ],
            'berat' => [
                'numeric',
            ],
        ];
    }
}
