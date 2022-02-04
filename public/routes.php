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
            'method' => 'showFlight',
            'class' => '\FlightsTracker\Controller\FlightController'
        ),
        array(
            'id' => '\d+'
        )
    )
);


$collection->add(
    'tracker/flight/add',
    new Route(
        HTTP_SERVER . "tracker/flight/add/<id>",
        array(
            'file' => CONTROLLERS_PATH . 'TrackerController.php',
            'method' => 'trackerAddFlight',
            'class' => '\FlightsTracker\Controller\TrackerController'
        ),
        array(
            'id' => '\d+'
        )
    )
);

$collection->add(
    'tracker/flight/delete',
    new Route(
        HTTP_SERVER . "tracker/flight/delete/<id>",
        array(
            'file' => CONTROLLERS_PATH . 'TrackerController.php',
            'method' => 'trackerDeleteFlight',
            'class' => '\FlightsTracker\Controller\TrackerController'
        ),
        array(
            'id' => '\d+'
        )
    )
);

$collection->add(
    'flight/add',
    new Route(
        HTTP_SERVER . "flight/add/<id>",
        array(
            'file' => CONTROLLERS_PATH . 'TrackerController.php',
            'method' => 'flightAddFlight',
            'class' => '\FlightsTracker\Controller\TrackerController'
        ),
        array(
            'id' => '\d+'
        )
    )
);

$collection->add(
    'flight/delete',
    new Route(
        HTTP_SERVER . "flight/delete/<id>",
        array(
            'file' => CONTROLLERS_PATH . 'TrackerController.php',
            'method' => 'flightDeleteFlight',
            'class' => '\FlightsTracker\Controller\TrackerController'
        ),
        array(
            'id' => '\d+'
        )
    )
);

$collection->add(
    'tracker/index',
    new Route(
        HTTP_SERVER . "claimtracker/index",
        array(
            'file' => CONTROLLERS_PATH . 'TrackerController.php',
            'method' => 'index',
            'class' => '\FlightsTracker\Controller\TrackerController'
        )
    )
);


$collection->add(
    'account/show',
    new Route(
        HTTP_SERVER . "account/show",
        array(
            'file' => CONTROLLERS_PATH . 'AccountController.php',
            'method' => 'showAccount',
            'class' => '\FlightsTracker\Controller\AccountController'
        )
    )
);


$collection->add(
    'account/delete',
    new Route(
        HTTP_SERVER . "account/delete",
        array(
            'file' => CONTROLLERS_PATH . 'AccountController.php',
            'method' => 'deleteAccount',
            'class' => '\FlightsTracker\Controller\AccountController'
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

$collection->add(
    'forum/index',
    new Route(
        HTTP_SERVER . "forum/index",
        array(
            'file' => CONTROLLERS_PATH . 'ForumController.php',
            'method' => 'index',
            'class' => '\FlightsTracker\Controller\ForumController'
        )
    )
);

?>