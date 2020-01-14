<?php

use App\Comment;
use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'comment' => $faker->text,
    ];
});
