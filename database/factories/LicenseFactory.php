<?php

use Faker\Generator as Faker;

$factory->define(App\License::class, function (Faker $faker) {
    return [
        'license_slug' => $faker->unique->slug(2),
        'starts' => $faker->date,
        'expires' => $faker->date,
    ];
});
