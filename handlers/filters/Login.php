<?php

namespace app\handlers\filters;

use yii\base\ActionFilter;
use yii\web\UnauthorizedHttpException;

/**
 * Created by PhpStorm.
 * User: jiang
 * Date: 2018/1/11
 * Time: 14:53
 */
class Login extends ActionFilter
{
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        $isLogin = !\Yii::$app->user->getIsGuest();
        if (!$isLogin or !parent::beforeAction($action)) {
            return \Yii::$app->getResponse()->redirect(\Yii::$app->user->loginUrl);
        }
        return true;
    }

}