<?php

    require  dirname(__DIR__) . '/config.php';

    function get_items($field, $airport, $term) {

        $oci = new \FlightsTracker\Utils\OCI();
        
        $sql = 
            "SELECT DISTINCT a." . $field . " AS RESULT " .
            "FROM Flight f
             JOIN Airport a
                  ON f." . $airport . " = a.id
            WHERE a." . $field . " LIKE '%" . $term . "%'
            ORDER BY RESULT ASC
            FETCH FIRST 10 ROWS ONLY";
        
        $oci->bindCols($sql, $countries);

        return json_encode($countries);
    }

    if (isset($_GET['field']) &&
        isset($_GET['airport']) &&
        isset($_GET['term'])
    ) {
        echo get_items($_GET['field'], $_GET['airport'], $_GET['term']);
    }