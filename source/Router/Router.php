<?php

namespace FlightsTracker\Router;

class Router
{
    /** @var String URL do przetworzenia */
    protected $url;
    
    /** @var array Statczny objekt RouteCollection. */
    protected static $collection;
    
    /** @var string Ścieżka do kontrolera */
    protected $file;
    
    /**  @var string Nazwa klasy kontrolera */

    protected $class;
    
    /** @var string Nazwa metody kontrolera */
    protected $method;

    /** Zapisuje URL z obciętym query stringiem. */
    public function __construct($url) {
        if (Router::$collection == null) {
            throw new \Exception("Define routes collection before instantiating Router. ");
        }
        $url=explode('?', $url);
        $this->url = $url[0];
    }

    public function setCollection($collection) {
        Router::$collection = $collection;
    }

    public function getCollection() {
        return Router::$collection;
    }

    public function setClass($class) {
        $this->class = $class;
    }

    public function getClass() {
        return $this->class;
    }

    public function setFile($file) {
        $this->file = $file;
    }

    public function getFile() {
        return $this->file;
    }


    public function setMethod($method) {
        $this->method = $method;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getUrl() {
        return $this->url;
    }

    /** Sprawdza czy URL pasuje do przekazanej reguły. */
    protected function  matchRoute($route) {
        $params = array();
        $key_params = array_keys($route->getParams());
        $value_params = $route->getParams();
        foreach ($key_params as $key) {
            $params['<' . $key . '>'] = $value_params[$key];
        }
        $url = $route->getPath();
        // Zamienia znaczniki na odpowiednie wyrażenia regularne
        $url = str_replace(array_keys($params), $params, $url);
        // Jeżeli brak znacznika w tablicy $params zezwala na dowolny znak
        $url = preg_replace('/<\w+>/', '.*', $url);
        // sprawdza dopasowanie do wzorca
        preg_match("#^$url$#", $this->url, $results);
        if ($results) {
            $this->url=str_replace(array($this->strlcs($url, $this->url)), array(''), $this->url);
            $this->file = $route->getFile();
            $this->class = $route->getClass();
            $this->method = $route->getMethod();
            return true;
        }
        return false;
    }

    /** Szuka odpowiedniej reguły pasującej do URL. Jeżeli znajdzie zwraca true. */
    public function run() {
        foreach (Router::$collection->getAll() as $route) {
            if ($this->matchRoute($route)) {
                $this->setGetData($route);
                return true;
            }
        }
        return false;
    }

    /** */
    protected function setGetData($route)
    {
        $routePath=str_replace(array('(', ')'), array('', ''), $route->getPath());
        $trim=explode('<', $routePath);
        $parsed_url=str_replace(array(HTTP_SERVER), array(''), $this->url);
        $parsed_url=preg_replace("#$trim[0]#", '', $parsed_url, 1);
        // ustawia parametry przekazane w URL
        foreach ($route->getParams() as $key => $param) {
            preg_match("#$param#", $parsed_url, $results);
            if (!empty($results[0])) {
                $_GET[$key] = $results[0];
                $temp_url=explode($results[0], $parsed_url, 2);
               // $parsed_url=str_replace($results[0], '', $temp_url[1]);
                //$parsed_url=preg_replace($patern, '', $temp_url[1], 1);
                $parsed_url=$temp_url[1];
            }
        }
        // jezeli brak parametru w URL ustawia go z tablicy wartości domyślnych
        foreach ($route->getDefaults() as $key => $default) {
            if (!isset($_GET[$key])) {
                $_GET[$key] = $default;
            }
        }
    }

    /**
     * Zwraca część wspólną ciągów
     * @param string $str1 Ciąg 1
     * @param string $str2 Ciąg 2
     * @return string część wspólna
     */
    protected function strlcs($str1, $str2){
        $str1Len = strlen($str1);
        $str2Len = strlen($str2);
        $ret = array();

        if($str1Len == 0 || $str2Len == 0)
            return $ret; //no similarities

        $CSL = array(); //Common Sequence Length array
        $intLargestSize = 0;

//initialize the CSL array to assume there are no similarities
        for($i=0; $i<$str1Len; $i++){
            $CSL[$i] = array();
            for($j=0; $j<$str2Len; $j++){
                $CSL[$i][$j] = 0;
            }
        }

        for($i=0; $i<$str1Len; $i++){
            for($j=0; $j<$str2Len; $j++){
//check every combination of characters
                if( $str1[$i] == $str2[$j] ){
//these are the same in both strings
                    if($i == 0 || $j == 0)
//it's the first character, so it's clearly only 1 character long
                        $CSL[$i][$j] = 1;
                    else
//it's one character longer than the string from the previous character
                        $CSL[$i][$j] = $CSL[$i-1][$j-1] + 1;

                    if( $CSL[$i][$j] > $intLargestSize ){
//remember this as the largest
                        $intLargestSize = $CSL[$i][$j];
//wipe any previous results
                        $ret = array();
//and then fall through to remember this new value
                    }
                    if( $CSL[$i][$j] == $intLargestSize )
//remember the largest string(s)
                        $ret[] = substr($str1, $i-$intLargestSize+1, $intLargestSize);
                }
//else, $CSL should be set to 0, which it was already initialized to
            }
        }
//return the list of matches
        if(isset($ret[0])) {
            return $ret[0];
        } else {
            return '';
        }
    }


} 