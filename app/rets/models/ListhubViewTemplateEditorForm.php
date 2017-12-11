<?php
namespace module\rets\models;

use WS;

class ListhubViewTemplateEditorForm extends \models\listhub\HouseFieldPropRule
{
    public function rules()
    {
        return [
            [['type_id', 'xml_rules'], 'required'],
        ];
    }
    /*

    public function save()
    {
        $cacheFile = self::getCacheFile($this->type);
        return file_put_contents($cacheFile, $this->content);
    }*/
}