<?php


namespace app\models;


class Product extends AppModel
{
    
    public function setRecentlyView($id)
    {
        $recently_view = $this->allRecentlyView();
        if ( ! $recently_view) {
            setcookie('recently_product', $id, time() + 3600, '/');
        } else {
            $recently_product = explode('.', $recently_view);
            if ( ! in_array($id, $recently_product)) {
                $recently_product[] = $id;
                $recently_product   = implode('.', $recently_product);
                setcookie('recently_product', $recently_product, time() + 3600, '/');
            }
        }
    }
    
    public function getRecentlyView()
    {
        if ( ! empty($_COOKIE["recently_product"])) {
            $recently_product = explode('.', $_COOKIE["recently_product"]);
            return array_slice($recently_product, -3);
        }
        
        return false;
    }
    
    public function allRecentlyView()
    {
        
        if ( ! empty($_COOKIE["recently_product"])) {
            return $_COOKIE["recently_product"];
        }
        
        return false;
    }
    
}