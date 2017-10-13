<?php
namespace module\configurtion\widgets;

class RenderHomeLuxuryHouse extends \yii\base\Widget
{
    public $entity = null;

    public function run()
    {  
        return $this->render('render_home-luxury-house.phtml', [
            'entity' => $this->entity
        ]);
    }
}