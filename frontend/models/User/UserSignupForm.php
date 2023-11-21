<?php

namespace frontend\models\User;

use common\models\AccessToken;
use common\models\User;
use yii\base\Model;
use common\models\Article;
use Yii;

class UserSignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['username','password'], 'required'],
            [['email'], 'email'],
        ];
    }

    public function init() {
        $this->attributes = Yii::$app->request->post();
    }

    public function signup() {
        $model = new User();
        $model->username = $this->username;
        $model->email = $this->email;
        $model->setPassword($this->password);
        $model->generateAuthKey();
        $model->generateEmailVerificationToken();
        $model->created_at = time();
        $model->updated_at = time();

        if ($model->save()) {
            $accessToken = new AccessToken();
            $accessToken->userId = $model->id;
            $accessToken->token = Yii::$app->security->generateRandomString();

            if ($accessToken->save()) {
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
                "error" => $this->getErrors(),
            ];
        }

    }

}