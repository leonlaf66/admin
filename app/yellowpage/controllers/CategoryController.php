<?php
namespace module\yellowpage\controllers;

use \module\core\model\TaxonomyTerm;

use WS;

class CategoryController extends \module\core\component\Controller
{
    CONST YELLOWPAGE_TAXONOMY_ID=2;

    public function actionIndex()
    {
        $this->view->setActiveMenuId('yellowpage-categories');

        $categoryTreeData = TaxonomyTerm::getTreeNav(2);
        array_push($categoryTreeData, [
            'id'=>0,
            'name'=>'Yellowpage Categories',
            'pId'=>0,
            'open'=>true,
            'lock'=>true
        ]);
        
        return $this->render('index.phtml', ['categoryTreeData'=>$categoryTreeData]);
    }

    public function actionSave() 
    {
        if(WS::$app->request->isPost) {
            $pid = WS::$app->request->post('pid');
            $id = WS::$app->request->post('id');
            $name = WS::$app->request->post('name');
            
            $term = null;
            if($id!=='new') {
                $term = TaxonomyTerm::findOne($id);
            }
            if(is_null($term)) {
                $term = new TaxonomyTerm();
                $term->taxonomy_id = self::YELLOWPAGE_TAXONOMY_ID;
                $term->parent_id = $pid;
                $term->sort_order = TaxonomyTerm::getNewWeightValue($pid);
            }
            $term->name = $name;
            $term->save();

            return $term->id;
        }
        return 0;
    }

    public function actionSort() 
    {
        if(WS::$app->request->isPost) {
            $ids = WS::$app->request->post('ids');
            foreach($ids as $idx=>$id) {
                $term = TaxonomyTerm::findOne($id);
                $term->sort_order = $idx;
                $term->save();
            }
        }
    }

    public function actionRemove() 
    {
        if(WS::$app->request->isPost) {
            $id = WS::$app->request->post('id');
            if($term = TaxonomyTerm::findOne($id)) {
                $term->delete();
            }
        }
        return true;
    }
}