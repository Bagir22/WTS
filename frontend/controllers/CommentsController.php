<?php

namespace frontend\controllers;

use yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\Response;

use frontend\models\Comments\CommentsDeleteForm;
use frontend\models\Comments\CommentsListForm;
use frontend\models\Comments\CommentsPublishForm;

class CommentsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'publish' => ['post'],
                    'delete' => ['post'],
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
        $model = new CommentsPublishForm();
        $model->load(Yii::$app->request->post(), "");
        return $model->makePublish();
    }

    public function actionDelete()
    {
        $model = new CommentsDeleteForm();
        $model->load(Yii::$app->request->post(), "");
        return $model->deleteComment();

    }

    public function actionAll()
    {
        $model = new CommentsListForm();
        $model->load(Yii::$app->request->get(), "");
        return $model->getAllCommentsList();
    }

    public function actionMy()
    {
        $model = new CommentsListForm();
        $model->load(Yii::$app->request->get(), "");
        return $model->getMyCommentsList();
    }
}
