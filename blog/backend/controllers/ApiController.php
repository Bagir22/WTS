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

    private function getAccessToken($id) {
        $accessToken = (new \yii\db\Query())
            ->select('accessToken')
            ->from('accessToken')
            ->where(['userId' => $id])
            ->one(); 

        return $accessToken;
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