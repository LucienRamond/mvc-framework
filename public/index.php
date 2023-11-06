<?php declare(strict_types = 1);

define('ROOT', str_replace('public', '', __DIR__));

require_once ROOT.'/vendor/autoload.php';

$app = new Lram\Foundation\App();

$app->render();