<?php

namespace frontend\models\Comments;

use common\models\Comments;
use common\models\User;
use yii\base\Model;
use common\models\Article;
use Yii;

class CommentsListForm extends Model
{
    public $accessToken;
    public $articleId;

    public $comments;
    public function rules()
    {
        return [];
    }

    public function init() {
        $this->accessToken = Yii::$app->request->get()['token'] ?? "";
        $this->articleId = Yii::$app->request->get()['articleId'] ?? "";
    }

    public function getCommentsList() {
        if ($this->accessToken) {
            $user = User::getUserByAccessToken($this->accessToken);
            return Comments::findAll(['articleId' => $this->articleId, 'userId' => $user->id]);
        } else {
            return Comments::findAll(['articleId' => $this->articleId]);
        }
    }

    public function serialize() {
        $result = [];

        foreach ($this->comments as $comment) {
            array_push( $result, $this->shortSerialize($comment));
        }

        return $result;
    }
    public function shortSerialize($comment) {

        return [
            "userId" => $comment["userId"],
            "body" => $comment["body"],
        ];
    }

    public function longSerialize($comment) {
        return [
            "commentId" => $comment["id"],
            "userId" => $comment["userId"],
            "articleId" => $comment["articleId"],
            "body" => $comment["body"],
        ];
    }
}