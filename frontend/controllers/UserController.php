<?php

namespace frontend\controllers;

use yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use frontend\models\User\UserLoginForm;
use frontend\models\User\UserSignupForm;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'login' => ['post'],
                    'signup' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionSignup()
    {
        $model = new UserSignupForm();
        return $model->signup();
    }

    public function actionLogin()
    {
        $model = new UserLoginForm();
        return $model->login();
    }   
}