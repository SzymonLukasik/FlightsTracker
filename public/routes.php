<?php

use FlightsTracker\Router\Route as Route;

$collection = new \FlightsTracker\Router\RouteCollection();

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
)

?>