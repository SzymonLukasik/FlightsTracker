<?php

namespace FlightsTracker\View;

use FlightsTracker\Router\Router as Router;

abstract class BaseView {

    public function renderTemplate($path) {
        $this->renderHeader();
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
        if(is_file($path)) {
            require $path;
        } else {
            throw new \Exception('Can not open template '.$name.' in: '.$path);
        }        
    }

    /** Ładuje nagłówek strony */
    public function renderHeader() {
        return $this->renderHTML('header', 'front/');
    }

    /**
     * Ładuje stopkę strony */
    public function renderFooter() {
        return $this->renderHTML('footer', 'front/');
    }
    
    /**
     * It sets data.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function set($name, $value) {
        $this->$name=$value;
    }
    /**
     * It sets data.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function __set($name, $value) {
        $this->$name=$value;
    }
    /**
     * It gets data.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get($name) {
        return $this->$name;
    }
    /**
     * It gets data.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name) {
        if( isset($this->$name) )
            return $this->$name;
        return null;
    }
}