<?php
namespace module\core\component;

class View extends \yii\web\View
{
    public $activeMenuId = null;

    public function createUrl($params)
    {
        return \WS::$app->urlManager->createUrl($params);
    }

    public function setActiveMenuId($menuId)
    {
        $this->activeMenuId = $menuId;
        return $this;
    }

    public function activeMenuItem($menuId, $activeClassName = 'active') 
    {
        echo $this->activeMenuId === $menuId ? $activeClassName : '';
    }
}