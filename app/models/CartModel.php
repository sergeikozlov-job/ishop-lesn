<?php


namespace app\models;


use ishop\App;

class CartModel extends AppModel
{
    
    public function addToCart($product, $qty = 1, $mod = null)
    {
        // Получаем активную валюту
        if ( ! isset($_SESSION['cart.currency'])) {
            $_SESSION['cart.currency'] = App::$app->getProperty('currency');
        }
        
        if ($mod) {
            $ID    = "{$product->id} - {$mod->id}";
            $title = "{$product->title} ({$mod->title})";
            $price = $mod->price;
        } else {
            $ID    = $product->id;
            $title = $product->title;
            $price = $product->price;
        }
        
        // Запись в сесию
        if (isset($_SESSION['cart'][$ID])) {
            $_SESSION['cart'][$ID]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$ID] = [
                'title' => $title,
                'img'   => $product->img,
                'price' => $price * $_SESSION['cart.currency']['value'],
                'qty'   => $qty,
                'alias' => $product->alias,
            ];
        }
        
        $_SESSION['cart.qty']   = isset($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;
        $_SESSION['cart.price'] = isset($_SESSION['cart.price']) ? $_SESSION['cart.price'] + $price * $qty * $_SESSION['cart.currency']['value'] : $price * $qty * $_SESSION['cart.currency']['value'];
    }
    
    public function deleteCart($id) {
    
        $_SESSION['cart.qty'] -=  $_SESSION['cart'][$id]['qty'];
        $_SESSION['cart.price'] -=  $_SESSION['cart'][$id]['price'] * $_SESSION['cart'][$id]['qty'];
        unset($_SESSION['cart'][$id]);
    }
    
    
}