<?php
namespace module\news\models;

class NewsSearch extends \yii\base\Model
{
    public $id;
    public $title;
    public $type_id;
    public $status;
    
    public function rules()
    {
        $safeFields = 'id,title,type_id,status';
        return [
            [explode(',', $safeFields), 'safe']
        ];
    }
}