<?php
require_once 'vendor/autoload.php';

$faker = Faker\Factory::create();

$name = $faker->name();
$email = $faker->email();
$text = $faker->word();

echo $name."<hr>";
echo $email."<hr>";
echo $text."<hr>";
