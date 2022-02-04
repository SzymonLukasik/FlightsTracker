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
        $offset = null,
        $limit = null
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
        string $table, 
        array $values, 
        array $date_fields = null
    ) {        
        if ($this->getTableArity($table) != \sizeof($values) || 
            \sizeof($values) == 0)
            throw new \Exception("Invalid number of variables.");
    
        $sql = <<<SQL
            INSERT INTO $table VALUES(
        SQL;

        $is_date = function($k) use ($date_fields) {
            return isset($date_fields) && in_array($k, $date_fields);
        };

        foreach ($values as $k => $v) {
            if ($is_date($k))
                $sql .= "to_date(:" . $k . ", 'YYYY-MM-DD'), ";
            else
                $sql .= ':' . $k . ', ';
        }
        $sql = \rtrim($sql, ', ');
        $sql .= ')';

        $compiled = oci_parse($this->conn, $sql);

        foreach ($values as $k => &$v)
                oci_bind_by_name($compiled, ':' . $k, $v);
        
        oci_execute($compiled);
    }

    public function deleteRows(
        string $table, 
        array $conds
    ) {
        $where_conds = '';
        foreach($conds as $column => $_)
            $where_conds .= $column . " = :" . $column . " AND ";
        $where_conds = \rtrim($where_conds, ' AND ');
        $sql = <<<SQL
            DELETE FROM $table
             WHERE $where_conds
        SQL;
        
        $compiled = oci_parse($this->conn, $sql);


        foreach ($conds as $column => &$v) 
                    oci_bind_by_name($compiled, ':' . $column, $v);
        $res= oci_execute($compiled);
    }

    private function getTableArity($table_name) {
        $table_name = strtoupper($table_name);
        $sql = <<<SQL
            SELECT COUNT(*) 
              FROM user_tab_columns
             WHERE table_name = '$table_name'
        SQL;
        $this->bindColsFlatten($sql, $res);
        return $res;
    }
}

?>