<?php

use FlightsTracker\Router\Route as Route;

$collection = new \FlightsTracker\Router\RouteCollection();

FlightsTracker\Router\Router::setCollection($collection);

$collection->add(
    'homepage', 
    new Route(
        HTTP_SERVER,
        array(
            'file' => CONTROLLERS_PATH . 'HomepageController.php',
            'method' => 'index',
            'class' => '\FlightsTracker\Controller\HomepageController'
        )
    )
);

$collection->add(
    'flight/index',
    new Route(
        HTTP_SERVER . "flight/index",
        array(
            'file' => CONTROLLERS_PATH . 'FlightController.php',
            'method' => 'index',
            'class' => '\FlightsTracker\Controller\FlightController'
        )
    )
);

$collection->add(
    'flight/show',
    new Route(
        HTTP_SERVER . "flight/show/<id>",
        array(
            'file' => CONTROLLERS_PATH . 'FlightController.php',
            'method' => 'show_flight',
            'class' => '\FlightsTracker\Controller\FlightController'
        ),
        array(
            'id' => '\d+'
        )
    )
);

$collection->add(
    'login/index',
    new Route(
        HTTP_SERVER . "login/index",
        array(
            'file' => CONTROLLERS_PATH . 'LoginController.php',
            'method' => 'index',
            'class' => '\FlightsTracker\Controller\LoginController'
        )
    )
);

$collection->add(
    'login/tryLogin',
    new Route(
        HTTP_SERVER . "login/index",
        array(
            'file' => CONTROLLERS_PATH . 'LoginController.php',
            'method' => 'tryLogin',
            'class' => '\FlightsTracker\Controller\LoginController'
        )
    )
);

?>