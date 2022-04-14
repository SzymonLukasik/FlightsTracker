<?php 

namespace FlightsTracker\View;

class AccountView extends \FlightsTracker\View\BaseView {

    public function __construct($data = null) {
        parent::__construct($data);
    }

    public function show_account() {
        $this->renderTemplate("account/show");
    }
}

?>