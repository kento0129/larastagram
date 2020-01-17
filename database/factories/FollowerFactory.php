<?php

use App\User;
use App\Follower;
use Faker\Generator as Faker;

$factory->define(App\Follower::class, function (Faker $faker) {
    
    $user = factory(App\User::class)->create();
    
    return [
        'followed_id' => $user->id,
    ];
});
