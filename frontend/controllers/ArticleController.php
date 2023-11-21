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


        //echo $model->userId;
        //echo $model->title;
        //echo $model->body;

        if ($model->validate()) {
            var_dump($model->attributes);
            //return $model->makePublish();
        }
    }


    /*
    public function actionPublish() {
        if ($this->request->isPost) {
            $queryParams = $this->request->post();
            $accessToken = $params["token"] ?? "";
            $title = $queryParams["title"] ?? "";
            $body = $queryParams["body"] ?? "";

            if ($accessToken == "") {
                return [
                    "message" => "Unsuccessful publish",
                    "error" => "No access token"
                ];
            }

            if ($title == "") {
                return [
                    "message" => "Unsuccessful publish",
                    "error" => "No title text"
                ];
            }

            if ($body == "") {
                return [
                    "message" => "Unsuccessful publish",
                    "error" => "No body text"
                ];
            }

            $user = User::getUserByAccessToken($accessToken);
            if ($user) {
                $article = new Article();
                if ($article->makePublish($user->id, $title, $body)) {
                    return [
                        "message" => "Successful publish"
                    ];
                } else {
                    return [
                        "message" => "Unsuccessful publish",
                        "error" => $article->getErrors()
                    ];
                }
            } else {
                return [
                    "message" => "Can't find user"
                ];
            }
        }
    }
    */

    public function actionAll() {
        $model = new ArticleListForm();
        return $model->getArticleList();
    }   

    public function actionMy() {
        $model = new ArticleListForm();
        return $model->getArticleList();
    }  
}