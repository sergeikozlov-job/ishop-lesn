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
        $hits = R::find("product", "status = '1' AND hit = '1' LIMIT 8");
        $this->set(compact('brands', 'hits'));
    }
    
}