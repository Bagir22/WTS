<?php

namespace common\models;


/**
 * This is the model class for table "Article".
 *
 * @property int $id
 * @property string $title
 * @property string|null $body
 * @property int $userId
 *
 * @property User $user
 */
class Article extends BaseArticle
{ 
    public function makePublish()
    {
        /*
        $article->userId = $userId;
        $article->title = $title;
        $article->body = $body;

        return $model->save();
        */

        //var_dump($this->getAttributes()['title']);

        if ($this->save()) {
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