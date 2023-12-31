<?php

namespace frontend\controllers;

use common\models\Article\Article;
use frontend\models\Comments\CommentsPublishForm;
use Yii;
use yii\base\Model;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Response;

use frontend\models\Article\ArticlePublishForm;
use frontend\models\Article\ArticleListForm;

class ArticleController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'publish' => ['post'],
                    'all' => ['get'],
                    'my' => ['get'],
                ],
            ],
        ];
    }
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionPublish()
    {
        $model = new ArticlePublishForm();
        $model->load(Yii::$app->request->post(), "");
        return $model->makePublish();
    }

    public function actionAll() {
        $model = new ArticleListForm();
        $model->load(Yii::$app->request->get(), "");
        return $model->getAllArticleList();
    }   

    public function actionMy() {
        $model = new ArticleListForm();
        $model->load(Yii::$app->request->get(), "");
        return $model->getMyArticleList();
        /*}
        else
        {
           return $model->getErrors();
        }*/
    }
}