<?php

namespace frontend\models\Article;

use common\models\User;
use Exception;
use common\models\Article;
use Yii;
class ArticlePublishForm extends Article
{
    public $userId;
    public $title;
    public $body;

    public function rules()
    {
        return [
            [['userId', 'title', 'body'], 'required'],
        ];
    }

    public function init() {
        $this->attributes = Yii::$app->request->post();

        $accessToken = Yii::$app->request->post()['token'] ?? "";

        if (!$accessToken) {
            throw new Exception("No access token");
        }

        $user = User::getUserByAccessToken($accessToken);

        $this->userId = $user->id ?? -1;
    }
}