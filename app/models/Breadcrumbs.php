<?php


namespace app\models;


use ishop\App;

class Breadcrumbs
{
    
    public static function getBreadcrumbs($category_id, $name = '')
    {
        // Получаем все котегории
        $category = App::$app->getProperty('category');
        
        // Получаем массив категорий до товара
        $breadcrumbs_array = self::getParts($category, $category_id);
        
        // Формируем ссылки
        $breadcrumbs = '<li><a href="' . PATH . '">Home</a></li>';
        if ($breadcrumbs_array) {
            foreach ($breadcrumbs_array as $alias => $title) {
                $breadcrumbs .= '<li><a href="' . PATH . '/category/' . $alias . '">' . $title . '</a></li>';
            }
        }
        
        if ($name) {
            $breadcrumbs .= "<li class='active'>{$name}</li>";
        }
        
        return $breadcrumbs;
    }
    
    public static function getParts($category, $id)
    {
        if ( ! $id) return false;
        $breadcrumbs = [];
        
        // Перебираем все категории, ищем нужную
        foreach ($category as $k => $v) {
            if (isset($category[$id])) {
                $breadcrumbs[$category[$id]["alias"]] = $category[$id]["title"];
                $id                                   = $category[$id]["parent_id"];
            } else break;
        }
        return array_reverse($breadcrumbs, true);
    }
    
}