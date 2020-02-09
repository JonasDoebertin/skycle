<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Darksky\Models\Condition;
use App\Strava\Models\Activity;
use Faker\Generator as Faker;

$factory->define(Condition::class, function (Faker $faker) {
    return [
        'activity_id'  => function () {
            return factory(Activity::class);
        },
    ];
});
