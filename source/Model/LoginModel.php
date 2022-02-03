<?php

namespace FlightsTracker\Model;

include_once('BaseModel.php');

class LoginModel extends \FlightsTracker\Model\BaseModel {

    public function verifyCredentials(&$data, &$username) {
        $sql = "SELECT username, pword 
                  FROM account 
                 WHERE username = '". $data['username'] . "'"
        ;
    
        $this->oci->bindColsFlatten($sql, $credentials['username'], $credentials['password']);
        if ($credentials === $data) {
            $username = $credentials['username'];
            return true;
        }
        else return false;
    }

}

?>