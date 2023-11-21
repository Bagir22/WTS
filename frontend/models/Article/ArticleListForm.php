<?php

namespace frontend\models\Article;

use common\models\User;
use yii\base\Model;
use common\models\Article;
use Yii;

class ArticleListForm extends Model
{
    public $accessToken;
    public $limit;
    public $offset;

    public function rules()
    {
        return [];
    }

    public function init() {
        $this->limit = Yii::$app->request->get()['limit'] ?? Yii::$app->params['limit'];
        $this->offset = Yii::$app->request->get()['offset'] ?? Yii::$app->params['offset'];

        $this->accessToken = Yii::$app->request->get()['token'] ?? "";
    }

    public function getArticleList() {
        if ($this->accessToken) {
            $user = User::getUserByAccessToken($this->accessToken);

            return $user->getArticles()
                ->limit($this->limit)
                ->offset($this->offset)
                ->asArray()->all();
        } else {
            return Article::find()
                ->limit($this->limit)
                ->offset($this->offset)
                ->asArray()->all();
        }
    }
}