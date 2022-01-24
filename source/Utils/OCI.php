<?php

namespace FlightsTracker\Utils;

class OCIConnectionError extends Exception {}

class OCI {

    private $conn;
    
    public function __construct() {
       $this->conn = oci_connect(DB_USERNAME, DB_PASSWORD, DSN);
        if (!$conn) {
            throw new ConnectionError(
                "Can not estabilish connection to the database. " 
                . "Provide correct credentials.\n");
        }
    }
    
    public function __destruct() {
        oci_close($conn);
    }

    public function conn() {
        return $conn;
    }

    public function fetchAll($sql, $res) {
        $stid = oci_parse($this->conn, $sql);
        oci_execute($stid);
        $nrows = oci_fetch_all($stid, $res);
        oci_free_statement($stid);
        return $nrows;        
    }
}

?>