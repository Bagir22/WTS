<?php

namespace frontend\models\User;

use yii\base\Model;
use yii;

use common\models\User;

class UserLoginForm extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],

            [['email'], 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['email', 'string', 'min' => 2, 'max' => 255],

            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    public function init()
    {
        $this->attributes = Yii::$app->request->post();
    }

    public function login()
    {
        $user = User::findByEmail($this->email);
        if ($user->validatePassword($this->password))
        {
            return ["accessToken" => $user->getAccessTokenByUserID($user->id)];
        } else {
            return [
                "message" => "Can't validate user"
            ];
        }
    }
}