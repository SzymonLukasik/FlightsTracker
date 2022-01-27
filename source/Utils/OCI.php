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

    public function bindRows(
        string $sql,
        &$rows,
        $offset,
        $limit
    ) {
        $this->fetchAll($sql, $rows, $offset, $limit, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
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

    public function bindColsFlatten(
        string $sql,
        &...$vars
    ) {
        $this->bindCols($sql, ...$vars);
        foreach ($vars as &$var) {
            $var = $var[0];
        }
    }

}

?>