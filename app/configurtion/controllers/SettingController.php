<?php
namespace module\configurtion\controllers;

use WS;

class SettingController extends \module\core\component\Controller
{
    public function actionIndex($id = null)
    {
        if (is_null($id)) {
            $mergedData = \module\configurtion\model\Configure::getMergedData();

            $this->view->setActiveMenuId('setting-general');

            return $this->render('index.phtml', ['configData'=>$mergedData]);
        } else {
            $entity = \models\SiteSetting::find()
                ->where(['path' => $id])
                ->one();

            $entity->value = json_decode($entity->value);

            $render = '\module\configurtion\widgets\Render'.ucfirst($entity->input_type);

            $this->view->setActiveMenuId('setting-'.str_replace('.', '-', $entity->path));

            return $this->render('index_one.phtml', [
                'render' => $render,
                'entity'=>$entity
            ]);
        }
    }

    public function actionSave($path = null)
    {
        if ($path) {
            $data = WS::$app->request->post('data');
            $row = \models\SiteSetting::find()->where(['path' => $path])->one();
            $row->value = json_encode($data);
            return $row->save();
        }

        if(WS::$app->request->isPost) {
            $configData = WS::$app->request->post('config');
            \module\configurtion\model\Configure::saveData($configData);
        }
        return $this->redirect(array('/configurtion/setting/index'));
    }
}