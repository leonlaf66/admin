<?php
namespace module\schooldistrict\controllers;

use WS;
use module\schooldistrict\model\SchoolDistrict;

class SchooldistrictController extends \module\core\component\Controller
{
    public function actionIndex()
    {
        $this->view->setActiveMenuId('schooldistrict');

        $items = (new \yii\db\Query())
            ->from('schooldistrict')
            ->select(['id', 'code', 'city_id', 'json', 'image'])
            ->orderBy('sort_order', 'ASC')
            ->all();

        return $this->render('index.phtml', ['items'=>$items]);
    }

    public function actionEdit($id, $code = null)
    {
        $code = $code ? urldecode($code) : null;

        $schoolDistrict = SchoolDistrict::find()->where(['id'=>$id])->one();
        if(! $schoolDistrict) {
            $schoolDistrict = new SchoolDistrict();
            $schoolDistrict->code = $code;
            $schooldistrict->name = '';
        }

        if(WS::$app->request->isPost) {
            $json = WS::$app->request->post('json');
            $json = json_decode($json);
            $json->code = strtoupper($json->code);

            if(!preg_match_all('/[A-Z]{4}/', $json->code, $matches)) {
                echo 'The code has error!';
                exit;
            }
            $json->code = implode('/', $matches[0]);

            $schoolDistrict->code = $json->code;
            $schoolDistrict->city_id = $this->matchCtiyIdsExpr($json->code);
            $schoolDistrict->setupJsonData(json_encode($json));
            $schoolDistrict->save();
        }
        return $this->render('edit.phtml', ['schoolDistrict'=>$schoolDistrict]);
    }

    public function actionRemove($id)
    {
        $id = urldecode($id);
        $schoolDistrict = SchoolDistrict::find()->where(['id'=>$id])->one();
        $schoolDistrict->delete();
        $this->redirect('/index.php?r=schooldistrict%2Fschooldistrict%2Findex');
    }

    private function matchCtiyIdsExpr($code)
    {
        preg_match_all('/[A-Z]{4}/', $code, $matches);
        $codes = $matches[0];

        $cityIds = (new \yii\db\Query())
            ->from('town')
            ->select('id')
            ->where(['in', 'short_name', $codes])
            ->column();
        return '{'.implode(',', $cityIds).'}';
    }
}