<?php

use Faker\Generator as Faker;

$factory->define(App\License::class, function (Faker $faker) {
    return [
        'statistics_stub' => $faker->slug,
        'starts' => $faker->date,
    ];
});
