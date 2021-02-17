<?php


namespace ishop;


class Route
{
    
    protected static $routes = [];
    protected static $route  = [];
    
    
    public static function add($rexep, $route = [])
    {
        self::$routes[$rexep] = $route;
    }
    
    public static function getRoutes()
    {
        return self::$routes;
    }
    
    public static function getRoute()
    {
        return self::$route;
    }
    
    
    public static function dispatch($url)
    {
        
        if (self::matchRoute($url)) {
            
            $controller = "app\controllers\\" . self::$route["prefix"] . self::$route["controller"] . "Controller";
            
            if (class_exists($controller)) {
                $controllerObjext = new $controller(self::$route);
                $action           = self::lowerApperCase(self::$route['action']) . 'Action';
                
                if (method_exists($controllerObjext, $action)) {
                    $controllerObjext->$action();
                    $controllerObjext->getView();
                } else {
                    throw new \Exception("Метод $controller:$action не найден", 404);
                }
                
            } else {
                throw new \Exception("Класс $controller не найден", 404);
            }
            
        } else {
            throw new \Exception("Страница не найдена", 404);
        }
    }
    
    // Проверяем что есть route
    public static function matchRoute($url)
    {
        
        foreach (self::$routes as $pater => $route) {
            
            if (preg_match("~{$pater}~", $url, $matches)) {
                
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }
                
                if (empty($route["action"])) {
                    $route["action"] = 'index';
                }
                
                if ( ! isset($route["prefix"])) {
                    $route["prefix"] = '';
                } else {
                    $route["prefix"] .= "\\";
                }
                
                $route["controller"] = self::upperCamelCase($route["controller"]);
                self::$route         = $route;
                
                return true;
            }
            
        }
    }
    
    
    protected static function upperCamelCase($name)
    {
        $name = str_replace("-", ' ', $name);
        $name = ucwords($name);
        $name = str_replace(" ", '', $name);
        
        return $name;
    }
    
    
    protected static function lowerApperCase($name)
    {
        return lcfirst(self::upperCamelCase($name));
    }
    
}















