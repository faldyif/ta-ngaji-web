<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

// Generate user
$factory->define(App\User::class, function (Faker $faker) {
    $gender = $faker->randomElements(['male', 'female'])[0];

    return [
        'name' => $faker->name($gender),
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'whatsapp_number' => '+628' . $faker->unique()->randomNumber($nbDigits = 9, $strict = false),
        'gender' => ($gender == 'male' ? 'M' : 'F'),
        'role_id' => 1,
        'verified' => $faker->boolean,
        'profile_pic_path' => $faker->image($dir = 'storage/app/public/temp', $width = 200, $height = 200, 'cats', false),
    ];
});