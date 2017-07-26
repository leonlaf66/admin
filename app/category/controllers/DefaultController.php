<?php
namespace module\category\controllers;

use WS;
use \module\core\model\TaxonomyTerm;

class DefaultController extends \module\core\component\Controller
{
    public function actionIndex($taxonomyId)
    {
        $this->view->setActiveMenuId('categories-'.$taxonomyId);

        $taxonomy = WS::$app->db->createCommand('select * from catalog_taxonomy where id=:id', [':id' => $taxonomyId])
            ->queryOne();

        $categoryTreeData = TaxonomyTerm::getTreeNav($taxonomyId);
        array_push($categoryTreeData, [
            'id'=>0,
            'name'=>$taxonomy['description'],
            'pId'=>0,
            'open'=>true,
            'lock'=>true
        ]);
        
        return $this->render('index.phtml', [
            'taxonomyId' => $taxonomyId,
            'typeName' => $taxonomy['description'],
            'categoryTreeData'=>$categoryTreeData
        ]);
    }

    public function actionSave($taxonomyId) 
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
                $term->taxonomy_id = $taxonomyId;
                $term->parent_id = $pid;
                $term->status = 0;
                $term->sort_order = TaxonomyTerm::getNewWeightValue($pid);
            }
            $term->name = $name;
            if (!$term->name_cn) {
                $term->name_cn = $name;
            }
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