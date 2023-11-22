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
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::class, ['articleId' => 'id']);
    }
}