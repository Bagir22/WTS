<?php

namespace frontend\models\Article;

use common\models\User;
use yii\base\Model;
use Exception;
use common\models\Article;
use Yii;
class ArticlePublishForm extends Model
{
    public $accessToken;
    public $title;
    public $body;

    public function rules()
    {
        return [
            [['accessToken', 'title'], 'required'],
            [['body'], 'default', 'value' => null],
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
            $article = new Article();
            $article->userId = $user->id;
            $article->title = $this->title;
            $article->body = $this->body;
            if ($article->save()) {
                return [
                    "message" => "Successful publish"
                ];
            } else {
                return [
                    "message" => "Unsuccessful publish",
                    "error" => $this->getErrors()
                ];
            }
        }
    }
}