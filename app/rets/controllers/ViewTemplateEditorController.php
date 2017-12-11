<?php
namespace module\rets\controllers;

use WS;

class ViewTemplateEditorController extends \module\core\component\Controller
{
    public function actionShow($type = 'rn')
    {
        $this->view->setActiveMenuId('rets-view-template');

        $model = \module\rets\models\ViewTemplateEditorForm::findOne($type);

        if(WS::$app->request->isPost) {
            $model->load(WS::$app->request->post());
            $model->type_id = $type;
            if($model->validate()) {
                $model->save();
            }
        }
        
        return $this->render('show.phtml', [
            'currentType'=>$type,
            'model'=>$model
        ]);
    }

    public function actionListhubShow($type = 'rn', $area = null)
    {
        $this->view->setActiveMenuId('rets-view-template2');

        $model = \module\rets\models\ListhubViewTemplateEditorForm::findOne($type);

        if(WS::$app->request->isPost) {
            $model->load(WS::$app->request->post());
            $model->type_id = $type;
            if($model->validate()) {
                $model->save();
            }
        }

        return $this->render('listhub-show.phtml', [
            'currentType'=>$type,
            'model'=>$model
        ]);
    }
}