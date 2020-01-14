<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    
    return [
        'name' => $faker->name,
        'user_name' => str_random(5),
        'email' => $faker->unique()->safeEmail,
        'remember_token' => str_random(10),
    ];
});
