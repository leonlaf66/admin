<?php
namespace module\page\controllers;

use WS;

class SeoController extends \module\core\component\Controller
{
    public function actionIndex($path = null)
    {
        if ($path && WS::$app->request->isPost) {
            $data = WS::$app->request->post('data');

            $seoMeta = \models\SiteSeoMeta::findOne($path);
            if (!$seoMeta) {
                $seoMeta = new \models\SiteSeoMeta();
                $seoMeta->path = $path;
            }
            $seoMeta->setAttributes($data, false);
            $seoMeta->save();

            echo json_encode($seoMeta->getErrors());

            exit;
        }

        $configs = include(APP_ROOT.'/../houses/config/metas.configs.php');
        $entity = \models\SiteSeoMeta::findOneAsArray($path);

        $this->view->setActiveMenuId('seo');

        return $this->render('index.phtml', [
            'currentPath' => $path,
            'configs' => $configs,
            'entity' => $entity
        ]);
    }
}