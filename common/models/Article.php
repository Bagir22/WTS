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
    public function makePublish($userId, $title, $body) {
        $article = new Article();
        $article->userId = $userId;
        $article->title = $title;
        $article->body = $body;
        $article->save();
    }
}