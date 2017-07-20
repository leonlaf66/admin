<?php
return \yii\helpers\ArrayHelper::merge(get_fdn_etc(), [
    'id' => 'usleju-admin',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'layout'=>'@module/page/views/layouts/main.phtml',
    'defaultRoute'=>'dashboard/default/index',
    'components' => [
        'request' => [
            'enableCsrfValidation'=>true,
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'S1NLWjJ3UV8qJwMWbQYBORFlGhlGQTYWfyQFLV01I20/IQ0edEIkNA==',
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => false,
            'suffix'=>'/',
            'rules'=>[
                '/' => 'dashboard/default/index'
            ]
        ],
        'view'=>[
            'class'=>'module\core\component\View',
            'defaultExtension'=>'phtml'
        ],
        'assetManager'=>[
            /*cdn http://developer.baidu.com/wiki/index.php?title=docs/cplat/libs#jQuery*/
            /*cdn http://www.cdnjs.cn/*/
            /*cdn http://www.bootcdn.cn/jquery-mobile/*/
            'assetMap'=>[
                /*css*/
                'bootstrap.css'=>'http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css',
                'ws-admin.css'=>'/css/styles.css',
                /*js*/
                'jquery.js'=>'http://cdn.bootcss.com/jquery/2.1.4/jquery.min.js',
                'bootstrap.js'=>'http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js',
                'app.js'=>'/js/app.js'
            ]
        ],
        'user' => [
            'identityClass'    => 'module\user\model\UserIdentity',
            'enableAutoLogin'  => true,
            'loginUrl'         =>['user/account/login']
        ],
    ],
    'modules'=>[
        'core'=>'module\core\Module',
        'user'=>'module\user\Module',
        'page'=>'module\page\Module',
        'dashboard'=>'module\dashboard\Module',
        'yellowpage'=>'module\yellowpage\Module',
        'configurtion'=>'module\configurtion\Module',
        'report'=>'module\report\Module',
        'news'=>'module\news\Module',
        'rets'=>'module\rets\Module',
        'schooldistrict'=>'module\schooldistrict\Module'
    ],
    'aliases'=>[
        'module'=>APP_ROOT.'/app'
    ]
]);
