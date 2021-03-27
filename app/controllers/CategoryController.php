<?php


namespace app\controllers;


use app\models\Breadcrumbs;
use app\models\CategoryModel;
use ishop\App;
use ishop\libs\Pagination;

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
        

        // Пагинация
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perpage = App::$app->getProperty('pagination');
        $total = \RedBeanPHP\R::count('product', "category_id IN ($id_categories)");
        $pagination = new Pagination($page, $perpage, $total);
        $start = $pagination->getStart();
    
    
        $products = \RedBeanPHP\R::find('product', "category_id IN ($id_categories) LIMIT $start, $perpage");
        
        // Хлебные крошки
        $breadcrumbs = Breadcrumbs::getBreadcrumbs($category->id);
        
        $this->setMeta($category->title, $category->desc, $category->keywords);
        $this->set(compact('products', 'breadcrumbs', 'pagination'));
    }
    
}