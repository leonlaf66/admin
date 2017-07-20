<?php
namespace module\rets\models;

use WS;

class ViewTemplateEditorForm extends \common\estate\dict\MapDetail
{
    public function rules()
    {
        return [
            [['type', 'content'], 'required'],
        ];
    }

    public function save()
    {
        $cacheFile = self::getCacheFile($this->type);
        return file_put_contents($cacheFile, $this->content);
    }
}