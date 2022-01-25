<?php

namespace FlightsTracker\Utils;

class OCIConnectionError extends \Exception {}

class OCIFetchError extends \Exception {}


class OCI {

    private $conn;
    
    public function __construct() {
       $this->conn = oci_connect(DB_USERNAME, DB_PASSWORD, DSN);
        if (!$this->conn) {
            throw new OCIConnectionError(
                "Can not estabilish connection to the database. " 
                . "Provide correct credentials.\n");
        }
    }
    
    public function __destruct() {
        oci_close($this->conn);
    }

    public function get() {
        return $this->conn;
    }

    /** oci_fetch_all wrapper. */
    public function fetchAll(
        string $sql,
        &$output,
        $offset,
        $limit,
        $flags
    ): int {
        $stid = oci_parse($this->conn, $sql);
        oci_execute($stid);
        $nrows = oci_fetch_all($stid, $output, $offset, $limit, $flags);
        oci_free_statement($stid);
        return $nrows;
    }

    /** Fetches scalar values. */
    public function fetchScalar(string $sql, &$output) {
        $nrows = $this->fetchAll($sql, $array, null, null, OCI_NUM);
        if ($nrows == 0 ) 
            throw new OCIFetchError("Fetched no data.\n");
        if ($nrows > 1 || sizeof($array) > 1)
            throw new OCIFetchError("Fetched data is non-scalar.");
        $output = $array[0][0];
    }

    public function bindCols(
        string $sql,
        &...$vars
    ) {
        $this->fetchAll($sql, $array, null, null, OCI_NUM);
        $nvars = func_num_args() - 1;
        if (sizeof($array) != $nvars)
            throw new \Exception("Invalid number of variables.");
        foreach ($vars as $i => &$var) {
            $var = $array[$i];
        }
    }

    public function bindColsFlat(
        string $sql,
        &...$vars
    ) {
        $this->bindCols($sql, ...$vars);
        foreach ($vars as &$var) {
            $var = $var[0];
        }
    }

}

// $sql = 'SELECT COUNT(*) AS NUM_OF_FLIGHTS FROM Flight';
        // $stid = oci_parse($this->oci->get(), $sql);
        // oci_execute($stid);

        // $nrows = $this->oci->fetchAll($sql, $arr);
        // print_r($nrows . " " . $arr[0][0]);
        // $nrows = oci_fetch_all($stid, $arr, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
        // print_r($arr[0]);
    
        // while (($row = oci_fetch_array($stid)) != false) {
        //     // Use the uppercase column names for the associative array indices
        //     echo $row[0];
        // }
        

        // oci_free_statement($stid);

?>