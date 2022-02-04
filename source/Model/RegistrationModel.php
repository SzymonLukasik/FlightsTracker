<?php

namespace FlightsTracker\Model;

include_once('BaseModel.php');

class RegistrationModel extends \FlightsTracker\Model\BaseModel {

    public function tryRegister($data, $date_fields) {

        if (!$this->isUsernameFree($data['username'])) 
            return false;
        $this->oci->insertRow('Account', $data, $date_fields);
        return true;
    }

    private function isUsernameFree($username) {
        $sql = <<<SQL
            SELECT username 
              FROM Account 
             WHERE username = '$username'
        SQL;
        
        $sql_count = <<<SQL
            SELECT COUNT(*) FROM ($sql) 
        SQL;

        $this->oci->bindColsFlatten($sql_count, $fetched_accounts);
        return $fetched_accounts == 0;
    }
   
}

?>