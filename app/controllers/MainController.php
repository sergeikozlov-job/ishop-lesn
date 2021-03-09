<?php

namespace app\controllers;

use ishop\App;
use \RedBeanPHP\R as R;

class MainController extends AppController
{
    
    public function indexAction()
    {
        $this->setMeta(App::$app->getProperty('name_ishop'), 'Описание...', 'Ключевики');
        $brands = R::find("brand", "LIMIT 3");
        $this->set(compact('brands'));
    }
    
}