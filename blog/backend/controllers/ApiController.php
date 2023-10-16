<?php

namespace backend\controllers;

use yii;
use yii\rest\Controller;
use common\models\User;
use yii\base\Model;

/**
 * UserController implements the CRUD actions for User model.
 */
class ApiController extends Controller
{
    public function actionSignup()
    {
        if ($this->request->isPost) {
            $params = $this->request->post();
            
            $model = new User();
            $model->username = $params["username"];
            $model->email = $params["email"];
            $model->setPassword($params["password"]);
            $model->generateAuthKey();
            $db = Yii::$app->db;
            $validate = $db->createCommand('SELECT * FROM `user` where username = :username or email = :email', [
                ':username' => $model->username,
                ':email' => $model->email,
            ])->execute();
            if ($validate == 0) {
                $success = $db->createCommand('INSERT INTO `user` (`username`, `password_hash`, `email`, `created_at`, `updated_at`, `status`, `auth_key`) VALUES 
                    (:username, :password_hash, :email, :created_at, :updated_at, :status, :auth_key)', [
                    ':username' => $model->username,
                    ':password_hash' => $model->password_hash,
                    ':email' => $model->email,
                    ':created_at' => time(),
                    ':updated_at' => time(),
                    ':status' => 9,
                    ':auth_key' => $model->auth_key,
                ])->execute();
                if ($success == 1) {
                    $accessToken = $this->generateRandomString(30);
                    $userId = User::find()->select(['id'=>'MAX(`id`)'])->one()->id;
                    $success = $db->createCommand('INSERT INTO `accessToken` (`userId`, `accessToken`) VALUES (:userId, :accessToken)', [
                        ':userId' => $userId,
                        'accessToken' => $accessToken
                    ])->execute();
                    if ($success == 1) {
                    Yii::$app->response->content = json_encode(sprintf('accessToken: %s', $accessToken));
                    }
                } else {
                    Yii::$app->response->content = json_encode("Can't register user");
                }           
            } else {
                Yii::$app->response->content = json_encode("User already exist");
            }
        }
    }

    public function actionLogin() {
        if ($this->request->isPost) {
            $params = $this->request->post();
            $user = User::findByEmail($params["email"]);
            if ($user->validatePassword($params["password"])) {
                $token = $this->getAccessToken($user->id);
                Yii::$app->response->content = json_encode($token);
            } else {
                Yii::$app->response->content = json_encode("Can't validate user");
            }
        }
    }   

    public function actionPublish() {
        if ($this->request->isPost) {
            $params = $this->request->post();
            $accessToken = $params["accessToken"];
            $title = $params["title"];
            $body = $params["body"];
            
            $userId = $this->getUserByAccessToken($accessToken);
            //echo $title;
            //echo $body;
            $this->makePulish($userId["userId"], $title, $body);
        }
    }   

    public function actionMyarticle() {
        $params = $this->request->get();
        if (count($params) == 0) {
            return Yii::$app->response->content = json_encode("No accessToken");
        }
        $accessToken = $params["accessToken"];
        $userId = $this->getUserByAccessToken($accessToken);
        $articles = $this->getUserArticleList($userId["userId"]);
        Yii::$app->response->content = json_encode($articles);
    }   
    private function getAccessToken($id) {
        $accessToken = (new \yii\db\Query())
            ->select('accessToken')
            ->from('accessToken')
            ->where(['userId' => $id])
            ->one(); 

        return $accessToken;
    }

    private function getUserByAccessToken($token) {
        $userId = (new \yii\db\Query())
            ->select('userId')
            ->from('accessToken')
            ->where(['accessToken' => $token])
            ->one();
        
        return $userId;
    }

    private function makePulish($userId, $title, $body) {
        $db = Yii::$app->db;
        $query = $db->createCommand('INSERT INTO `article` (`userId`, `title`, `body`) VALUES 
                (:userId, :title, :body)', [
                ':userId' => $userId,
                ':title' => $title,
                ':body' => $body
            ])->execute();
    }

    private function getUserArticleList($id) {
        $articles = (new \yii\db\Query())
            ->select(['title', 'body'])
            ->from('article')
            ->where(['userId' => $id])
            ->all(); 

        return $articles;
    }
    private function generateRandomString($length = 30) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}