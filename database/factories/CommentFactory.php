<?php

use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        'body' => $faker->paragraphs(2, true),
        'vote' => $faker->numberBetween(-2, 12),
        'offensive_index' => $faker->biasedNumberBetween(0, 10, 'sqrt')
    ];
});
