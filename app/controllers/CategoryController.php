<?php


namespace app\controllers;


use app\models\CategoryModel;

class CategoryController extends AppController
{
    
    public function viewAction()
    {
        $alias    = $this->route["alias"];
        $category = \RedBeanPHP\R::findOne('category', 'alias = ?', [$alias]);
        
        if ( ! $category) {
            throw new \Exception('Страница не найдена', 404);
        }
        
        $id_categories = CategoryModel::getIdsCategory($category->id);
        $id_categories = $id_categories ? $id_categories . $category->id : $category->id;
        
        $products = \RedBeanPHP\R::find('product', "category_id IN ($id_categories)");
        
        
        // Хлебные крошки
        $breadcrumbs = '';
        
        $this->setMeta($category->title, $category->desc, $category->keywords);
        $this->set(compact('products', 'breadcrumbs'));
    }
    
}