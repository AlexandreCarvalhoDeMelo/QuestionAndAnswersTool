<?php
$factory->define(
    App\Question::class,

    function (Faker\Generator $faker): array {
        return [
            'text' => $faker->paragraph,
            'answer' => $faker->boolean
        ];
    }
);