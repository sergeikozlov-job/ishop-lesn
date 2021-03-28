<?php


namespace app\controllers;


use app\models\User;

class UserController extends AppController
{
    
    public function signupAction()
    {
        
        if ( ! empty($_POST)) {
            $data = $_POST;
            
            $user = new User();
            $user->load($data);
            if ($user->validate($data)) {
                $_SESSION['success'] = 'OK';
                redirect();
            } else {
                $_SESSION['errors'] = $user->getErrors();
                redirect();
            }
            
            
        }
        
        $this->setMeta('Регистрация');
    }
    
    public function loginAction()
    {
    
    }
    
    public function logoutAction()
    {
    
    }
    
}