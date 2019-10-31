<?php

use App\Strava\Models\Activity;
use App\Strava\Models\Athlete;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TinkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jonas = factory(Athlete::class)->create([
            'foreign_id'      => 15149332,
            'first_name'      => 'Jonas',
            'last_name'       => 'Döbertin',
            'profile_picture' => 'https://dgalywyr863hv.cloudfront.net/pictures/athletes/15149332/10828498/1/medium.jpg',
            'access_token'    => 'f7659534f2e9555423a6c9252789561aae064c8b',
            'refresh_token'   => '491bff922d77bf4fc3cbbcc1825df390aa8a6fa8',
            'expires_at'      => Carbon::create(2019, 8, 31, 1, 45, 1, 'UTC'),
        ]);

        $activity = factory(Activity::class)->create([
            'athlete_id' => $jonas->id,
            'foreign_id' => 2664904183,
        ]);
    }
}
