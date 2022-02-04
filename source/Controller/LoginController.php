<?php

namespace FlightsTracker\Controller;

/** Login page controller. */
class LoginController extends \FlightsTracker\Controller\BaseController {


    public function index() {
        if (isset($_SESSION['login_failed']))
            if ($_SESSION['login_failed'] > 0)
                unset($_SESSION['login_failed']);
            else
                $_SESSION['login_failed']++;

        $view = new \FlightsTracker\View\LoginView();
        $view->index();
    }

    public function tryLogin() {
        $data = $this->getCredentials();
        $model = new \FlightsTracker\Model\LoginModel();
        $success = $model->verifyCredentials($data, $_SESSION['logged_user']);

        if ($success) {
            $this->redirect($this->generateUrl('homepage/index'));
        } else {
            $_SESSION['login_failed'] = 0;
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