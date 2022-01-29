<?php

namespace FlightsTracker\Model;

include_once('BaseModel.php');

class LoginModel extends \FlightsTracker\Model\BaseModel {

    public function verifyCredentials(&$data) {
        $sql = "SELECT username, pword 
                  FROM account 
                 WHERE username = '". $data['username'] . "'"
        ;
    
        $this->oci->bindColsFlatten($sql, $credentials['username'], $credentials['password']);

        return $credentials === $data;
    }

}

?>