<?php

use FlightsTracker\Router\Route as Route;

$collection = new \FlightsTracker\Router\RouteCollection();

FlightsTracker\Router\Router::setCollection($collection);

$collection->add(
    'homepage/index', 
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
        HTTP_SERVER . "login/tryLogin",
        array(
            'file' => CONTROLLERS_PATH . 'LoginController.php',
            'method' => 'tryLogin',
            'class' => '\FlightsTracker\Controller\LoginController'
        )
    )
);

$collection->add(
    'login/logout',
    new Route(
        HTTP_SERVER . "login/logout",
        array(
            'file' => CONTROLLERS_PATH . 'LoginController.php',
            'method' => 'logout',
            'class' => '\FlightsTracker\Controller\LoginController'
        )
    )
);

$collection->add(
    'registration/index',
    new Route(
        HTTP_SERVER . "registration/index",
        array(
            'file' => CONTROLLERS_PATH . 'RegistrationController.php',
            'method' => 'index',
            'class' => '\FlightsTracker\Controller\RegistrationController'
        )
    )
);

$collection->add(
    'registration/tryRegister',
    new Route(
        HTTP_SERVER . "registration/tryRegister",
        array(
            'file' => CONTROLLERS_PATH . 'RegistrationController.php',
            'method' => 'tryRegister',
            'class' => '\FlightsTracker\Controller\RegistrationController'
        )
    )
);

?>