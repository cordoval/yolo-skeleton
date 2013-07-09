<?php

$app->get('/', function (Request $request) use ($container) {
    return new Response(sprintf("The skeleton talks back with %s\n", $container->getParameter('app.name')));
});