<?php

use Faker\Generator as Faker;

$factory->define(App\Content::class, function (Faker $faker) {
    $body = '<p>' . $faker->paragraph . '</p>'
            . '<h3>' . $faker->sentence . '</h3>'
            . '<p>' . $faker->paragraph . '</p>'
            . '<h3>' . $faker->sentence . '</h3>'
            . '<p>' . $faker->paragraph . '</p>';

    return [
        'body' => $body
    ];
});
