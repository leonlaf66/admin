<?php
namespace module\rets\controllers;

use WS;

class ViewTemplateEditorController extends \module\core\component\Controller
{
    public function actionShow()
    {
        $this->view->setActiveMenuId('rets-view-template');

        $currentType = WS::$app->request->get('type', 'rn');

        $model = \module\rets\models\ViewTemplateEditorForm::findOne($currentType);

        if(WS::$app->request->isPost) {
            $model->load(WS::$app->request->post());
            if($model->validate()) {
                $model->save();
            }
        }
        
        return $this->render('show.phtml', [
            'currentType'=>$currentType,
            'model'=>$model
        ]);
    }
}