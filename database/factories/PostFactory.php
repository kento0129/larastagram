<?php

use App\User;
use App\Post;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(App\Post::class, function (Faker $faker) {
    
    $file = UploadedFile::fake()->image('public.jpg')->storeAs('public/post_images','test.jpg');
    
    return [
        'user_id'  => function(){
            return factory(App\User::class)->create()->id;
        },
        'post_photo' => 'test.jpg',
        'caption' => 'テスト',
    ];
});
