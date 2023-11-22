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
    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::class, ['articleId' => 'id']);
    }
}