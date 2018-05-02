<?php

use Faker\Generator as Faker;

$factory->define(App\TeacherRegistery::class, function (Faker $faker) {
    return [
        'registered_from' => $faker->dateTimeThisYear($max = 'now'),
        'minimum_points' => $faker->numberBetween(0,2000),
    ];
});
