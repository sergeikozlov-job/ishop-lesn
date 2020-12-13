<?php

use ishop\Route;




//default routes
Route::add("^admin$", ["controller" => "main", "action" => "index", "prefix"=>"admin"]);
Route::add("^admin/(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$",  ["prefix"=>"admin"]);

Route::add("^$", ["controller" => "main", "action" => "index"]);
Route::add("^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$");