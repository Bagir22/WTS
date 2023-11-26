<?php

namespace frontend\models\Article;

use yii;
use yii\base\Model;

use common\models\User;
use common\models\Article;

class ArticlePublishForm extends Model
{
    public $accessToken;
    public $title;
    public $body;

    public function rules()
    {
        return [
            [['accessToken', 'title'], 'required'],

            ['accessToken', 'exist', 'targetClass' => '\common\models\AccessToken',
                'targetAttribute' => 'token',
                'message' => "This access token doesn't exist."],

            [['body'], 'default', 'value' => null],
        ];
    }

    public function init()
    {
        $this->attributes = Yii::$app->request->post();
    }

    public function makePublish()
    {
        if (!$this->validate())
        {
            return $this->getErrors();
        }

        $user = User::getUserByAccessToken($this->accessToken);

        if (!$user)
        {
            return [
                "message" => "Unsuccessful publish article",
                "error" => "No find user by access token"
            ];
        }
        else
        {
            $article = new Article();
            $article->userId = $user->id;
            $article->title = $this->title;
            $article->body = $this->body;

            return $article->saveArticle();
        }
    }
}