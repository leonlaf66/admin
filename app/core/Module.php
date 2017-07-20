<?php
namespace module\core;

class Module extends \yii\base\Module
{
    public $urlRules = [];

    public function init()
    {
        \WS::$app->getUrlManager()->addRules($this->urlRules);
        
        return parent::init();
    }
}