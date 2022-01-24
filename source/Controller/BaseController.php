<?php

namespace FlightsTracker\Controller;

abstract class BaseController {
    /** Przekierowuje na wskazany adres */
    public function redirect($url) {
        header("location: " . $url);
    }

    /** Generuje link. */
    public function generateUrl($name, $data = null) {
        $router = new \FlightsTracker\Router('http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
        $collection = $router->getCollection();
        $route = $collection->get($name);
        if (isset($route)) {
            return $route->geneRateUrl($data);
        }
        return false;
    }
}