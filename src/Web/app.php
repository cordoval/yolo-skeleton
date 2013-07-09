<?php

$container = Yolo\createContainer(
    [
        'debug' => $env,
    ],
    [
        new Yolo\DependencyInjection\ConfigurationExtension(),
    ]
);

$app = new Yolo\Application($container);

require __DIR__.'/controllers.php';

return $app;
