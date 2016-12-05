<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Cms\Models\CmsUser::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Noticia::class, function (Faker\Generator $faker) {
    return [
        'imagem' => 'noticia.jpg',
        'titulo' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'descricao' => $faker->paragraph($nbSentences = 5, $variableNbSentences = true),
        'autor' => $faker->name(),
        'fonte' => $faker->word(),
        'link_font' => $faker->url(),
        'cmsuser_id' => 1,
    ];
});