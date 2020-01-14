<?php

use App\Post;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

$factory->define(App\Post::class, function (Faker $faker) {
    
    return [
        'caption' => 'テスト',
    ];
});
