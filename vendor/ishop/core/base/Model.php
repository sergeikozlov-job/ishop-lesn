<?php


namespace ishop\base;


use ishop\Db;

abstract class Model
{
    
    public $attributes = [];
    public $rules = [];
    public $errors = [];
    
    public function __construct()
    {
        Db::instance();
    }
}