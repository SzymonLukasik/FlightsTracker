<?php
    session_start();
    require_once 'config.php';
    require_once 'routes.php';
    $url = 'http://' . $_SERVER["SERVER_NAME"] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER["REQUEST_URI"];
    debug($url);
    $router = new FlightsTracker\Router\Router($url);
    $router->run();
    $file = $router->getFile();
    $classController = $router->getClass();
    $method = $router->getMethod();
    require_once $file;
    $controller = new $classController();
    $controller->$method();