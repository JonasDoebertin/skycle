<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateToStravaActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('strava_activities', function (Blueprint $table) {
            $table->dropColumn('fetched_at');
        });

        Schema::table('strava_activities', function (Blueprint $table) {
            $table->dropColumn('decorated_at');
        });

        Schema::table('strava_activities', function (Blueprint $table) {
            $table->string('state')->default('reported')->index()->after('end_latitude');
            $table->timestamp('state_updated_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        //
    }
}
