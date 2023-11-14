<?php

namespace frontend\controllers;

use common\models\Article;
use yii;
use yii\rest\Controller;
use common\models\User;

class ArticleController extends Controller
{
    public function actionPublish() {
        if ($this->request->isPost) {
            $params = $this->request->post();
            $accessToken = $params["token"] ?? "";
            $title = $params["title"] ?? "";
            $body = $params["body"] ?? "";

            if ($accessToken == "") {
                return Yii::$app->response->content = json_encode([
                    "message" => "Unsuccessful publish",
                    "error" => "No access token"
                ]);
            }

            if ($title == "") {
                return Yii::$app->response->content = json_encode([
                    "message" => "Unsuccessful publish",
                    "error" => "No title text"
                ]);
            }

            if ($body == "") {
                return Yii::$app->response->content = json_encode([
                    "message" => "Unsuccessful publish",
                    "error" => "No body text"
                ]);
            }

            $user = User::getUserByAccessToken($accessToken);
            if ($user) {
                $article = new Article();
                if ($article->makePublish($user->id, $title, $body)) {
                    return Yii::$app->response->content = json_encode([
                        "message" => "Successful publish"
                    ]);
                } else {
                    return Yii::$app->response->content = json_encode([
                        "message" => "Unsuccessful publish",
                        "error" => $article->getErrors()
                    ]);
                }
            } else {
                return Yii::$app->response->content = json_encode([
                    "message" => "Can't find user"
                ]);
            }
        }
    }

    public function actionAll() {
        $params = $this->request->get();
        $limit = $params["limit"] ?? "";
        $offset = $params["offset"] ?? "";

        // TODO Article::find() https://www.yiiframework.com/doc/guide/2.0/ru/db-active-record

        $articles = Article::find()
            ->limit($limit)
            ->offset($offset)
            ->asArray()->all();

        Yii::$app->response->content = json_encode($articles);
    }   

    public function actionMy() {
        $params = $this->request->get();
        $accessToken = $params["token"] ?? "";
        if ($accessToken == "") {
            return Yii::$app->response->content = json_encode([
                "message" => "No access token"
            ]);
        }

        $user = User::getUserByAccessToken($accessToken);
        if ($user) {
            $limit = $params["limit"] ?? "";
            $offset = $params["offset"]?? "";
            
            $articles = $user->getArticles()
                ->limit($limit)
                ->offset($offset)
                ->asArray()->all();
            
            Yii::$app->response->content = json_encode($articles);
        } else {
            return Yii::$app->response->content = json_encode([
                "message" => "Can't find user"
            ]);
        }   
    }  
}