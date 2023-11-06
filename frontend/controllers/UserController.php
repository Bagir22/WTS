<?php

namespace frontend\controllers;

use common\models\Article;
use common\models\AccessToken;
use yii;
use yii\rest\Controller;
use common\models\User;
use yii\helpers\Json;

class UserController extends Controller
{
    // TODO Разнести методы по UserController и ArticleController
    public function actionSignup()
    {
        if ($this->request->isPost) {
            $params = $this->request->post();

            $username = $params["username"] ?? "";
            $email = $params["email"] ?? "";
            $password = $params["password"] ?? "";

            if ($username== "") {
                return Yii::$app->response->content = json_encode([
                    "message" => "Unsuccessful signup",
                    "error" => "No username"
                ]);
            }

            if ($email  == "") {
                return Yii::$app->response->content = json_encode([
                    "message" => "Unsuccessful signup",
                    "error" => "No user email"
                ]);
            }

            if ($password  == "") {
                return Yii::$app->response->content = json_encode([
                    "message" => "Unsuccessful signup",
                    "error" => "No user password"
                ]);
            }

            $model = new User();
            $model->username = $params["username"];
            $model->email = $params["email"];
            $model->setPassword($params["password"]);
            $model->generateAuthKey();
            $model->created_at = time();
            $model->updated_at = time();

            // TODO $model->save(); https://www.yiiframework.com/doc/guide/2.0/ru/db-active-record
            
            if ($model->save()) {
                //create and save token
                $accessToken = new AccessToken();
                $accessToken->userId = $model->id;
                $accessToken->token = Yii::$app->security->generateRandomString();
                if ($model->save()) {
                    $accessToken->save();
                    return Json::encode([
                        'accessToken' => $accessToken->token,
                    ]);
                } else {
                    return Json::encode([
                        "message" => "Can't create accessToken for user",
                        "error" => $accessToken->getErrors(),
                    ]);
                }                
            } else {
                return Json::encode([
                    "message" => "Can't create user",
                    "error" => $model->getErrors(),
                ]);
            }
        }
    }

    public function actionLogin() {
        if ($this->request->isPost) {
            $params = $this->request->post();
            $user = User::findByEmail($params["email"]);
            if ($user->validatePassword($params["password"])) {
                $token = $user->getAccessTokenByUserID($user->id);
                Yii::$app->response->content = json_encode($token);
            } else {
                Yii::$app->response->content = json_encode([
                    "message" => "Can't validate user"
                ]);
            }
        }
    }   
}