<?php

namespace app\controllers;

use ishop\App;
use ishop\Cache;
use \RedBeanPHP\R as R;

class MainController extends AppController
{
    
    public function indexAction()
    {
        $this->setMeta(App::$app->getProperty('name_ishop'), 'Описание...', 'Ключевики');
        
        $posts = R::findAll('test');
        
        $name = 'Jhon';
        $age  = 30;
        
        
        $names = ['Andrey', 'Jane', 'Mike'];
        $cache = new Cache();
//        $cache->set('test', $names);
//        $cache->delete('test');
        
        
        $this->set(compact('name', 'age', 'posts'));
    }
    
}