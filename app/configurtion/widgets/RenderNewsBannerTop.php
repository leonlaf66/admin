<?php
namespace module\configurtion\widgets;

class RenderNewsBannerTop extends \yii\base\Widget
{
    public $entity = null;

    public function run()
    {
        return $this->render('render_news_banner_top.phtml', [
            'entity' => $this->entity
        ]);
    }
}