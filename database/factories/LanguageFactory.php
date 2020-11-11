<?php

/* @var $factory Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;

$factory->define(\Stilldesign\Translations\Models\Language::class, function (Faker $faker) {
    return [
        'locale' => $faker->languageCode,
        'name' => $faker->text(100),
    ];
});
