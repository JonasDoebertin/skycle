<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStravaActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strava_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('athlete_id');
            $table->unsignedBigInteger('foreign_id')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('timezone')->nullable();
            $table->datetime('start_time')->nullable();
            $table->float('start_longitude')->nullable();
            $table->float('start_latitude')->nullable();
            $table->datetime('end_time')->nullable();
            $table->float('end_longitude')->nullable();
            $table->float('end_latitude')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->timestamp('decorated_at')->nullable();
            $table->timestamps();

            $table->foreign('athlete_id')
                ->references('id')->on('strava_athletes')
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
        //
    }
}
