<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePembacaanSensorRequest;
use App\Http\Requests\UpdatePembacaanSensorRequest;
use App\Http\Resources\Admin\PembacaanSensorResource;
use App\Models\PembacaanSensor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PembacaanSensorApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('pembacaan_sensor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PembacaanSensorResource(PembacaanSensor::all());
    }

    public function store(StorePembacaanSensorRequest $request)
    {
        $pembacaanSensor = PembacaanSensor::create($request->all());

        return (new PembacaanSensorResource($pembacaanSensor))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(PembacaanSensor $pembacaanSensor)
    {
        abort_if(Gate::denies('pembacaan_sensor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PembacaanSensorResource($pembacaanSensor);
    }

    public function update(UpdatePembacaanSensorRequest $request, PembacaanSensor $pembacaanSensor)
    {
        $pembacaanSensor->update($request->all());

        return (new PembacaanSensorResource($pembacaanSensor))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(PembacaanSensor $pembacaanSensor)
    {
        abort_if(Gate::denies('pembacaan_sensor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pembacaanSensor->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
