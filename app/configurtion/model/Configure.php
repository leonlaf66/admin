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
            ['path', 'required'],
            [['value', 'site_id'], 'safe']
        ];
    }

    public static function getAdminData($areaId, $settingTree)
    {
        $mergedData = [];

        $localConfiguationData = WS::$app->configuationData;
        foreach ($settingTree as $group) {
            $groupTitle = $group['title'];

            $resultItems = [];
            foreach ($group['items'] as $id => $options) {
                if (isset($localConfiguationData[$id])) {
                    $options = array_merge($localConfiguationData[$id], $options);

                    if (is_null($areaId) && isset($options['private']) === false) {
                        $resultItems[$id] = [
                            'title' => $options['title'],
                            'value' => self::getValue($id)
                        ];
                    } elseif (!is_null($areaId) && isset($options['private']) ) {
                        $resultItems[$id] = [
                            'title' => $options['title'],
                            'value' => self::getValue($id, $areaId)
                        ];
                    }
                }

                if (isset($resultItems[$id]) && isset($options['link'])) {
                    unset($resultItems[$id]['value']);
                    $resultItems[$id]['link'] = true;
                }
            }

            $mergedData[] = [
                'name'=>$groupTitle,
                'items'=>$resultItems
            ];
        }

        return array_filter($mergedData, function ($group) {
            return count($group['items']) > 0;
        });
    }

    public static function saveRowData($areaId, $path, $value, $toJson = false)
    {
        $item = self::find()->where(['path'=>$path, 'site_id' => $areaId])->one();
        if (! $item) {
            $item = new self();
            $item->path = $path;
            $item->site_id = $areaId;
        }

        $item->value = $toJson ? json_encode($value) : $value;
        $item->save();
    }

    public static function saveData($areaId, $data, $toJson = false)
    {
        $item = null;

        foreach($data as $path=>$value) {
            self::saveRowData($areaId, $path, $value, $toJson);
        }
    }
}