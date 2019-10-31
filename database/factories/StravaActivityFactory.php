<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Strava\Models\Activity;
use App\Strava\Models\Athlete;
use Faker\Generator as Faker;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'athlete_id'  => function () {
            return factory(Athlete::class);
        },
        'foreign_id' => $faker->numberBetween(),
    ];
});

$factory->state(Activity::class, 'fetched', function (Faker $faker) {
    return [
        'name'            => $faker->words(4, true),
        'description'     => $faker->sentence(10, true),
        'start_time'      => $faker->dateTimeBetween('-2 day', '-1 day'),
        'start_longitude' => $faker->longitude,
        'start_latitude'  => $faker->latitude,
        'end_time'        => $faker->dateTimeBetween('-1 day', 'now'),
        'end_longitude'   => $faker->longitude,
        'end_latitude'    => $faker->latitude,
        'fetched_at'      => now(),
    ];
});

$factory->state(Activity::class, 'decorated', function ($faker) {
    return [
        'decorated_at' => now(),
    ];
});
