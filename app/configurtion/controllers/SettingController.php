<?php
namespace module\configurtion\controllers;

use WS;

class SettingController extends \module\core\component\Controller
{
    public function actionIndex()
    {
        $mergedData = \module\configurtion\model\Configure::getMergedData();

        $this->view->setActiveMenuId('system-setting');

        return $this->render('index.phtml', ['configData'=>$mergedData]);
    }

    public function actionSave()
    {
        if(WS::$app->request->isPost) {
            $configData = WS::$app->request->post('config');
            \module\configurtion\model\Configure::saveData($configData);
        }
        return $this->redirect(array('/configurtion/setting/index'));
    }
}