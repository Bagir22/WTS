<?php

namespace frontend\models\User;

use common\models\User\User;
use yii;
use yii\base\Model;

class UserLoginForm extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['email', 'password'], 'required'],

            [['email'], 'email'],
            ['email', 'exist', 'targetClass' => '\common\models\User\User', 'message' => 'This email is not exist.'],
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
        if (!$this->validate()) {
            return $this->getErrors();
        }

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