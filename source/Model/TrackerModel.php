<?php

namespace FlightsTracker\Model;

include_once('BaseModel.php');

class TrackerModel extends \FlightsTracker\Model\BaseModel {
 
    public function addFlight($data) {
        $this->oci->insertRow('FlightAttendant', $data);
    }

	public function deleteFlight($username, $id) {
		$this->oci->deleteRows('FlightAttendant', ['flight_id' => $id, 'attendant_id' => $username]);
	}

    public function getTrackedFlights($username, &$data) {
        $sql = <<<SQL
            SELECT f.*
              FROM Flight f
              JOIN FlightAttendant a
                ON f.id = a.flight_id
              JOIN Airport a_dep
                ON f.aof_dep = a_dep.id
              JOIN Airport a_des
                ON f.aof_des = a_des.id
             WHERE a.attendant_id = '$username'
             ORDER BY f.filed_off_block_time, f.filed_arrival_time
        SQL;
        
        $sql_count = <<<SQL
            SELECT COUNT(*) FROM ($sql)
        SQL;

        $this->oci->bindColsFlatten($sql_count, $data['n_tracked_flights']);
        $this->oci->bindRows($sql, $data['flights']);


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

    public function getTrackedFlightsIds($username, &$data) {
		$sql = <<<SQL
            SELECT f.id
              FROM Flight f
              JOIN FlightAttendant a
                ON f.id = a.flight_id
              JOIN Airport a_dep
                ON f.aof_dep = a_dep.id
              JOIN Airport a_des
                ON f.aof_des = a_des.id
             WHERE a.attendant_id = '$username'
             ORDER BY f.filed_off_block_time, f.filed_arrival_time
        SQL;
      $this->oci->bindCols($sql, $data);
    }
}

?>