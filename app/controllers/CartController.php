<?php

namespace app\controllers;

use app\models\CartModel;
use app\models\OrderModel;
use app\models\User;
use RedBeanPHP\R;

class CartController extends AppController
{
    
    public function addAction()
    {
        $id     = ! empty($_GET['id']) ? (int) $_GET['id'] : null;
        $qty    = ! empty($_GET['qty']) ? (int) $_GET['qty'] : null;
        $mod_id = ! empty($_GET['mod']) ? (int) $_GET['mod'] : null;
        $mod    = null;
        
        if ($id) {
            $product = R::findOne('product', 'id = ?', [$id]);
            if ( ! $product) {
                echo 'error';
            }
            if ($mod_id) {
                $mod = R::findOne('modification', 'id = ? AND product_id = ?', [$mod_id, $id]);
            }
        }
        
        $cart = new CartModel();
        $cart->addToCart($product, $qty, $mod);
        if ($this->isAjax()) {
            $this->loadView('cart_modal');
        }
        redirect();
    }
    
    public function showAction()
    {
        $this->loadView('cart_modal');
    }
    
    public function deleteAction()
    {
        $id = ! empty($_GET['id']) ? $_GET['id'] : null;
        
        if (isset($_SESSION['cart'][$id])) {
            $cart = new CartModel();
            $cart->deleteCart($id);
        }
        
        if ($this->isAjax()) {
            $this->loadView('cart_modal');
        }
        redirect();
    }
    
    
    public function clearAction()
    {
        unset($_SESSION['cart.currency']);
        unset($_SESSION['cart.price']);
        unset($_SESSION['cart.qty']);
        unset($_SESSION['cart']);
        $this->loadView('cart_modal');
    }
    
    public static function cartReculc($curr)
    {
        if (isset($_SESSION['cart.currency'])) {
            
            // Пересчитываем корзину
            if ($_SESSION['cart.currency']['base']) {
                $_SESSION['cart.price'] *= $curr['value'];
            } else {
                $_SESSION['cart.price'] = $_SESSION['cart.price'] / $_SESSION['cart.currency']['value'] * $curr['value'];
            }
            
            // Пересчитываем сумму товаров
            foreach ($_SESSION['cart'] as $k => $v) {
                if ($_SESSION['cart.currency']['base']) {
                    $_SESSION['cart'][$k]['price'] *= $curr['value'];
                } else {
                    $_SESSION['cart'][$k]['price'] = $_SESSION['cart'][$k]['price'] / $_SESSION['cart.currency']['value'] * $curr['value'];
                }
            }
            
            // Заменяем валюту в сессии
            foreach ($curr as $k => $v) {
                $_SESSION['cart.currency'][$k] = $v;
            }
        }
    }
    
    public function viewAction()
    {
        $this->setMeta('Оформление заказа');
    }
    
    public function checkoutAction()
    {
        
        if ( ! empty($_POST)) {
            
            // регистрация пользователя
            if ( ! User::checkAuth()) {
                $data = $_POST;
                $user = new User();
                $user->load($data);
                if ($user->validate($data) && $user->checkUnique()) {
                    $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                    if ( ! $user_id = $user->save('user')) {
                        $_SESSION['errors'] = 'Ошибка';
                        redirect();
                    }
                } else {
                    $_SESSION['errors']    = $user->getErrors();
                    $_SESSION['form_data'] = $data;
                    redirect();
                }
            }
            
            // сохранение заказа
            $data['user_id'] = isset($user_id) ? $user_id : $_SESSION['user']['id'];
            $data['note']    = ! empty($_POST['note']) ? $_POST['note'] : '';
            $user_email      = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : $_POST['email'];
            
            $order_id = OrderModel::saveOrder($data);
            OrderModel::productOrder($order_id);
            OrderModel::mailOrder($order_id, $user_email);
        }
        
    }
    
}