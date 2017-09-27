<?php
namespace module\page\controllers;

use WS;

class SeoController extends \module\core\component\Controller
{
    public function actionIndex()
    {
        $this->view->setActiveMenuId('seo');

        $data = include(APP_ROOT.'/../houses/config/metas.php');
        $configs = include(APP_ROOT.'/../houses/config/metas.configs.php');

        return $this->render('index.phtml', [
          'data' => \yii\helpers\ArrayHelper::merge($configs, $data)
        ]);
    }

    public function actionSave()
    {
      $items = WS::$app->request->post('data');
      if (! $items) $items = [];

      foreach ($items as $routeId => $item) {
        unset($items[$routeId]['name']);
        unset($items[$routeId]['variables']);
      }

      $php = "<?php\nreturn ".var_export($items, true).';?>';
      file_put_contents(APP_ROOT.'/../houses/config/metas.php', $php);
    }
}