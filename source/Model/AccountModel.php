<?php

namespace FlightsTracker\Model;

include_once('BaseModel.php');

class AccountModel extends \FlightsTracker\Model\BaseModel {

    public function deleteAccount($username) {
        $this->oci->deleteRows('FlightAttendant', ['attendant_id' => $username]);
        $this->oci->deleteRows('Account', ['username' => $username]);
        unset($_SESSION['logged_user']);
    }
}

?>