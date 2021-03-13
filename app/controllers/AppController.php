<?php


namespace app\controllers;


use app\models\AppModel;
use app\widgets\currency\Currency;
use ishop\App;
use ishop\base\Controller;
use ishop\Cache;

class AppController extends Controller
{
    public function __construct($route)
    {
        parent::__construct($route);
        new AppModel();
        
        App::$app->setProperty('currencies', Currency::getCurrencies());
        App::$app->setProperty('currency', Currency::getCurrency(Currency::getCurrencies()));
    
        App::$app->setProperty('category', self::cacheCategory());
    }
    
    
    public static function cacheCategory()
    {
        $cache      = Cache::instance();
        $categories = $cache->get('category');
        
        if ( ! $categories) {
            $category = \RedBeanPHP\R::getAssoc("SELECT * FROM category");
            $cache->set('category', $category);
        }
        
        return $categories;
    }
    
}