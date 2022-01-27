<?php

namespace FlightsTracker\Controller;

/** Flights controller. */
class FlightController extends \FlightsTracker\Controller\BaseController {

    public function index() {
        $model = new \FlightsTracker\Model\FlightModel();
        $data = array();
        $model->getStatistics($data);
        $params = $this->getFilterParams();
        $model->getFlightData($params, $data);
        $view = new \FlightsTracker\View\FlightView($data);
        $view->index();
    }

    public function getFilterParams() {
        $param_keys = [
            'dep_country',
            'dep_city',
            'des_country',
            'des_city'
        ];
        return $this->getPost($param_keys);
    }

    public function show_flight() {
        echo '<h1> Showing flight with id: ' . $_GET['id'] . '</h1>';
    }

}