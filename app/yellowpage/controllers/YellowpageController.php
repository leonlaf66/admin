<?php
namespace module\yellowpage\controllers;

use WS;
use module\yellowpage\model\YellowpageSearch;
use module\yellowpage\model\Yellowpage;
use yii\web\UploadedFile;

class YellowpageController extends \module\core\component\Controller
{
    public function actionIndex()
    {
        $req = WS::$app->request;
        if($req->get('action') === 'weight') {
            $id = intval($req->get('id', '0'));
            $value = intval($req->get('value', 0));

            $result = WS::$app->db->createCommand()
                ->update('catalog_yellow_page', ['weight'=>$value], 'id='.intval($id))
                ->execute();
            echo json_encode($result);
            WS::$app->end();
        }

        $this->view->setActiveMenuId('yellowpages');

        $searchModel = new YellowpageSearch();
        $searchModel->setAttributes($req->get('YellowpageSearch'));

        $dataProvider = Yellowpage::search($searchModel);

        return $this->render('index.phtml', [
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);
    }

    public function actionEdit($id = null)
    {
        return $this->actionUpdate($id);
    }

    public function actionUpdate($id = null)
    {
        if($id) {
            $yellowpage = Yellowpage::findOne($id);
        }
        else {
            $yellowpage = new Yellowpage();
        }

        if(WS::$app->request->isPost) {
            $yellowpage->setAttributes(WS::$app->request->post('Yellowpage'));
            if ($yellowpage->validate()) {
                if($uploader = UploadedFile::getInstance($yellowpage, 'photo')) {
                    $yellowpage->photo_hash = \common\helper\Media::init('yellowpage')->getUploader()->uploadAsFormData($uploader);
                }
                $yellowpage->save();
                $this->redirect(['/yellowpage/yellowpage/index']);
            }
        }

        return $this->render('edit.phtml', [
            'yellowpage'=>$yellowpage
        ]);
    }

    public function actionDelete($id)
    {
        $id = intval($id);
        $db = \WS::$app->db;

        if($yellowpage = Yellowpage::findOne($id)) {
            $db->createCommand()->delete('catalog_yellow_page_cities', 'yellowpage_id='.$id)->execute();
            $db->createCommand()->delete('catalog_yellow_page_photos', 'yellow_page_id='.$id)->execute();
            $db->createCommand()->delete('catalog_yellow_page_tags', 'yellow_page_id='.$id)->execute();
            $db->createCommand()->delete('catalog_yellow_page_types', 'yellow_page_id='.$id)->execute();

            $yellowpage->delete();
        }
        $this->redirect(['/yellowpage/yellowpage/index']);
    }
}