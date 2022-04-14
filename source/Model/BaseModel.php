<?php

namespace FlightsTracker\Model;

abstract class BaseModel {

    protected $oci;

    public function __construct() {
        try {
            $this->oci = new \FlightsTracker\Utils\OCI();
        } catch (DBException $e) {
            echo 'The connect can not create: ' . $e->getMessage();
        }
    }

}