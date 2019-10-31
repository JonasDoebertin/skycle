<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Base\Models\User;
use App\Strava\Models\Athlete;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Athlete::class, function (Faker $faker) {
    return [
        'user_id'         => function () {
            return factory(User::class);
        },
        'foreign_id'      => $faker->numberBetween(),
        'first_name'      => $faker->firstName,
        'last_name'       => $faker->lastName,
        'profile_picture' => $faker->imageUrl(),
        'refresh_token'   => Str::random('40'),
        'access_token'    => Str::random('40'),
        'expires_at'      => $faker->dateTime,
    ];
});
