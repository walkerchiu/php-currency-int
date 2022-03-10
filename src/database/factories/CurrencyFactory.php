<?php

/** @var \Illuminate\Database\Eloquent\Factory  $factory */

use Faker\Generator as Faker;
use WalkerChiu\Currency\Models\Entities\Currency;
use WalkerChiu\Currency\Models\Entities\CurrencyLang;

$factory->define(Currency::class, function (Faker $faker) {
    return [
        'serial'       => $faker->isbn10,
        'abbreviation' => $faker->slug,
        'mark'         => $faker->slug
    ];
});

$factory->define(CurrencyLang::class, function (Faker $faker) {
    return [
        'code'  => $faker->locale,
        'key'   => $faker->randomElement(['name', 'description']),
        'value' => $faker->sentence
    ];
});
