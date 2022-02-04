<?php

namespace FlightsTracker\Controller;

/** Flights controller. */
class FlightController extends \FlightsTracker\Controller\BaseController {

    public function index() {
        $model = new \FlightsTracker\Model\FlightModel();
        $data = array();
        $model->getStatistics($data);
        $params = $this->getParams();
        $model->getFlightData($params, $data);
        $data['pages'] = $this->getPages($params, $data);

        if (isset($_SESSION['logged_user']))
            $this->getTrackedFlightsIds($data);

        $view = new \FlightsTracker\View\FlightView($data);
        $_SESSION['flight_form_post_data'] = $_POST;
        $view->index();
    }

    private function getParams() {
        $filter_keys = [
            'dep_country',
            'dep_city',
            'des_country',
            'des_city',
            'departure_after'
        ];

        $params['filter_config'] = $this->getPost($filter_keys);
        $params['page'] = $this->getPost('page', 1);
        $params['stride'] = 10; // User can't specify it.
        return $params;
    }

    private function getPages($params, $data) {
        $n_flights = $data['n_filtered_flights'];
        if ($n_flights == 0)
            return;
        $pages = $data['pages'] = array();
        $pages['first'] = 1;
        $pages['last'] = $last = ceil($n_flights / $params['stride']);
        $pages['current'] = $current = $params['page'];
        $pages['prev'] = $current > 1 ? $current - 1 : 1;
        $pages['next'] = $current < $last ? $current + 1 : $last;
        $pages['one'] = (1 == $last);
        return $pages;
    }

    private function getTrackedFlightsIds(&$data) {
        $model = new \FlightsTracker\Model\TrackerModel();
        $model->getTrackedFlightsIds($_SESSION['logged_user'], $data['tracked_flights']);
    }

    public function showFlight() {
        $view = new \FlightsTracker\View\FlightView($data);
        $view->show();
    }

}