<?php


namespace app\models;


use ishop\App;

class CategoryModel extends AppModel
{
    
    public static function getIdsCategory($id) {
    
        $categories = App::$app->getProperty('category');
        $ids = null;
        
        foreach ($categories as $key => $category) {
            if($category['parent_id'] == $id) {
                $ids .= $key . ',';
                $ids .= self::getIdsCategory($key);
            }
        }
       return $ids;
    }
    
}