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
    
}