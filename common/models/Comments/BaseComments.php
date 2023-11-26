<?php

namespace common\models\Comments;

use common\models\Article\Article;
use common\models\User\User;

/**
 * This is the model class for table "BaseComments".
 *
 * @property int $id
 * @property int $userId
 * @property int $articleId
 * @property string $body
 */
class BaseComments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'articleId', 'body'], 'required'],
            [['userId', 'articleId'], 'integer'],
            [['body'], 'string'],
            [['articleId'], 'exist', 'skipOnError' => true, 'targetClass' => Article::class, 'targetAttribute' => ['articleId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'articleId' => 'Article ID',
            'body' => 'Body',
        ];
    }
}
