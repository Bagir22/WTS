<?php

namespace frontend\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;

use frontend\models\Article\ArticlePublishForm;
use frontend\models\Article\ArticleListForm;

class ArticleController extends Controller
{
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionPublish()
    {
        $model = new ArticlePublishForm();

        if ($model->validate()) {
            return $model->makePublish();
        } else {
            return $model->getErrors();
        }
    }

    public function actionAll() {
        $model = new ArticleListForm();
        $model->articles = $model->getArticleList();
        return $model->serialize();
    }   

    public function actionMy() {
        $model = new ArticleListForm();
        $model->articles = $model->getArticleList();
        return $model->serialize();
    }  
}