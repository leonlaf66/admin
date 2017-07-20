<?php
namespace module\dashboard\controllers;

use WS;
use yii\filters\AccessControl;

class DefaultController extends \module\core\component\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index.phtml');
    }

    public function actionError()
    {
        return $this->render('error.phtml');
    }
}