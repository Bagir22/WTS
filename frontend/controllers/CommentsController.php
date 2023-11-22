<?php

namespace frontend\controllers;

use frontend\models\Comments\CommentsDeleteForm;
use frontend\models\Comments\CommentsPublishForm;
use frontend\models\Comments\CommentsListForm;
use yii\web\Response;
use yii\rest\Controller;
use yii;

class CommentsController extends Controller
{
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionPublish()
    {
        $model = new CommentsPublishForm();

        if ($model->validate()) {
            return $model->makePublish();
        } else {
            return $model->getErrors();
        }
    }

    public function actionDelete()
    {
        $model = new CommentsDeleteForm();

        if ($model->validate()) {
            return $model->deleteComment();
        } else {
            return $model->getErrors();
        }
    }

    public function actionAll() {
        $model = new CommentsListForm();
        $model->comments = $model->getCommentsList();
        return $model->serialize();
    }

    public function actionMy() {
        $model = new CommentsListForm();
        $model->articles = $model->getCommentsList();
        return $model->serialize();
    }
}
