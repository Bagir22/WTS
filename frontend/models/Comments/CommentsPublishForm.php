<?php

namespace frontend\models\Comments;

use yii\base\Model;
use yii;

use common\models\Comments;
use common\models\User;

class CommentsPublishForm extends Model
{
    public $accessToken;
    public $articleId;
    public $body;

    public function rules()
    {
        return [
            [['accessToken', 'articleId', 'body'], 'required'],

            ['accessToken', 'exist', 'targetClass' => '\common\models\AccessToken',
                'targetAttribute' => 'token',
                'message' => "This access token doesn't exist."],

            ['articleId', 'exist', 'targetClass' => '\common\models\Article',
                'targetAttribute' => 'id',
                'message' => "This article doesn't exist."],
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
                "message" => "Unsuccessful publish comment",
                "error" => "No find user by access token"
            ];
        }
        else
        {
            $comment = new Comments();
            $comment->userId = $user->id;
            $comment->articleId = $this->articleId;
            $comment->body = $this->body;

            return $comment->saveComment();
        }
    }
}