<?php

namespace frontend\controllers;

use common\models\Article;
use common\models\AccessToken;
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
    
    public function actionSignup()
    {
        if ($this->request->isPost) {
            $params = $this->request->post();

            $username = $params["username"] ?? "";
            $email = $params["email"] ?? "";
            $password = $params["password"] ?? "";

            if ($username== "") {
                return [
                    "message" => "Unsuccessful signup",
                    "error" => "No username"
                ];
            }

            if ($email  == "") {
                return [
                    "message" => "Unsuccessful signup",
                    "error" => "No user email"
                ];
            }

            if ($password  == "") {
                return [
                    "message" => "Unsuccessful signup",
                    "error" => "No user password"
                ];
            }

            $model = new User();
            $model->username = $params["username"];
            $model->email = $params["email"];
            $model->setPassword($params["password"]);
            $model->generateAuthKey();
            $model->created_at = time();
            $model->updated_at = time();

            
            if ($model->save()) {
                $accessToken = new AccessToken();
                $accessToken->userId = $model->id;
                $accessToken->token = Yii::$app->security->generateRandomString();
                if ($model->save()) {
                    $accessToken->save();
                    return [
                        'accessToken' => $accessToken->token,
                    ];
                } else {
                    return [
                        "message" => "Can't create accessToken for user",
                        "error" => $accessToken->getErrors(),
                    ];
                }                
            } else {
                return [
                    "message" => "Can't create user",
                    "error" => $model->getErrors(),
                ];
            }
        }
    }

    public function actionLogin() {
        if ($this->request->isPost) {
            $params = $this->request->post();
            $user = User::findByEmail($params["email"]);
            if ($user->validatePassword($params["password"])) {
                return $user->getAccessTokenByUserID($user->id);
            } else {
                return [
                    "message" => "Can't validate user"
                ];
            }
        }
    }   
}