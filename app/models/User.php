<?php


namespace app\models;


class User extends AppModel
{
    
    public $attributes = [
        'login'    => '',
        'password' => '',
        'email'    => '',
        'name'     => '',
        'address'  => '',
    ];
    
    public $rules = [
        'required'  => [
            ['login'],
            ['password'],
            ['email'],
            ['name'],
            ['address'],
        ],
        'email'     => [
            ['email'],
        ],
        'lengthMin' => [
            ['password', 6],
        ],
    ];
    
    public function checkUnique()
    {
        $user = \RedBeanPHP\R::findOne('user', "login = ? OR email = ?", [$this->attributes['login'], $this->attributes['email']]);
        if ($user) {
            if ($user->login == $this->attributes['login']) {
                $this->errors['unique'][] = 'Этот логин уже занят';
            }
            if ($user->email == $this->attributes['email']) {
                $this->errors['unique'][] = 'Этот email уже занят';
            }
            return false;
        }
        return true;
    }
    
}