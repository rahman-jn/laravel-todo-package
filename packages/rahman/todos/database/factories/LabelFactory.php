<?php


use Rahman\Todos\Models\Label;
/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;

$factory->define(Rahman\Todos\Models\Label::class, function (Faker $faker) {
    return [
        'title' => $this->faker->text(70),
    ];
});
