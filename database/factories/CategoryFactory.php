<?php

use Faker\Generator as Faker;

$factory->define(App\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->colorName,
        'description' => $faker->text(255),
        'access_level' => $faker->randomElement(['F', 'M', 'P', null])
    ];
});
