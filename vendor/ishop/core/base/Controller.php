<?php


namespace ishop\base;


class Controller
{
    public $route;
    public $controller;
    public $view;
    public $prefix;
    public $model;
    public $layouts;
    public $data = [];
    public $meta = [];
    
    
    public function __construct($route)
    {
        $this->route      = $route;
        $this->controller = $route['controller'];
        $this->model      = $route['controller'];
        $this->view       = $route['action'];
        $this->prefix     = $route['prefix'];
    }
    
    public function set($data)
    {
        $this->data = $data;
    }
    
    public function getView()
    {
        $viewObject = new View($this->route, $this->layouts, $this->view, $this->meta);
        $viewObject->render($this->data);
    }
    
    public function setMeta($title = '', $desc = '', $keywords = '')
    {
        $this->meta['title']    = $title;
        $this->meta['desc']     = $desc;
        $this->meta['keywords'] = $keywords;
    }
    
    public function isAjax() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && (strtolower(getenv('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest'));
    }
    
    public function loadView($view, $vars = []) {
        extract($vars);
        require_once APP . "/views/{$this->prefix}/{$this->controller}/{$view}.php";
        die;
    }
    
    
}