<?php

use Faker\Generator as Faker;

$factory->define(App\TeacherRegistery::class, function (Faker $faker) {
    return [
        'teacher_level_id' => $faker->numberBetween(1, 3),
        'teacher_competence' => $faker->numberBetween(1, 4),
        'registered_from' => $faker->dateTimeThisYear($max = 'now'),
    ];
});
