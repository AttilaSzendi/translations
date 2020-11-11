<?php

/* @var $factory Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\Stilldesign\Translations\Models\Translation::class, function (Faker $faker) {
    return [
        'locale' => $faker->languageCode,
        'namespace' => $faker->text(20),
        'group' => $faker->text(20),
        'item' => $faker->text(20),
        'text' => $faker->text(200),
    ];
});
