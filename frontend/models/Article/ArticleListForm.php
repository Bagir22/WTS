<?php

namespace frontend\models\Article;

use Yii;
use yii\base\Model;

use common\models\Article;
use common\models\User;

class ArticleListForm extends Model
{
    public $accessToken;
    public $limit;
    public $offset;

    public $articles;

    public function rules()
    {
        return [
            ['accessToken', 'exist', 'targetClass' => '\common\models\AccessToken',
                'targetAttribute' => 'token',
                'message' => "This access token doesn't exist."],

            ['limit', 'default', 'value' => Yii::$app->params['article.limit']],

            ['offset', 'default', 'value' => Yii::$app->params['article.offset']],
        ];
    }

    public function init()
    {
        $this->attributes = Yii::$app->request->get();
    }

    public function getArticleList()
    {
        if (!$this->validate())
        {
            return $this->getErrors();
        }

        if ($this->accessToken)
        {
            $user = User::getUserByAccessToken($this->accessToken);

            $this->articles = $user->getArticles()
                ->limit($this->limit)
                ->offset($this->offset)
                ->asArray()->all();

            return $this->serialize();
        }
        else
        {
            $this->articles = Article::find()
                ->limit($this->limit)
                ->offset($this->offset)
                ->asArray()->all();

            return $this->serialize();
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