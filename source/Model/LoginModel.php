<?php

namespace FlightsTracker\Model;

include_once('BaseModel.php');

class LoginModel extends \FlightsTracker\Model\BaseModel {

    public function verifyCredentials(&$data, &$username) {
        $given_username = $data["username"];
        $sql = <<<SQL
            SELECT username, pword 
              FROM account 
             WHERE username = '$given_username'
        SQL;
    
        $this->oci->bindColsFlatten($sql, $credentials['username'], $credentials['password']);
        if ($credentials === $data) {
            $username = $credentials['username'];
            return true;
        }
        return false;
    }
}

?>