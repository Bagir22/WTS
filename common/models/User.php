<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 * @property int|null $isAdmin
 *
 * @property AccessToken $accessToken
 * @property Article[] $articles
 */
class User extends BaseUser
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status', 'created_at', 'updated_at', 'isAdmin'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
            'isAdmin' => 'Is Admin',
        ];
    }

    /**
     * Gets query for [[AccessToken]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAccessToken()
    {
        return $this->hasOne(AccessToken::class, ['userId' => 'id']);
    }

    /**
     * Gets query for [[Articles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::class, ['userId' => 'id']);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    
    /*
    public function getAccessToken($id) {
        $accessToken = (new \yii\db\Query())
            ->select('accessToken')
            ->from('accessToken')
            ->where(['userId' => $id])
            ->one(); 

        return $accessToken;
    }
    */    

    /**
     * Save user accessToken
     *
     * @param string $id
     * @return string|null
     */
    public function saveUserAccessToken($id) {
        $accessToken = new AccessToken(['user_id'=> $id]);
        $accessToken->token = Yii::$app->security->generateRandomString();
        $accessToken->save();

        return $accessToken->token;
    }

    /**
     * Find user by AccessToken
     *
     * @param string $token
     * @return User|null
     */
    public static function getUserByAccessToken($token) {
        $userId = (new \yii\db\Query())
            ->select('userId')
            ->from('accessToken')
            ->where(['token' => $token])
            ->one();
        
            return static::findOne(['id' => $userId, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Find accessToken by UserId
     *
     * @param string $id
     * @return string|null
     */
    public function getAccessTokenByUserID($id) {
        $userId = (new \yii\db\Query())
            ->select('token')
            ->from('accessToken')
            ->where(['userId' => $id])
            ->one();
        
        return $userId;
    }
}
