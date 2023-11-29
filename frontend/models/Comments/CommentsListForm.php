<?php

namespace frontend\models\Comments;

use common\models\Comments\Comments;
use common\models\User\User;
use yii;
use yii\base\Model;

class CommentsListForm extends Model
{
    public $accessToken;
    public $articleId;

    public $limit;
    public $offset;

    public $comments;
    public function rules()
    {
        return [
            [['articleId'], 'required'],

            ['accessToken', 'exist', 'targetClass' => '\common\models\AccessToken\AccessToken',
                'targetAttribute' => 'token',
                'message' => "This access token doesn't exist."],

            ['articleId', 'exist', 'targetClass' => '\common\models\Article\Article',
                'targetAttribute' => 'id',
                'message' => "This article doesn't exist."],

            ['limit', 'default', 'value' => Yii::$app->params['comment.limit']],

            ['offset', 'default', 'value'  => Yii::$app->params['comment.offset']],
        ];
    }

    public function getAllCommentsList()
    {
        if (!$this->validate())
        {
            return $this->getErrors();
        }

        $this->comments = Comments::find()
            ->where(['articleId' => $this->articleId])
            ->limit($this->limit)
            ->offset($this->offset)->all();

        return $this->serialize();
    }

    public function getMyCommentsList()
    {
        if (!$this->validate())
        {
            return $this->getErrors();
        }

        if ($this->accessToken)
        {
            $user = User::getUserByAccessToken($this->accessToken);
            $this->comments = Comments::find()
                ->where(['articleId' => $this->articleId, 'userId' => $user->id])
                ->limit($this->limit)
                ->offset($this->offset)->all();

            return $this->serialize();
        }
        else
        {
            return [
                "message" => "Unsuccessful get my comment for article",
                "error" => "No access token"
            ];
        }
    }
    public function serialize()
    {
        $result = [];

        foreach ($this->comments as $comment)
        {
            $model = new Comments($comment);
            $result[] = $model->shortSerialize();
        }

        return $result;
    }
}