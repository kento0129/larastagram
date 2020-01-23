<?php

use App\User;
use App\Follower;
use Faker\Generator as Faker;

$factory->define(App\Follower::class, function (Faker $faker) {
    
    $user1 = factory(App\User::class)->create();
    $user2 = factory(App\User::class)->create();
    
    return [
        'following_id' => $user1->id,
        'followed_id' => $user2->id,
    ];
});
