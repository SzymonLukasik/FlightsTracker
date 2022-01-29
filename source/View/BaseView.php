<?php

namespace FlightsTracker\View;

use FlightsTracker\Router\Router as Router;

abstract class BaseView {

    public $data;

    private $renderHeadScript;

    public function __construct($data = null) {
        $this->data = $data;
    }

    public function renderTemplate($path, $renderHeadScript = null) {
        $this->renderHeadScript = $renderHeadScript;
        $this->renderHeader($path);
        $this->renderFile(TEMPLATES_PATH . $path);
        $this->renderFooter($path);
    }

    public function renderScript($path) {
        $path = SCRIPTS_PATH . $path;
        renderFile($path);
    }

    /** Generuje link. */
    public function generateUrl($name, $data=null) {
        $collection = Router::getCollection();
        $route=$collection->get($name);
        if (isset($route)) {
            return $route->generateUrl($data);
        }
        return false;
    }

    /** Wyświetla kod pliku */
    public function renderFile($path) {
        $path = $path . '.php';
        if(is_file($path)) {
            require $path;
        } else {
            throw new \Exception('Can not open file: ' . $path);
        }        
    }

    /** Ładuje nagłówek strony */
    public function renderHeader($path) {
        $headers_path = TEMPLATES_PATH . 'headers/';
        $this->renderFile($headers_path . 'head');

        $header_type = ($path == 'homepage/index' ? 'header' : 'sub-header');
        $this->renderFile($headers_path . $header_type);
    }

    /** Ładuje stopkę strony */
    public function renderFooter($path) {
        $footers_path = TEMPLATES_PATH . 'footers/';
        $footer_type = ($path == 'homepage/index' ? 'footer' : 'sub-footer');
        $this->renderFile($footers_path . $footer_type);
    }
}