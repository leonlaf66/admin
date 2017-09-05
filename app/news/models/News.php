<?php
namespace module\news\models;

class News extends \common\news\News
{
    public $image;

    public function rules()
    {
        return [
            [['type_id', 'title', 'content'], 'required'],
            [['title'], 'string', 'max'=>100],
            [['content', 'md_content'], 'string', 'min'=>10],
            [['is_public', 'is_infomation', 'is_hot'], 'boolean', 'trueValue'=>'1', 'falseValue'=>'0'],
            // [['image'], 'file'],
            [['status', 'towns', 'updated_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'type_id'=>'Type',
            'status' => 'Status',
            'is_infomation' => 'Is Information',
            'is_hot' => 'Is Hot'
        ];
    }

    public static function search($newsSearch = null)
    {
        $query = self::find();

        $query->filterWhere(['id'=>$newsSearch->id, 'type_id'=>$newsSearch->type_id, 'status'=>$newsSearch->status]);
        $query->andFilterWhere(['like', 'title', $newsSearch->title]);
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        return $dataProvider;
    }
}