<?php
    namespace FlightsTracker\Controller;

    class HomepageController extends \FlightsTracker\Controller\BaseController {
        public function index() {
            $view = new \FlightsTracker\View\HomepageView();
            $view->index();
        }
    }
?>