<?php
namespace module\configurtion\model;

use WS;

class Configure extends \models\SiteSetting
{
    public function attributeLabels()
    {
        return [
            'id'=>'#ID',
            'path'=>'Path',
            'value'=>'Value'
        ];
    }

    public function rules()
    {
        return [
            ['path', 'required']
        ];
    }

    public static function getMergedData()
    {
        $mergedData = [];

        $localConfiguationData = WS::$app->configuationData;
        foreach($localConfiguationData as $group) {
            $groupName = $group[0];
            $keys = $group[1];
            if(is_string($keys)) {
                $keys = explode(',', $keys);
            }
            
            $items = [];
            foreach($keys as $key) {
                if(trim($key) !== '') {
                    $items[trim($key)] = self::getValue($key);
                }
            }

            $mergedData[] = [
                'name'=>$groupName,
                'items'=>$items
            ];
        }

        return $mergedData;
    }

    public static function saveRowData($path, $data, $toJson = false)
    {
        $item = self::find()->where(['path'=>$path])->one();
        if(! $item) $item = new self();

        $item->path = $path;
        $item->value = $toJson ? json_encode($value) : $value;
        $item->save();
    }

    public static function saveData($data, $toJson = false)
    {
        $item = null;

        foreach($data as $path=>$value) {
            $this->saveRowData($path, $value);
        }
    }
}