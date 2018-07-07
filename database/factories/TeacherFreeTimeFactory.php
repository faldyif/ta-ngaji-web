<?php

use Faker\Generator as Faker;

$factory->define(\App\TeacherFreeTime::class, function (Faker $faker) {
    $randomTeacher = \App\TeacherRegistery::inRandomOrder()->first();
    $dateTimeStart = $faker->dateTimeBetween($startDate = 'now', $endDate = '+1 weeks');
    $dateTimeEnd = new \Carbon\Carbon($dateTimeStart->format(DATE_ISO8601));
    $dateTimeEnd->addHours($faker->numberBetween(1, 3));

    return [
        'teacher_id' => $randomTeacher->id,
        'start_time' => $dateTimeStart,
        'end_time' => $dateTimeEnd,
        'short_place_name' => $faker->streetAddress,
        'latitude' => $faker->latitude($min = -7.89, $max = -7.75),
        'longitude' => $faker->longitude($min = 110.27, $max = 110.48),
    ];
});
