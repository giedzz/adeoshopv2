<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Product;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'sku' => Str::random(10),
        'status' => true,
        'base_price' => $faker->randomFloat(3, 0, 1000), // password
        'special_price' => $faker->randomFloat(3, 0, 1000),
        'description' => $faker->text(),
        'image' => Str::random(10)
        
    ];
});
