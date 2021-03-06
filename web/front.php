<?php

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

if (!isset($env)) {
    http_response_code(503);
    echo 'Front controller must have environment configured.';
    exit;
}

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../src/Web/app.php';
$app->run();