<?php

require_once 'config.php';
require_once 'routes.php';
$router = new FlightsTracker\Router\Router(
    'http://' . $_SERVER["SERVER_NAME"] . ":8001" . $_SERVER["REQUEST_URI"],
    $collection
);
$router->run();
$file = $router->getFile();
$classController = $router->getClass();
$method = $router->getMethod();
require_once $file;
$controller = new $classController();
$controller->$method();