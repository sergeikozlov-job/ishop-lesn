<?php


namespace app\models;


use ishop\App;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class OrderModel extends AppModel
{
    
    public static function saveOrder($data)
    {
        $order           = \RedBeanPHP\R::dispense('order');
        $order->user_id  = $data['user_id'];
        $order->note     = $data['note'];
        $order->currency = $_SESSION['cart.currency']['code'];
        $order_id        = \RedBeanPHP\R::store($order);
        self::productOrder($order_id);
        return $order_id;
    }
    
    public static function productOrder($order_id)
    {
        $sql_part = '';
        foreach ($_SESSION['cart'] as $product_id => $product) {
            $product_id = (int) $product_id;
            $sql_part   .= "($order_id, $product_id, {$product['qty']}, '{$product['title']}', {$product['price']}),";
        }
        $sql_part = rtrim($sql_part, ',');
        \RedBeanPHP\R::exec("INSERT INTO order_product (order_id, product_id, qty, title, price) VALUES $sql_part");
    }
    
    
    public static function mailOrder($order_id, $user_email)
    {
        // Create the Transport
        $transport = (new Swift_SmtpTransport(App::$app->getProperty('smpt_host'), App::$app->getProperty('smpt_port'), 'ssl'))
            ->setUsername(App::$app->getProperty('smpt_login'))
            ->setPassword(App::$app->getProperty('smpt_password'));
        
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);
        
        ob_start();
        require_once APP . '/views/mail/mail_order.php';
        $mail_body = ob_get_clean();
        
        // Create a message
        $message = (new Swift_Message("Заказ №{$order_id}"))
            ->setFrom([App::$app->getProperty('admin_email') => App::$app->getProperty('name_ishop')])
            ->setTo([$user_email, App::$app->getProperty('admin_email')])
            ->setBody($mail_body, 'text/html');
        
        // Send the message
        $result = $mailer->send($message);
        
        
        unset($_SESSION['cart']);
        unset($_SESSION['cart.qty']);
        unset($_SESSION['cart.price']);
        unset($_SESSION['cart.currency']);
        $_SESSION['success'] = 'Спасибо за заказ';
    }
    
}