<?php

namespace frontend\models\Comments;

use yii\base\Model;
use yii;

use common\models\Comments;
use common\models\User;

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

            ['accessToken', 'exist', 'targetClass' => '\common\models\AccessToken',
                'targetAttribute' => 'token',
                'message' => "This access token doesn't exist."],

            ['articleId', 'exist', 'targetClass' => '\common\models\Article',
                'targetAttribute' => 'id',
                'message' => "This article doesn't exist."],

            ['limit', 'default', 'value' => Yii::$app->params['comment.limit']],

            ['offset', 'default', 'value'  => Yii::$app->params['comment.offset']],
        ];
    }

    public function init()
    {
        $this->attributes = Yii::$app->request->get();
    }

    public function getCommentsList()
    {
        if (!$this->validate()) {
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
            $this->comments = Comments::find()
                ->where(['articleId' => $this->articleId])
                ->limit($this->limit)
                ->offset($this->offset)->all();

            return $this->serialize();
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