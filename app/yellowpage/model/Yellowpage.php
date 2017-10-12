<?php
namespace module\yellowpage\model;

class Yellowpage extends \models\YellowPage
{
    public $photo;
    public $category_ids;
    public $city_ids;

    public function attributeLabels()
    {
        return [
            'intro'=>'Description',
            'category_ids'=>'Categories',
            'city_ids'=>'Cities',
            'business_cn'=>'CN Business'
        ];
    }

    public function rules()
    {
        return [
            [['name', 'business', 'business_cn', 'contact', 'address', 'phone', 'email'], 'required'],
            [['is_top'], 'boolean'],
            [['photo_hash', 'license', 'is_top', 'website', 'intro', 'category_ids', 'city_ids'], 'safe'],
            [['longitude', 'latitude'], 'number'],
            [['photo'], 'image', 'extensions'=>['jpg']]
        ];
    }

    public function afterFind()
    {
        $this->category_ids = $this->getCategoryIds();
        $this->city_ids = $this->getCityIds();

        return parent::afterFind();
    }

    public function afterSave($insert, $changedAttributes)
    {
        if(! $this->category_ids) $this->category_ids = [];
        $originTypeIds = $this->getCategoryIds();
        foreach($originTypeIds as $originTypeId) {
            if(! in_array($originTypeId, $this->category_ids)) {
                \WS::$app->db->createCommand()
                    ->delete('yellow_page_type', 'yellow_page_id=:ypid and type_id=:tid', [
                            ':ypid'=>$this->id,
                            ':tid'=>$originTypeId
                        ])
                    ->execute();
            }
        }

        foreach($this->category_ids as $cid) {
            if(! in_array($cid, $originTypeIds)) {
                $m = new \models\YellowPageType();
                $m->yellow_page_id = $this->id;
                $m->type_id = $cid;
                $m->save();
            }
        }

        if(! $this->city_ids) $this->city_ids = [];
        $originCityIds = $this->getCityIds();
        foreach($originCityIds as $originCityId) {
            if(! in_array($originCityId, $this->city_ids)) {
                \WS::$app->db->createCommand()
                    ->delete('yellow_page_city', 'yellowpage_id=:ypid and city_id=:cid', [
                            ':ypid'=>$this->id,
                            ':cid'=>$originCityId
                        ])
                    ->execute();
            }
        }

        foreach($this->city_ids as $cid) {
            if(! in_array($cid, $originCityIds)) {
                \WS::$app->db->createCommand()->insert('yellow_page_city', [
                    'yellowpage_id'=>$this->id,
                    'city_id'=>$cid
                ])->execute();
            }
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function getCityIds()
    {
        return \WS::$app->db
            ->createCommand('select city_id from yellow_page_city where yellowpage_id='.intval($this->id))
            ->queryColumn();
    }

    public function getCategoryIds()
    {
        return \WS::$app->db
            ->createCommand('select type_id from yellow_page_type where yellow_page_id='.intval($this->id))
            ->queryColumn();
    }

    public static function search($searchModel = null)
    {
        $likeCols = ['name'];

        $query = self::find();
        foreach($searchModel as $key=>$value) {
            if(is_array($value)) {
                if(count($value) !== 2) continue;

                list($start, $end) = $searchModel->$key;
                if($start === '' or $end === '') continue;

                $query->andWhere("{$key} between :{$key}_1 and :{$key}_2", [":{$key}_1"=>$start, ":{$key}_2"=>$end]);
            }
            else {
                if(trim($value)==='' || is_null($value)) continue;
                if(in_array($key, $likeCols)) {
                    $query->andWhere("{$key} like :{$key}", [":{$key}"=>'%'.$searchModel->$key.'%']);
                }
                else {
                    $query->andWhere("{$key}=:{$key}", [":{$key}"=>$searchModel->$key]);
                }
            }
        }

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'weight' => SORT_DESC
                ]
            ],
        ]);

        return $dataProvider;
    }
}