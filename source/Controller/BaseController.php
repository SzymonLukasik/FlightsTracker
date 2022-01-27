<?php

namespace FlightsTracker\Controller;

abstract class BaseController {
    /** Przekierowuje na wskazany adres */
    public function redirect($url) {
        header("location: " . $url);
    }

    /** Generuje link. */
    public function generateUrl($name, $data = null) {
        $router = new \FlightsTracker\Router();
        $collection = $router->getCollection();
        $route = $collection->get($name);
        if (isset($route)) {
            return $route->geneRateUrl($data);
        }
        return false;
    }

    private function getFromArray($keys, $arr, $defaults = null) {
        if (!is_array($keys)) {
            return $arr[$key] ?? $defaults;
        }
        
        $result = array();
        if ($defaults != null && sizeof($keys) != $sizeof($defaults))
            throw new \Exception("The number of provided keys and default values are not equal.");
        foreach($keys as $idx => $key) {
            $default = (isset($defaults) ? $defaults[$idx] : null);
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