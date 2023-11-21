<?php

namespace frontend\models\User;

use common\models\AccessToken;
use common\models\User;
use yii\base\Model;
use common\models\Article;
use Yii;

class UserLoginForm extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['password'], 'required'],
            [['email'], 'email'],
        ];
    }

    public function init() {
        $this->attributes = Yii::$app->request->post();
    }

    public function login() {
        $user = User::findByEmail($this->email);
        if ($user->validatePassword($this->password)) {
            return ["accessToken" => $user->getAccessTokenByUserID($user->id)];
        } else {
            return [
                "message" => "Can't validate user"
            ];
        }
    }
}