<?php
namespace module\yellowpage\model;

class YellowpageSearch extends \yii\base\Model
{
    public $id;
    public $name;
    public $city;
    public $rating;
    public $hits;
    public $comments;
    
    public function rules()
    {
        $safeFields = 'id,name,contact,city,phone,email,rating,hits,comments';
        return [
            [explode(',', $safeFields), 'safe']
        ];
    }
}