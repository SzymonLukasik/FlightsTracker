<?php

namespace FlightsTracker\Controller;

/** Flights controller. */
class FlightController extends \FlightsTracker\Controller\BaseController {

    public function index() {
        $model = new \FlightsTracker\Model\FlightModel();
        $data = $model->getIndexData();
        $view = new \FlightsTracker\View\FlightView();
        //$view->articles = $articles;
        $view->index();
    }

}