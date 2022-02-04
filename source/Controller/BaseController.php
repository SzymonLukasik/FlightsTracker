<?php

namespace FlightsTracker\Controller;

abstract class BaseController {
    /** Przekierowuje na wskazany adres */
    public function redirect($url) {
        header("location: " . $url);
    }

    /** Generuje link. */
    public function generateUrl($name, $data = null) {
        $collection = \FlightsTracker\Router\Router::getCollection();
        $route = $collection->get($name);
        if (isset($route)) {
            return $route->geneRateUrl($data);
        }
        return false;
    }

    private function getFromArray($keys, $arr, $defaults = null) {
        if (!is_array($keys)) {
            return $arr[$keys] ?? $defaults;
        }
        
        $result = array();
        foreach($keys as $idx => $key) {
            $default = $defaults[$key] ?? null;
            $result[$key] = $arr[$key] ?? $default;
        }

        return $result;
   }

   public function getPost($keys, $defaults = null) {
       return $this->getFromArray($keys, $_POST, $defaults);
   }

    public function getGet($keys, $defaults = null) {
        return $this->getFromArray($keys, $_GET, $defaults);
    }
}