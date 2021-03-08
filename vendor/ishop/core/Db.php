<?php


namespace ishop;

use http\Exception;
use \RedBeanPHP\R as R;

class Db
{
    use TSingletone;
    
    protected function __construct() {
        $db = require_once CONFIG . '/config_db.php';
        R::setup($db['dsn'], $db['user'], $db['pass']);
        
        if(!R::testConnection()) {
           throw new \Exception('Нет соединения с БД', 500);
        }
        
        R::freeze();
        
        if(DEBUG) {
            R::debug( TRUE, 1 );
        }
    }
    
}