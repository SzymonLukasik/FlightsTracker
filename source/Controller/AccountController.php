<?php

namespace FlightsTracker\Controller;

/** UserFlight controller. */
class AccountController extends \FlightsTracker\Controller\BaseController {
    
    public function showAccount() {
        $view = new \FlightsTracker\View\AccountView();
        $view->show_account();
    }

    public function deleteAccount() {
        $model = new \FlightsTracker\Model\AccountModel();
        $model->deleteAccount($_SESSION['logged_user']);
        $this->redirect($this->generateUrl('homepage/index'));
    }

}