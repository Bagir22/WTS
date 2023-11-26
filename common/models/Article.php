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
 * @property Comments[] $comments
 */
class Article extends BaseArticle
{
    public function saveArticle() {
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
    public function shortSerialize() {

        return [
            "id" => $this["id"],
            "title" => $this["title"],
        ];
    }

    public function longSerialize() {
        return [
            "id" => $this["id"],
            "userId" => $this["userId"],
            "title" => $this["title"],
            "body" => $this["body"],
            "comments" => Comments::findAll(['articleId' => $this["id"]])
        ];
    }
}