<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->string('iata_code')->primary();
            $table->string('city_name_en')->nullable();
            $table->string('city_name_ru')->nullable();
            $table->string('airport_name_en')->nullable();
            $table->string('airport_name_ru')->nullable();
            $table->string('country');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('timezone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('airports');
    }
};
