<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/', function (Request $request) use ($container) {
    return new Response(sprintf("The skeleton talks back with %s\n", $container->getParameter('app.name')));
});