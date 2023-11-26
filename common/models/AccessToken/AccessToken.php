<?php

namespace common\models\AccessToken;

use common\models\User\User;

/**
 * This is the model class for table "accessToken".
 *
 * @property int $id
 * @property int $userId
 * @property string $token
 *
 * @property User $user
 */
class AccessToken extends BaseAccessToken
{

}