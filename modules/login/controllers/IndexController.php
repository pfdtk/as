<?php

namespace app\modules\login\controllers;

use app\authorize\OAuth2Client;
use app\traits\JsonResponseTrait;
use yii\base\Module;
use yii\web\Controller;

/**
 * Class IndexController
 * @author jiang
 * @package app\modules\login\controllers
 */
class IndexController extends Controller
{
    use JsonResponseTrait;

    /**
     * @var OAuth2Client
     */
    private $oauth2Client;

    /**
     * IndexController constructor.
     * @param $id
     * @param Module $module
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->oauth2Client = \Yii::$app->get('oauth2_client');
    }

    /**
     *
     */
    public function actionIndex()
    {
        return $this->redirect($this->oauth2Client->getAuthorizationUrl());
    }

    /**
     *
     */
    public function actionCallback()
    {
        echo 1;
    }

}