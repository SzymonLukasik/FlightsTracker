<?php

namespace FlightsTracker\Controller;

/** Registration page controller. */
class RegistrationController extends \FlightsTracker\Controller\BaseController {

    public function index() {
        $view = new \FlightsTracker\View\RegistrationView();
        $view->index();
    }

    public function tryRegister() {
        $data = $this->getRegistrationData();
        $model = new \FlightsTracker\Model\RegistrationModel();

        $success = $model->tryRegister($data);
        
        if ($success) {
            unset($_SESSION['err_registration_failed']); //TODO: czy sprawdzić czy isset?
            $this->redirect($this->generateUrl('homepage/index'));
        }
        else {
            $_SESSION['err_registration_failed'] = true;
            $this->redirect($this->generateUrl('registration/index'));
        }
    }

    private function getRegistrationData() {
        $registration_keys = [
            'username',
            'password',
            'first_name',
            'surname'
            // ,'birthdate'
        ];
        return $this->getPost($registration_keys);
    }

}