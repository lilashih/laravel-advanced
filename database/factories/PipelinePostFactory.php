<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Pipeline\Post;
use App\Models\Pipeline\User;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'content' => $faker->paragraph,
        'user_id' => factory(User::class),
    ];
});
