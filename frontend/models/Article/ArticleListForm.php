<?php

namespace frontend\models\Article;

use common\models\Article\Article;
use common\models\User\User;
use Yii;
use yii\base\Model;

class ArticleListForm extends Model
{
    public $accessToken;
    public $limit;
    public $offset;

    public $articles;

    public function rules()
    {
        return [
                ['accessToken', 'exist', 'targetClass' => '\common\models\AccessToken\AccessToken',
                    'targetAttribute' => 'token',
                    'message' => "This access token doesn't exist."],
                ['limit', 'default', 'value' => Yii::$app->params['article.limit']],

                ['offset', 'default', 'value' => Yii::$app->params['article.offset']],
        ];
    }

    public function getAllArticleList()
    {
        if (!$this->validate())
        {
            return $this->getErrors();
        }

        $this->articles = Article::find()
            ->limit($this->limit)
            ->offset($this->offset)
            ->asArray()->all();

        return $this->serialize();
    }

    public function getMyArticleList()
    {
        if (!$this->validate()) {
            return $this->getErrors();
        }

        if ($this->accessToken)
        {
            $user = User::getUserByAccessToken($this->accessToken);

            $this->articles = $user->getArticles()
                ->limit($this->limit)
                ->offset($this->offset)
                ->all();

            return $this->serialize();
        }
        else
        {
            return [
                "message" => "Unsuccessful get my articles",
                "error" => "No access token"
            ];
        }
    }

    public function serialize()
    {
        $result = [];

        foreach ($this->articles as $article)
        {
            $model = new Article($article);
            $result[] = $model->shortSerialize();
        }

        return $result;
    }
}