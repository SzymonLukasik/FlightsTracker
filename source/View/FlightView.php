<?php 

namespace FlightsTracker\View;

class FlightView extends \FlightsTracker\View\BaseView {

    public function __construct($data = null) {
        parent::__construct($data);
    }

    public function index() {
        $autocompletes_params = [
            ["dep_country", "country", "aof_dep"],
            ["dep_city", "city", "aof_dep"],
            ["des_country", "country", "aof_des"],
            ["des_city", "city", "aof_des"]
        ];

        $renderHeadScript = function() use ($autocompletes_params) {
            $this->renderFile(SCRIPTS_PATH . 'flight_autocomplete/libs');
            foreach ($autocompletes_params as $params) {
                $this->renderAutocompleteScript(...$params);
            }
        };

        $this->renderTemplate('flight/index', $renderHeadScript);
    }

    public function show() {
        $this->renderTemplate('flight/show');
    }

    private function renderAutocompleteScript($item, $field, $airport) {
        require SCRIPTS_PATH . 'flight_autocomplete/script.php';
    }
}

?>