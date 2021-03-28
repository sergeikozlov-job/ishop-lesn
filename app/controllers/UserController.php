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
            if ($user->validate($data) && $user->checkUnique()) {
                $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                if ($user->save('user')) {
                    $_SESSION['success'] = 'Спасибо за регистрацию';
                } else {
                    $_SESSION['errors'] = 'Ошибка';
                }
            } else {
                $_SESSION['errors'] = $user->getErrors();
            }
            redirect();
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