<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->safeLoad();
$dotenv->required(['DB_HOST', 'DB_USERNAME', 'DB_PASSWORD', 'DB_DATABASE_NAME']);

define("DB_HOST", $_ENV['DB_HOST']);
define("DB_USERNAME", $_ENV['DB_USERNAME']);
define("DB_PASSWORD", $_ENV['DB_PASSWORD']);
define("DB_DATABASE_NAME", $_ENV['DB_DATABASE_NAME']);
