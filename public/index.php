<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start(); //TODO: czy to trzeba robić gdzieś indziej?
    }
    require_once 'config.php';
    require_once 'routes.php';
    $url = 'http://' . $_SERVER["SERVER_NAME"] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER["REQUEST_URI"];
    debug($url . "\n");
    $router = new FlightsTracker\Router\Router($url);
    $router->run();
    $file = $router->getFile();
    $classController = $router->getClass();
    $method = $router->getMethod();
    debug($file . "\n");
    require_once $file;
    $controller = new $classController();
    $controller->$method();