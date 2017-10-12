<?php
namespace module\schooldistrict\model;

use common\helper\ArrayHelper;

class SchoolDistrict extends \models\SchoolDistrict
{
    public function setupJsonData($json)
    {
        $this->json = $json;
        $this->jsonData = json_decode($json);
    }

    public function beforeSave($insert)
    {
        $this->sort_order = $this->getItemData('ranking', 0);
        // $this->property = self::PROPERTY;
        return parent::beforeSave($insert);
    }
}