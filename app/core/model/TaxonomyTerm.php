<?php
namespace module\core\model;

use WS;

class TaxonomyTerm extends \models\TaxonomyTerm
{
    public static function getTreeNav($rootId)
    {
        $terms = self::find()
            ->orderBy(['sort_order'=>SORT_ASC, 'parent_id'=>SORT_ASC])
            ->where('taxonomy_id=:id and status=0', [':id'=>$rootId])
            ->all();

        $convertedTreeData = [];
        foreach($terms as $term) {
            $convertedTreeData[] = [
                'id'=>$term->id,
                'name'=>$term->name,
                'pId'=>$term->parent_id,
            ];
        }

        return $convertedTreeData;
    }

    public static function getNewWeightValue($parentId)
    {
        return self::find()->where(['parent_id'=>$parentId])->max('sort_order') + 1;
    }

    public function rules()
    {
        return [];
    }
}