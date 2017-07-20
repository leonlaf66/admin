<?php
namespace module\rets\models;

class GoTourFilter extends \yii\base\Model
{
    public $id;
    public $user_id;
    public $list_no;
    public $date_start;
    public $date_end;
    
    public function rules()
    {
        $safeFields = 'id,user_id,list_no,date_start, date_end';
        return [
            [explode(',', $safeFields), 'safe']
        ];
    }
}