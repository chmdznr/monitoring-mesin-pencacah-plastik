<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembacaanSensorsTable extends Migration
{
    public function up()
    {
        Schema::create('pembacaan_sensors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('energi', 15, 4)->nullable();
            $table->float('berat', 15, 4)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
