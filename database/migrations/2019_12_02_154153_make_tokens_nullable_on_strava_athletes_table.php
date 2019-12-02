<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeTokensNullableOnStravaAthletesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('strava_athletes', function (Blueprint $table) {
            $table->string('refresh_token')->nullable()->change();
            $table->string('access_token')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('strava_athletes', function (Blueprint $table) {
            $table->string('refresh_token')->change();
            $table->string('access_token')->change();
        });
    }
}
