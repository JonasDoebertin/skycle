<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Darksky\Models\Condition;
use App\Strava\Models\Activity;
use App\Strava\Models\Athlete;
use Faker\Generator as Faker;

$factory->define(Activity::class, function (Faker $faker) {
    return [
        'athlete_id'  => function () {
            return factory(Athlete::class);
        },
        'foreign_id' => $faker->numberBetween(),
        'state'      => 'reported',
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
        'state'           => 'fetched',
    ];
});

$factory->state(Activity::class, 'decorated', function ($faker) {
    return [
        'state' => 'decorated',
    ];
});

$factory->afterCreatingState(Activity::class, 'decorated', function (Activity $activity, Faker $faker) {
    $activity->condition()->save(factory(Condition::class)->make());
});
