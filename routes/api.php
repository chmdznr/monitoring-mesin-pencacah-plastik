<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Pembacaan Sensor
    Route::apiResource('pembacaan-sensors', 'PembacaanSensorApiController');
});
