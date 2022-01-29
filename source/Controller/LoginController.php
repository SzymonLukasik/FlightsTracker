<?php

namespace FlightsTracker\Controller;

/** Login page controller. */
class LoginController extends \FlightsTracker\Controller\BaseController {

    static bool $login_failed = false;

    public function index() {
        $view = new \FlightsTracker\View\LoginView();
        $view->index();
        \FlightsTracker\Controller\LoginController::$login_failed = false;
    }

    public function tryLogin() {
        $data = $this->getCredentials();
        $model = new \FlightsTracker\Model\LoginModel();
        $_SESSION['user_loggedin'] = $model->verifyCredentials($data);

        if ($_SESSION['user_loggedin'])
            $this->redirect($this->generateUrl('homepage/index'));
        else {
            \FlightsTracker\Controller\LoginController::$login_failed = true;
            $this->redirect($this->generateUrl('login/index'));
        }
    }

    public function logout() {
        $_SESSION['user_loggedin'] = false;
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