<?php

namespace FlightsTracker\Model;

include_once('BaseModel.php');

class FlightModel extends \FlightsTracker\Model\BaseModel {
    
    public function getStatistics(&$data) {
        $sql = 'SELECT COUNT(*) FROM Flight';
        $this->oci->bindColsFlatten($sql, $data['n_flights']);
        
        $sql = 
            'WITH A AS (
                SELECT DISTINCT a.id, a.country
                  FROM Flight f
                  JOIN Airport a
                    ON f.aof_dep = a.id OR f.aof_des = a.id
                )
            SELECT COUNT(*), COUNT(DISTINCT A.country)  FROM A';
        
        $this->oci->bindColsFlatten($sql, $data['n_airports'], $data['n_countries']);
    }

    public function getFlightData($params, &$data) {
        $params_cond = [
            'dep_country' => 'a_dep.country = ',
            'dep_city' => 'a_dep.city = ',
            'des_country' => 'a_des.country = ',
            'des_city' => 'a_des.city = '
        ];
        $cond = "";
        foreach ($params as $k => $v) {
            if (!empty($v))
                $cond .= (empty($cond) ? '' : ' AND ') .
                    $params_cond[$k] . "'" . $v . "'";
        }
        $where_statement = (empty($cond) ? '' : 'WHERE ' . $cond);

        $sql = 
            'SELECT f.*
               FROM Flight f
               JOIN Airport a_dep
                 ON f.aof_dep = a_dep.id
               JOIN Airport a_des
                 ON f.aof_des = a_des.id ' . 
               $where_statement .
            ' ORDER BY f.filed_off_block_time, f.filed_arrival_time';
        
        $sql_count = 'SELECT COUNT(*) FROM (' . $sql . ')';

        $this->oci->bindRows($sql, $data['flights'], 0, 10);
        $this->oci->bindColsFlatten($sql_count, $data['n_filtered_flights']);
    }
}

?>