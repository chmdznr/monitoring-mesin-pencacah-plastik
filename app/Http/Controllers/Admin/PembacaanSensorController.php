<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPembacaanSensorRequest;
use App\Http\Requests\StorePembacaanSensorRequest;
use App\Http\Requests\UpdatePembacaanSensorRequest;
use App\Models\PembacaanSensor;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PembacaanSensorController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('pembacaan_sensor_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = PembacaanSensor::query()->select(sprintf('%s.*', (new PembacaanSensor)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'pembacaan_sensor_show';
                $editGate      = 'pembacaan_sensor_edit';
                $deleteGate    = 'pembacaan_sensor_delete';
                $crudRoutePart = 'pembacaan-sensors';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id;
            });
            $table->editColumn('energi', function ($row) {
                return $row->energi;
            });
            $table->editColumn('berat', function ($row) {
                return $row->berat;
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.pembacaanSensors.index');
    }

    public function create()
    {
        abort_if(Gate::denies('pembacaan_sensor_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.pembacaanSensors.create');
    }

    public function store(StorePembacaanSensorRequest $request)
    {
        $pembacaanSensor = PembacaanSensor::create($request->all());

        return redirect()->route('admin.pembacaan-sensors.index');
    }

    public function edit(PembacaanSensor $pembacaanSensor)
    {
        abort_if(Gate::denies('pembacaan_sensor_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.pembacaanSensors.edit', compact('pembacaanSensor'));
    }

    public function update(UpdatePembacaanSensorRequest $request, PembacaanSensor $pembacaanSensor)
    {
        $pembacaanSensor->update($request->all());

        return redirect()->route('admin.pembacaan-sensors.index');
    }

    public function show(PembacaanSensor $pembacaanSensor)
    {
        abort_if(Gate::denies('pembacaan_sensor_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.pembacaanSensors.show', compact('pembacaanSensor'));
    }

    public function destroy(PembacaanSensor $pembacaanSensor)
    {
        abort_if(Gate::denies('pembacaan_sensor_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pembacaanSensor->delete();

        return back();
    }

    public function massDestroy(MassDestroyPembacaanSensorRequest $request)
    {
        $pembacaanSensors = PembacaanSensor::find(request('ids'));

        foreach ($pembacaanSensors as $pembacaanSensor) {
            $pembacaanSensor->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
