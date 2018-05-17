<?php
namespace module\page\controllers;

use WS;

class SeoController extends \module\core\component\Controller
{
    public function actionIndex($path = null, $area)
    {
        if ($path && WS::$app->request->isPost) {
            $data = WS::$app->request->post('data');

            $seoMeta = \models\SiteSeoMeta::find()->where(['path' => $path, 'area_id' => $area])->one();
            if (!$seoMeta) {
                $seoMeta = new \models\SiteSeoMeta();
                $seoMeta->area_id = $area;
                $seoMeta->path = $path;
            }
            foreach ($data as $idx => $field) {
                if (is_array($field) && count($field) === 2) {
                    $data[$idx] = array_map(function ($val) {
                        return empty($val) ? '.' : $val;
                    }, $field);
                }
            }

            $seoMeta->setAttributes($data, false);
            $seoMeta->save();

            echo json_encode($seoMeta->getErrors());

            exit;
        }

        $configs = include(APP_ROOT.'/../houses/config/metas.configs.php');
        $entity = \models\SiteSeoMeta::findOneAsArray($area, $path);

        $this->view->setActiveMenuId('seo-'.$area);

        return $this->render('index.phtml', [
            'area' => $area,
            'currentPath' => $path,
            'configs' => $configs,
            'entity' => $entity
        ]);
    }
}