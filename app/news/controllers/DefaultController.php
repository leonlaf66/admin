<?php
namespace module\news\controllers;

use WS;
use module\news\models\News;
use module\news\models\NewsSearch;
use yii\web\UploadedFile;

class DefaultController extends \module\core\component\Controller
{
    public function actionIndex()
    {
        $this->view->setActiveMenuId('news');

        $searchModel = new NewsSearch();
        $searchModel->setAttributes(\Yii::$app->request->get('NewsSearch'));

        $dataProvider = News::doSearch($searchModel);

        return $this->render('index.phtml', [
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ]);
    }

    public function actionEdit($id=null)
    {
        if($id) {
            $news = News::find($id)->where(['id'=>$id])->one();
        }
        else {
            $news = new News();
        }

        if(WS::$app->request->isPost) {
            $news->setAttributes(WS::$app->request->post('News'));
            $news->content_type = 'md';
            $news->updated_at = date('Y-m-d H:i:s', time());
            if ($news->validate()) {
                $news->save();
                $this->redirect(['/news/default/index']);
            }
        }

        return $this->render('edit.phtml', [
            'news'=>$news
        ]);
    }

    public function beforeAction($action)
    {            
        if ($action->id == 'upload-image') {
            WS::$app->controller->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionUploadImage()
    {
        $result = ['error'=>0, 'url'=>'#'];

        $uploader = UploadedFile::getInstanceByName('imgFile');

        $media = \common\helper\Media::init('news');
        $pathImage = $media->getUploader()->uploadAsFormData($uploader);
        $result['url'] = $media->getUrl($pathImage);

        echo json_encode($result);
        WS::$app->end();
    }
}