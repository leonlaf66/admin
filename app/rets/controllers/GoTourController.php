<?php
namespace module\rets\controllers;

use WS;

class GoTourController extends \module\core\component\Controller
{
    public function actionIndex()
    {
        $this->view->setActiveMenuId('rets-go-tour');

        $searchModel = new \module\rets\models\GoTourFilter();
        $searchModel->setAttributes(\Yii::$app->request->get('GoTourFilter'));

        $dataProvider = \module\rets\models\GoTour::search($searchModel);

        return $this->render('index.phtml', [
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);
    }

    public function actionConfirm($id=null)
    {
        if($item = \module\rets\models\GoTour::findOne($id)) {
            if($item->confirm()) {
                WS::$app->session->setFlash('success', 'The item has been confirmed!');
            }
        }
        return $this->redirect('/index.php?r=rets%2Fgo-tour%2Findex');
    }
}