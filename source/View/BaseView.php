<?php

namespace FlightsTracker\View;

use FlightsTracker\Router\Router as Router;

abstract class BaseView {

    public $data;

    public function __construct($data = null) {
        $this->data = $data;
    }

    public function renderTemplate($path) {
        $this->renderHeader($path);
        $this->renderHTML($path);
        $this->renderFooter();
    }

    /** Generuje link. */
    public function generateUrl($name, $data=null) {
        $router = new Router(
            'http://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
        $collection = $router->getCollection();
        $route=$collection->get($name);
        if (isset($route)) {
            return $route->generateUrl($data);
        }
        return false;
    }

    /** Wyświetla kod HTML szablonu */
    public function renderHTML($path='') {
        $path = TEMPLATES_PATH . $path . '.php';
        //print $path;
        if(is_file($path)) {
            require $path;
        } else {
            throw new \Exception('Can not open template: ' . $path);
        }        
    }

    /** Ładuje nagłówek strony */
    public function renderHeader($path) {
        $this->renderHTML('headers/head');
        $header_type = $path == 'index' ? 'header' : 'sub-header';
        $this->renderHTML('headers/' . $header_type);
    }

    /**
     * Ładuje stopkę strony */
    public function renderFooter() {
        return $this->renderHTML('footer');
    }
}