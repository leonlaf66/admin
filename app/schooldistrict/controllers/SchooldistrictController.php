<?php
namespace module\schooldistrict\controllers;

use WS;
use module\schooldistrict\model\SchoolDistrict;

class SchooldistrictController extends \module\core\component\Controller
{
    public function actionIndex()
    {
        $this->view->setActiveMenuId('schooldistrict');

        $codes = SchoolDistrict::xFind()->select('code')->column();

        return $this->render('index.phtml', ['codes'=>$codes]);
    }

    public function actionEdit($id)
    {
        $id = urldecode($id);

        $schoolDistrict = SchoolDistrict::find()->where(['code'=>$id])->one();
        if(! $schoolDistrict) {
            $schoolDistrict = new SchoolDistrict();
            $schoolDistrict->code = $id;
            $schooldistrict->name = '';
        }

        if(WS::$app->request->isPost) {
            $json = WS::$app->request->post('json');
            $schoolDistrict->setupJsonData($json);
            $schoolDistrict->save();
        }
        return $this->render('edit.phtml', ['schoolDistrict'=>$schoolDistrict]);
    }

    public function actionRemove($id)
    {
        $id = urldecode($id);
        $schoolDistrict = SchoolDistrict::find()->where(['code'=>$id])->one();
        $schoolDistrict->delete();
        $this->redirect('/index.php?r=schooldistrict%2Fschooldistrict%2Findex');
    }
}