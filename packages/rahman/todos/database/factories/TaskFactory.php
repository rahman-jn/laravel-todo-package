<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Rahman\Todos\Models\Task;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'title' => $this->faker->text(25),
        'description' => $this->faker->text()
    ];
});
