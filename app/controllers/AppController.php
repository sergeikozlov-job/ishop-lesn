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
        
        $currencies = Currency::getCurrencies();
        App::$app->setProperty('currencies', $currencies);
        App::$app->setProperty('currency', Currency::getCurrency($currencies));
    
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