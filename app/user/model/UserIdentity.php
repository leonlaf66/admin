<?php
namespace module\user\model;

class UserIdentity extends \yii\base\Object implements \yii\web\IdentityInterface 
{
 
    public $id;
    public $username;
    public $email;
    public $password;
    public $status;
    public $auth_key;
    public $access_token;
    //public $created_at;
    //public $updated_at;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        $user = self::findById($id);
        if ($user) {
            return new static($user);
        }
        return null;
    }
 
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        $user = User::find()->where(array('accessToken' => $token))->one();
        if ($user) {
            return new static($user);
        }
        return null;
    }
 
    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {
        $user = User::find()->where(array('username' => $username))->one();
        if ($user) {
            return new static($user);
        }
 
        return null;
    }
 
    public static function findById($id) {
        $user = User::find()->where(array('id' => $id))->asArray()->one();
        if ($user) {
            return new static($user);
        }
 
        return null;
    }
    
    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }
 
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->auth_key === $authKey;
    }
 
    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->password === md5($password);
        //return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }
 
}