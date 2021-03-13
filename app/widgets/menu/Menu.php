<?php


namespace app\widgets\menu;


use ishop\App;
use ishop\Cache;

class Menu
{
    protected $tpl;
    protected $data;
    protected $tree;
    protected $menuHtml;
    protected $container = 'ul';
    protected $class     = 'menu-list';
    protected $table     = 'category';
    protected $cache     = 3600;
    protected $cache_key = 'menu';
    protected $attrs     = [];
    // Произвольная строка
    protected $prepend = '';
    
    
    public function __construct($options = [])
    {
        $this->tpl = __DIR__ . '/menu_tpl/menu.php';
        $this->getOptions($options);
        $this->run();
    }
    
    protected function getOptions($options)
    {
        foreach ($options as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }
    
    protected function run()
    {
        $cache          = Cache::instance();
        $this->menuHtml = $cache->get($this->cache_key);
        if ( ! $this->menuHtml) {
            $this->data = App::$app->getProperty('category');
            if ( ! $this->data) {
                $this->data = $cats = \RedBeanPHP\R::getAssoc("SELECT * FROM {$this->table}");
            }
            $this->tree     = $this->getTree();
            $this->menuHtml = $this->getMenuHtml($this->tree);
            if ($this->cache) {
                $cache->set($this->cache_key, $this->menuHtml, $this->cache);
            }
        }
        $this->output();
    }
    
    protected function output()
    {
        $atrrs = '';
        
        if ( ! empty($this->attrs)) {
            foreach ($this->attrs as $k => $v) {
                $atrrs = "{$k} = '$v'";
            }
        }
        
        echo "<{$this->container} class='{$this->class}' $atrrs>";
        echo $this->prepend;
        echo $this->menuHtml;
        echo "</{$this->container}>";
    }
    
    
    protected function getTree()
    {
        $tree = [];
        $data = $this->data;
        foreach ($data as $id => &$node) {
            if ( ! $node['parent_id']) {
                $tree[$id] = &$node;
            } else {
                $data[$node['parent_id']]['childs'][$id] = &$node;
            }
        }
        return $tree;
    }
    
    
    protected function getMenuHtml($tree, $tab = '')
    {
        $str = '';
        foreach ($tree as $id => $category) {
            $str .= $this->catToTemplate($category, $tab, $id);
        }
        return $str;
    }
    
    
    protected function catToTemplate($category, $tab, $id)
    {
        ob_start();
        require $this->tpl;
        return ob_get_clean();
    }
    
}