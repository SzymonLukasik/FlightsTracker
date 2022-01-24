<?php 

namespace FlightsTracker\View;

class FlightView extends \FlightsTracker\View\BaseView {

    public function index() {
        parent::renderTemplate("flight/index");
    }
}

?>