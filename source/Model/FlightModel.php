<?php

namespace FlightsTracker\Model;

include_once('BaseModel.php');

class FlightModel extends \FlightsTracker\Model\BaseModel {
    
    public function getIndexData() {
        $nrows = $this->oci->fetchAll("SELECT COUNT(*) FROM Flight;", $arr);
        var_dump($arr);
    }
}

?>