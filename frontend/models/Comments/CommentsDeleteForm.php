<?php

namespace frontend\models\Comments;

use yii\base\Model;
use yii;

use common\models\Comments;
use common\models\User;

class CommentsDeleteForm extends Model
{
    public $accessToken;
    public $commentId;

    public function rules()
    {
        return [
            [['accessToken', 'commentId'], 'required'],

            ['accessToken', 'exist', 'targetClass' => '\common\models\AccessToken',
                'targetAttribute' => 'token',
                'message' => "This access token doesn't exist."],

            ['commentId', 'exist', 'targetClass' => '\common\models\Comments',
                'targetAttribute' => 'id',
                'message' => "This comment doesn't exist."],
        ];
    }

    public function init() {
        $this->attributes = Yii::$app->request->post();
    }

    public function deleteComment() {
        if (!$this->validate()) {
            return $this->getErrors();
        }

        $user = User::getUserByAccessToken($this->accessToken);

        if (!$user) {
            return [
                "message" => "Unsuccessful delete comment",
                "error" => "No find user by access token"
            ];
        } else {
            $comment = Comments::findOne(['id' => $this->commentId]);
            if ($comment) {
                return $comment->deleteComment();
            }
        }

        return [
            "message" => "Unsuccessful delete comment",
            "error" => "No find comment to delete"
        ];
    }
}