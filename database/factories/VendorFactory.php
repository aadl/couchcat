<?php

use Faker\Generator as Faker;

$factory->define(App\Vendor::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'contact_name' => $faker->name,
        'contact_email' => $faker->safeEmail,
    ];
});
