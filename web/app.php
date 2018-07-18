<?php

use Symfony\Component\HttpFoundation\Request;

umask(0000);

require __DIR__.'/../vendor/autoload.php';

$kernel = new AppKernel('prod', false);
$kernel = new AppCache($kernel);

Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
