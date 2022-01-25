<?php

namespace FlightsTracker\Model;

include_once('BaseModel.php');

class FlightModel extends \FlightsTracker\Model\BaseModel {
    
    public function getIndexData() {
        $data = array();

        $sql = 'SELECT COUNT(*) FROM Flight';
        $this->oci->fetchScalar($sql, $data['n_flights']);
        
        $sql = 
            'WITH A AS (
                SELECT DISTINCT a.id, a.country
                  FROM Flight f
                  JOIN Airport a
                    ON f.aof_dep = a.id OR f.aof_des = a.id
                )
            SELECT COUNT(*), COUNT(DISTINCT A.country)  FROM A';
        
        $this->oci->bindColsFlat($sql, $data['n_airports'], $data['n_countries']);

        return $data;
    }
}

?>