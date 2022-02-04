<?php

namespace FlightsTracker\Controller;

/** UserFlight controller. */
class TrackerController extends \FlightsTracker\Controller\BaseController {

    private function addFlight() {
        $model = new \FlightsTracker\Model\TrackerModel();
        $data = $this->getUserFlightData();
        $model->addFlight($data);
    }

    public function trackerAddFlight() {
        $this->addFlight();
        $this->redirect($this->generateURL('tracker/index'));
    }

    public function flightAddFlight() {
        $this->addFlight();
        $this->redirect($this->generateURL('flight/index'));
    }

    private function deleteFlight() {
        $model = new \FlightsTracker\Model\TrackerModel();
        $model->deleteFlight($_SESSION['logged_user'], $_GET['id']);
    }
    public function trackerDeleteFlight() {
        $this->deleteFlight();
        $this->redirect($this->generateURL('tracker/index'));
    }

    public function flightDeleteFlight() {
        $this->deleteFlight();
        $this->redirect($this->generateURL('flight/index'));
    }

    private function getUserFlightData() {
        $data = array();
        $data['id'] = $_GET['id'];
        $data['username'] = $_SESSION['logged_user'];
        return $data;
    }

    public function index() {
        $view = new \FlightsTracker\View\TrackerView();
        if (!isset($_SESSION['logged_user'])) {
            $view->loginPrompt();
            return;
        }   
        $model = new \FlightsTracker\Model\TrackerModel();
        $model->getTrackedFlights($_SESSION['logged_user'], $data);
        $this->getTrackedFlightsIds($data);
        $view = new \FlightsTracker\View\TrackerView($data);
        $view->index();
    }

    private function getTrackedFlightsIds(&$data) {
        $model = new \FlightsTracker\Model\TrackerModel();
        $model->getTrackedFlightsIds($_SESSION['logged_user'], $data['tracked_flights']);
    }

}