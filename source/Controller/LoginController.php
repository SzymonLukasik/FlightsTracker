<?php

namespace FlightsTracker\Controller;

/** Account/login page controller. */
class LoginController extends \FlightsTracker\Controller\BaseController {

    public function index() {
        $view = new \FlightsTracker\View\LoginView();
        $view->index();
    }

    public function tryLogin() {
        $data = getCredentials();
        $model = new \FlightsTracker\Model\LoginModel();
        $_SESSION['userloggedin'] = $model->verifyCredentials($data);

        if ($_SESSION['userloggedin'])
            redirect(generateUrl('homepage'));
        else 
            redirect(generateUrl('login/index'));
    }

    public function logout() {
        
    }

    private function getCredentials() {
        $credential_keys = [
            'username',
            'password'
        ];
        return $this->getPost($credential_keys);
    }

}