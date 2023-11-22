<?php

namespace frontend\models\Comments;

use common\models\Comments;
use common\models\User;
use yii\base\Model;
use Exception;
use common\models\Article;
use Yii;
class CommentsDeleteForm extends Model
{
    public $accessToken;
    public $commentId;

    public function rules()
    {
        return [
            [['accessToken', 'commentId'], 'required'],
        ];
    }

    public function init() {
        $this->attributes = Yii::$app->request->post();
    }

    public function deleteComment() {
        if (!$this->accessToken) {
            throw new Exception("No access token");
        }

        $user = User::getUserByAccessToken($this->accessToken);

        if (!$user) {
            throw new Exception("No find user");
        } else {
            $comments = Comments::findOne(['id' => $this->commentId]);
            if ($comments && $comments->delete()) {
                return [
                    "message" => "Successful delete comment"
                ];
            } else {
                return [
                    "message" => "Unsuccessful delete comment",
                    "error" => $this->getErrors()
                ];
            }
        }
    }
}