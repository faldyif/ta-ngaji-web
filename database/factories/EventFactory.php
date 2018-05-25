<?php

use Faker\Generator as Faker;

$factory->define(\App\Event::class, function (Faker $faker) {
    $randomTeacher = \App\TeacherRegistery::inRandomOrder()->first();
    $dateTimeStart = $faker->dateTimeBetween($startDate = 'now', $endDate = '+3 months');
    $dateTimeEnd = new \Carbon\Carbon($dateTimeStart->format(DATE_ISO8601));
    $dateTimeEnd->addHours($faker->numberBetween(1, 3));

    return [
        'event_type' => $faker->numberBetween(1, 3),
        'teacher_id' => $randomTeacher->id,
        'short_place_name' => $faker->streetAddress,
        'latitude' => $faker->latitude($min = -7.89, $max = -7.75),
        'longitude' => $faker->longitude($min = 110.27, $max = 110.48),
//        'is_available' => $faker->boolean,
        'start_time' => $dateTimeStart,
        'end_time' => $dateTimeEnd,
    ];
});
