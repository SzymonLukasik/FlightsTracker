<?php 

namespace FlightsTracker\View;

class ForumView extends \FlightsTracker\View\BaseView {

    public function __construct($data = null) {
        parent::__construct($data);
    }

    public function index() {
        $this->renderTemplate("forum/index");
    }
}

?>