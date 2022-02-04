<?php

namespace FlightsTracker\Model;

include_once('BaseModel.php');

class ForumModel extends \FlightsTracker\Model\BaseModel {

    public function getAirlineData($airline, &$data) {
        if (!isset($airline)) {
            //$data['airline_found'] = -1;
            return;
        }
        //TODO: można by za pomocą EXTRACT zrobić np różnicę o 15min
        $sql = <<<SQL
            SELECT COUNT(*) FROM Airline WHERE airline_name = '$airline'
        SQL;
        $this->oci->bindColsFlatten($sql, $data['airline_found']);
        if ($data['airline_found'] == 0) 
            return;

        $sql = <<<SQL
            WITH A as (
                SELECT a.airline_name, ac.stars AS stars
                  FROM Airline a
                  JOIN AirlineComment ac
                    ON a.id = ac.airline_id
                 WHERE a.airline_name = '$airline'
                )
            SELECT COUNT(*), AVG(A.stars) FROM A
        SQL;

        $this->oci->bindColsFlatten($sql, $data['num_comments'], $data['avg_stars']);

        $sql = <<<SQL
            WITH B as (
                SELECT a.airline_name, f.id AS flight_id,
                        CASE WHEN (
                            (f.filed_off_block_time < f.actual_off_block_time) OR 
                            (f.filed_arrival_time < f.actual_arrival_time)
                        ) THEN 1 ELSE 0
                        END AS late
                  FROM Airline a
                  JOIN Flight f
                    ON f.operator_id = a.id
                 WHERE a.airline_name = '$airline'
                )
            SELECT COUNT(*), SUM(B.late) FROM B
        SQL;

        $this->oci->bindColsFlatten($sql, $data['num_flights'], $data['num_late']);
    }

    public function getAirlineNames(&$data) {
        $sql = 'SELECT airline_name FROM Airline';
        $this->oci->bindRows($sql, $data['airlines']);
    }
}

?>