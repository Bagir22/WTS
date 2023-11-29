<?php

namespace frontend\models\User;

use common\models\User\User;
use yii;
use yii\base\Model;

class UserSignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['username','password', 'email'], 'required'],

            ['username', 'unique', 'targetClass' => '\common\models\User\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            [['email'], 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User\User', 'message' => 'This email address has already been taken.'],
            ['email', 'string', 'min' => 2, 'max' => 255],

            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    public function signup()
    {
        if (!$this->validate())
        {
            return $this->getErrors();
        }

        $user = new User();
        $user->load($this->toArray(), "");
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->created_at = time();
        $user->updated_at = time();

        return $user->saveUser();
    }
}