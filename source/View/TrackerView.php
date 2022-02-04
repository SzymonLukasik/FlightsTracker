<?php 

namespace FlightsTracker\View;

class TrackerView extends \FlightsTracker\View\BaseView {

    public function __construct($data = null) {
        parent::__construct($data);
    }

    public function show_account() {
        $this->renderTemplate("account/show");
    }

    public function index() {
        $this->renderTemplate("tracker/index");
    }

    public function loginPrompt() {
        $this->renderTemplate("tracker/login_prompt");
    }
}

?>