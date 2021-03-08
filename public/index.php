<?php

use ishop\App;
use ishop\Route;

require_once dirname(__DIR__) . '/config/init.php';
require_once LIBS . '/functions.php';
require_once CONFIG . '/routes.php';

new App();


//debug(Route::getRoute());