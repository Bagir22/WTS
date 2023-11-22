<?php

namespace frontend\models\Comments;

use common\models\Comments;
use common\models\User;
use yii\base\Model;
use Exception;
use common\models\Article;
use Yii;
class CommentsPublishForm extends Model
{
    public $accessToken;
    public $articleId;
    public $body;

    public function rules()
    {
        return [
            [['accessToken', 'articleId', 'body'], 'required'],
        ];
    }

    public function init() {
        $this->attributes = Yii::$app->request->post();
    }

    public function makePublish() {
        if (!$this->accessToken) {
            throw new Exception("No access token");
        }

        $user = User::getUserByAccessToken($this->accessToken);

        if (!$user) {
            throw new Exception("No find user");
        } else {
            $comments = new Comments();
            $comments->userId = $user->id;
            $comments->articleId = $this->articleId;
            $comments->body = $this->body;

            if ($comments->save()) {
                return [
                    "message" => "Successful publish comment"
                ];
            } else {
                return [
                    "message" => "Unsuccessful publish comment",
                    "error" => $this->getErrors()
                ];
            }
        }
    }
}