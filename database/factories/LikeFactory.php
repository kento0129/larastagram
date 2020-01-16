<?php

use App\User;
use App\Post;
use App\Like;
use Faker\Generator as Faker;

$factory->define(App\Like::class, function (Faker $faker) {
    
    $post = factory(App\Post::class)->create();

    return [
        'post_id' => $post->id,
        'user_id'  => $post->user_id,
    ];
});
