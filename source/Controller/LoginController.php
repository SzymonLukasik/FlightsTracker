<?php

namespace FlightsTracker\Controller;

/** Login page controller. */
class LoginController extends \FlightsTracker\Controller\BaseController {

    static bool $login_failed = false; //TODO: niech to działa

    // public function __construct() {
    //     parent::__construct();
    //     \FlightsTracker\Controller\LoginController::$login_failed = false;
    // }

    public function index() {
        $view = new \FlightsTracker\View\LoginView();
        \FlightsTracker\Controller\LoginController::$login_failed = false;
        $view->index();
    }

    public function tryLogin() {
        $data = $this->getCredentials();
        $model = new \FlightsTracker\Model\LoginModel();
        $success = $model->verifyCredentials($data, $_SESSION['logged_user']);

        if ($success)
            $this->redirect($this->generateUrl('homepage/index'));
        else {
            \FlightsTracker\Controller\LoginController::$login_failed = true;
            $this->redirect($this->generateUrl('login/index'));
        }
    }

    public function logout() {
        unset($_SESSION['logged_user']);
        $this->redirect($this->generateUrl('homepage/index'));
    }

    private function getCredentials() {
        $credential_keys = [
            'username',
            'password'
        ];
        return $this->getPost($credential_keys);
    }

}