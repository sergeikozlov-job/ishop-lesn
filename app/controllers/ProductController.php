<?php


namespace app\controllers;


use app\models\Product;

class ProductController extends AppController
{
    
    public function viewAction()
    {
        
        $alias   = $this->route["alias"];
        $product = \RedBeanPHP\R::findOne("product", "alias = ? AND status = '1'", [$alias]);
        
        if ( ! $product) {
            throw new \Exception("Страница не найдена", 404);
        }
        
        
        // хлебные крошки
        
        
        // связанные товары
        $related = \RedBeanPHP\R::getAll("SELECT * FROM related_product JOIN product ON product.id = related_product.related_id WHERE related_product.product_id = $product->id");
        
        
        // запись в куки запрошенного товара
        $pruduct_view = new Product();
        $pruduct_view->setRecentlyView($product->id);
        
        // просмотренные товыры
        $recently_product = $pruduct_view->getRecentlyView();
        $recently_view    = null;
        if ($recently_product) {
            $recently_view = \RedBeanPHP\R::find('product', 'id IN (' . \RedBeanPHP\R::genSlots($recently_product) . ')', $recently_product);
        }
        
        // модификации
        
        
        // галлерея
        $gallery = \RedBeanPHP\R::findAll('gallery', "product_id = ?", [$product->id]);
        
        
        $this->set(compact('product', 'related', 'gallery', 'recently_view'));
        $this->setMeta($product->title, $product->description, $product->keywords);
    }
    
}