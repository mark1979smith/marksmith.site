<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';
if (PHP_VERSION_ID < 70000) {
    include_once __DIR__.'/../var/bootstrap.php.cache';
}
// DEV_MODE environment variable set up when running DOCKER Container
if (getenv('DEV_MODE', true) === 'true') {
    $kernel = new AppKernel('dev', true);
    Debug::enable();
} else {
    $kernel = new AppKernel('prod', false);
}
if (PHP_VERSION_ID < 70000) {
    $kernel->loadClassCache();
}

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
