<?php

use Faker\Generator as Faker;

$factory->define(App\GoogleAnalytics::class, function (Faker $faker) {
    return [
        'start_date' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
        'page_views' => $faker->numberBetween(1,10000),
        'adsense_revenue' => $faker->numberBetween(1,10000)
    ];
});
