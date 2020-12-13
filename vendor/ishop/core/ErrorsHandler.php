<?php


namespace ishop;


class ErrorsHandler
{
    
    public function __construct()
    {
        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }
        
        set_exception_handler([$this, "exceptionHandler"]);
    }
    
    public function exceptionHandler($e)
    {
        $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine());
        $this->dispalyErrors("Исключение", $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }
    
    public function logErrors($message = '', $file = '', $line = '')
    {
        error_log("Дата:" . date("Y-m-d H-i-s") . "| Текс ошибки: {$message} | Файл: {$file} | Строка: {$line} \n=======================\n",
            3, ROOT . '/tmp/errors-log.txt');
    }
    
    
    public function dispalyErrors($errno, $errstr, $errfile, $errline, $responce = 404)
    {
        if ($responce == 404 && ! DEBUG) {
            require_once WWW . '/errors/404.php';
            die;
        }
        
        if (DEBUG) {
            require_once WWW . '/errors/dev.php';
        } else {
            require_once WWW . '/errors/prod.php';
        }
    }
    
}