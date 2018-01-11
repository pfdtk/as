<?php


namespace app\modules\login\services;

use app\models\User;

/**
 * Class Login
 * @author jiang
 * @package app\modules\login\services
 */
class Login
{
    /**
     * @param array $user
     * @return bool
     */
    public function signin($user)
    {
        $userId = isset($user['userid']) ? $user['userid'] : 0;
        if (!$user) return false;
        /** @var User $userIdentify */
        $userIdentify = User::findOne(['id' => intval($userId)]);
        return \Yii::$app->user->login($userIdentify);
    }
    
}