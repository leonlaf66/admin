<?php
namespace module\yellowpage\controllers;

use WS;
use module\yellowpage\model\YellowpageSearch;
use module\yellowpage\model\Yellowpage;
use yii\web\UploadedFile;

class YellowpageController extends \module\core\component\Controller
{
    public function actionIndex($area_id)
    {
        $req = WS::$app->request;
        if($req->get('action') === 'weight') {
            $id = intval($req->get('id', '0'));
            $value = intval($req->get('value', 0));

            $result = WS::$app->db->createCommand()
                ->update('yellow_page', ['weight'=>$value], 'id='.intval($id))
                ->execute();
            echo json_encode($result);
            WS::$app->end();
        }

        $this->view->setActiveMenuId('yellowpages-'.$area_id);

        $searchModel = new YellowpageSearch();
        $searchModel->setAttributes($req->get('YellowpageSearch'));

        $dataProvider = Yellowpage::search($searchModel);
        $dataProvider->query->andWhere(['area_id'=>$area_id]);

        return $this->render('index.phtml', [
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);
    }

    public function actionEdit($area_id, $id = null)
    {
        return $this->actionUpdate($area_id, $id);
    }

    public function actionUpdate($area_id, $id = null)
    {
        if($id) {
            $yellowpage = Yellowpage::findOne($id);
        }
        else {
            $yellowpage = new Yellowpage();
            $yellowpage->area_id = $area_id;
        }

        if(WS::$app->request->isPost) {
            $yellowpage->setAttributes(WS::$app->request->post('Yellowpage'));
            if ($yellowpage->validate()) {
                if($uploader = UploadedFile::getInstance($yellowpage, 'photo')) {
                    $yellowpage->photo_hash = \common\helper\Media::init('yellowpage')->getUploader()->uploadAsFormData($uploader);
                }
                $yellowpage->save();
                $this->redirect(['/yellowpage/yellowpage/index', 'area_id'=>$area_id]);
            }
        }

        //citis
        $citis = [];
        if ($area_id === 'ma') {
            $citis = \models\Town::mapOptions();
        } else {
            $citis = \models\City::mapOptions(strtoupper($area_id));
        }

        $this->view->setActiveMenuId('yellowpages-'.$area_id);

        return $this->render('edit.phtml', [
            'cities' => $citis,
            'yellowpage'=>$yellowpage
        ]);
    }

    public function actionDelete($id)
    {
        $id = intval($id);
        $db = \WS::$app->db;

        if($yellowpage = Yellowpage::findOne($id)) {
            $db->createCommand()->delete('yellow_page_city', 'yellowpage_id='.$id)->execute();
            $db->createCommand()->delete('yellow_page_photo', 'yellow_page_id='.$id)->execute();
            $db->createCommand()->delete('yellow_page_tag', 'yellow_page_id='.$id)->execute();
            $db->createCommand()->delete('yellow_page_type', 'yellow_page_id='.$id)->execute();

            $yellowpage->delete();
        }
        $this->redirect(['/yellowpage/yellowpage/index']);
    }
}