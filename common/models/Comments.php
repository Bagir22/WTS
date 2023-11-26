<?php

namespace common\models;

/**
 * This is the model class for table "Comments".
 *
 * @property int $id
 * @property int $userId
 * @property int $articleId
 * @property string $body
 */

class Comments extends BaseComments
{
    /**
     * Save comment
     *
     * @return string|array
     */
    public function saveComment() {
        if ($this->save()) {
            return [
                'message' => "Success save comment"
            ];
        } else {
            return [
                "message" => "Can't save comment",
                "error" => $this->getErrors(),
            ];
        }
    }

    /**
     * Delete comment
     *
     * @return string|array
     */
    public function deleteComment() {
        if ($this->delete()) {
            return [
                'message' => "Success delete comment"
            ];
        } else {
            return [
                "message" => "Can't delete comment",
                "error" => $this->getErrors(),
            ];
        }
    }

    public function shortSerialize() {

        return [
            "userId" => $this["userId"],
            "body" => $this["body"],
        ];
    }

    public function longSerialize($comment) {
        return [
            "commentId" => $comment["id"],
            "userId" => $comment["userId"],
            "articleId" => $comment["articleId"],
            "body" => $comment["body"],
        ];
    }
}