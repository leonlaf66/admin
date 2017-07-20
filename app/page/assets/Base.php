<?php
namespace module\page\assets;

use yii\web\AssetBundle;

class Base extends AssetBundle
{
    public $css = [
        'bootstrap.css', '/css/select2.min.css', '/css/styles.css'
    ];
    public $js = [
        'jquery.js', 'bootstrap.js', '/js/select2.full.min.js', '/js/app.js'
    ];
    public $depends = [
        
    ];
    public $cssOptions = ['position'=>\yii\web\View::POS_HEAD];
    public $jsOptions = ['position'=>\yii\web\View::POS_HEAD];
}