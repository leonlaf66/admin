<?php
namespace module\configurtion\widgets;

class RenderAppNewsBanner extends \yii\base\Widget
{
    public $entity = null;

    public function run()
    {
        return $this->render('render_app_news_banner.phtml', [
            'entity' => $this->entity
        ]);
    }
}