<?php

use Faker\Generator as Faker;

$factory->define(App\Media::class, function (Faker $faker) {
    return [
        'base_path' => 'http://source.unsplash.com',
        'filename' => '700x400',
        'name' => $faker->text(50),
        'type' => $faker->randomElement(['jpeg', 'png', 'gif', 'bmp']),
        'size' => $faker->randomFloat(2, 10, 5 * 1024 * 1024),
        'optimized' => 'N',
    ];
});
