<?php
namespace module\user\controllers;

use WS;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class AccountController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        if (! WS::$app->user->isGuest) {
            return $this->redirect(array('/dashboard/default/index'));
        }

        $loginForm = new \module\user\model\LoginForm();
        $loginForm->load(WS::$app->request->get('loginForm'));

        if ($loginForm->load(WS::$app->request->post()) && $loginForm->login()) {
            return $this->goBack();
        } else {
            $this->layout = '@module/page/views/layouts/base.phtml';
            return $this->render('login.phtml', [
                'model' => $loginForm,
            ]);
        }
    }

    public function actionLogout()
    {
        WS::$app->user->logout();

        return $this->redirect(array('/user/account/login/'));
    }
}
