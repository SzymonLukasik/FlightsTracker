<?php

namespace FlightsTracker\Controller;

/** Forum controller. */
class ForumController extends \FlightsTracker\Controller\BaseController {

    public function index() {
        $model = new \FlightsTracker\Model\ForumModel();
        $data = array();
        $airline = $this->getAirline();
        $model->getAirlineData($airline, $data); //TODO: dorobić autocompletion
        $view = new \FlightsTracker\View\ForumView($data);
        $view->index();
    }

    public function getAirline() {
        $arr = [
            'airline'
        ];
        $tmp = $this->getPost($arr);
        return $tmp['airline'];
    }

}