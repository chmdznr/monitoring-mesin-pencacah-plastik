@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.pembacaanSensor.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.pembacaan-sensors.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('energi') ? 'has-error' : '' }}">
                            <label for="energi">{{ trans('cruds.pembacaanSensor.fields.energi') }}</label>
                            <input class="form-control" type="number" name="energi" id="energi" value="{{ old('energi', '0') }}" step="0.0001">
                            @if($errors->has('energi'))
                                <span class="help-block" role="alert">{{ $errors->first('energi') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.pembacaanSensor.fields.energi_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('berat') ? 'has-error' : '' }}">
                            <label for="berat">{{ trans('cruds.pembacaanSensor.fields.berat') }}</label>
                            <input class="form-control" type="number" name="berat" id="berat" value="{{ old('berat', '0') }}" step="0.0001">
                            @if($errors->has('berat'))
                                <span class="help-block" role="alert">{{ $errors->first('berat') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.pembacaanSensor.fields.berat_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection