<?php

use App\User;
use App\Post;
use App\Comment;
use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {

    $post = factory(App\Post::class)->create();

    return [
        'comment' => $faker->text,
        'post_id' => $post->id,
        'user_id'  => $post->user_id,
    ];
});
