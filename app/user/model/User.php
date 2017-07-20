<?php
namespace module\user\model;

class User extends \yii\db\ActiveRecord {
 
    public static function tableName()
    {
        return 'admin_user';
    }
}