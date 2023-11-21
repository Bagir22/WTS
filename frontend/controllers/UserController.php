<?php

namespace frontend\controllers;

use common\models\Article;
use common\models\AccessToken;
use frontend\models\User\UserLoginForm;
use frontend\models\User\UserSignupForm;
use yii;
use yii\rest\Controller;
use common\models\User;
use yii\helpers\Json;
use yii\web\Response;

class UserController extends Controller
{
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionSignup() {
        $model = new UserSignupForm();
        return $model->signup();
    }

    public function actionLogin() {
        $model = new UserLoginForm();
        return $model->login();
    }   
}