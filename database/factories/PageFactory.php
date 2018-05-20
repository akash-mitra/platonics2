<?php

use Faker\Generator as Faker;

$factory->define(App\Page::class, function (Faker $faker) {
    return [
        'title' => $faker->text(100),
        'summary' => $faker->paragraph(4, true),
        'metakey' => implode(', ', $faker->words($nb = 5, $asText = false)),
        'metadesc' => $faker->paragraph(2, true),
        'media_url' => 'https://source.unsplash.com/700x400'
    ];
});
