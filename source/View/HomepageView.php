<?php 

namespace FlightsTracker\View;

class HomepageView extends \FlightsTracker\View\BaseView {

    public function index() {
        parent::renderTemplate("index");
    }
}

?>