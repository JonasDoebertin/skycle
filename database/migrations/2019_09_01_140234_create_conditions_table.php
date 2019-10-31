<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->string('summary')->nullable();
            $table->string('icon')->nullable();
            $table->tinyInteger('temperature')->nullable();
            $table->tinyInteger('apparent_temperature')->nullable();
            $table->unsignedSmallInteger('wind_bearing')->nullable();
            $table->unsignedSmallInteger('wind_speed')->nullable();
            $table->unsignedSmallInteger('wind_gust')->nullable();
            $table->float('moon_phase')->nullable();
            $table->float('cloud_coverage')->nullable();
            $table->timestamps();

            $table->foreign('activity_id')
                ->references('id')->on('strava_activities')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conditions');
    }
}
