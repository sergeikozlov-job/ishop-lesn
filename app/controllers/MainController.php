<?php

namespace app\controllers;

use ishop\App;
use \RedBeanPHP\R as R;

class MainController extends AppController
{
 
    public function indexAction() {
          $this->setMeta(App::$app->getProperty('name_ishop'), 'Описание...', 'Ключевики');

          $posts = R::findAll('test');
          
          $name = 'Jhon';
          $age = 30;
          
          $this->set(compact('name', 'age', 'posts'));
    }
    
}