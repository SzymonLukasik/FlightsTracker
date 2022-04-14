<?php

namespace FlightsTracker\Model;

include_once('BaseModel.php');

class FlightModel extends \FlightsTracker\Model\BaseModel {
    
    public function getStatistics(&$data) {
        $sql = 'SELECT COUNT(*) FROM Flight';
        $this->oci->bindColsFlatten($sql, $data['n_flights']);
        
        $sql = <<<SQL
            WITH A AS (
                SELECT DISTINCT a.id, a.country
                  FROM Flight f
                  JOIN Airport a
                    ON f.aof_dep = a.id OR f.aof_des = a.id
                )
            SELECT COUNT(*), COUNT(DISTINCT A.country)  FROM A
        SQL;
        
        $this->oci->bindColsFlatten($sql, $data['n_airports'], $data['n_countries']);
    }

    public function getFlightData($params, &$data) {
        $filter_conds = [
            'dep_country' => 'a_dep.country = ',
            'dep_city' => 'a_dep.city = ',
            'des_country' => 'a_des.country = ',
            'des_city' => 'a_des.city = '
        ];
        $cond = "";
        foreach ($params['filter_config'] as $k => $v) {
            if (!empty($v) && $k != 'departure_after')
                $cond .= (empty($cond) ? '' : ' AND ') .
                    $filter_conds[$k] . "'" . $v . "'";
        }
        if (isset($params['filter_config']['departure_after']))
            $cond .= (empty($cond) ? '' : ' AND ') . 
                     "f.filed_off_block_time > to_timestamp('" . $params['filter_config']['departure_after'] . "', 'YYYY-MM-DD" . '"T"'. "HH24:MI')"; 
        $where_statement = (empty($cond) ? '' : 'WHERE ' . $cond);

        $sql = <<<SQL
            SELECT f.*
              FROM Flight f
              JOIN Airport a_dep
                ON f.aof_dep = a_dep.id
              JOIN Airport a_des
                ON f.aof_des = a_des.id
                $where_statement
             ORDER BY f.filed_off_block_time, f.filed_arrival_time
        SQL;
        
        $sql_count = <<<SQL
            SELECT COUNT(*) FROM ($sql)
        SQL;

        $this->oci->bindColsFlatten($sql_count, $data['n_filtered_flights']);
        
        $stride = $params['stride'];
        $b = ($params['page'] - 1) * $stride; 
        $this->oci->bindRows($sql, $data['flights'], $b, $stride);


        $time_fields = [
            "filed_off_block_time", 
            "actual_off_block_time", 
            "filed_arrival_time",
            "actual_arrival_time"
        ];
        
        foreach ($data['flights'] as &$d)
            foreach ($d as $k => &$v)
            if (in_array(\strtolower($k), $time_fields)) {
                    $p = date_parse($v);
                    $v = date('Y-m-d H:i:s', mktime($p['hour'], $p['minute'], $p['second'], $p['month'], $p['day'], $p['year']));
                }
    }
}
?>