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
                "Cannot estabilish connection to the database. " 
                . "Please provide correct credentials and try again.\n");
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

    public function insertRow(
        string $table, array $vars, array $date_indices = null
    ) {
        $vars = array_values($vars); //TODO: może usunąć
        $nvars = sizeof($vars);
        $sql = "SELECT COUNT(*) 
                  FROM user_tab_columns
                 WHERE table_name = '" . strtoupper($table) . "'"
        ;
        $this->bindColsFlatten($sql, $table_arity);
        if ($table_arity != $nvars || $nvars == 0)
            throw new \Exception("Invalid number of variables.");
    
        $sql = 'INSERT INTO ' . $table .
            ' VALUES(:val0'
        ;
        for ($i = 1; $i < $nvars; $i++) {
            if (isset($date_indices) && in_array($i, $date_indices))
                $sql .= ", TO_DATE(':val" .$i. "', 'YYYY-MM-DD')";
            else
                $sql .= ', :val'.$i;
        }
        $sql .= ')';

        $compiled = oci_parse($this->conn, $sql);

        foreach ($vars as $i => $var) {
            oci_bind_by_name($compiled, ':val'.$i, $var);
        }
        oci_execute($compiled);
    }

}

?>