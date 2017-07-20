<?php
namespace module\rets\models;

class GoTour extends \common\estate\gotour\Tour
{
    public function getUser()
    {
        return $this->hasOne(\common\customer\Account::className(), ['id'=>'user_id']);
    }

    public static function search($turoForm)
    {
        $query = self::find();

        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        return $dataProvider;
    }
}