<?php

namespace app\modules\login\controllers;

use app\authorize\OAuth2Client;
use app\traits\JsonResponseTrait;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use yii\base\Module;
use yii\web\Controller;
use app\authorize\Token;

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
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $user = \Yii::$app->user;
        if ($user && $user->getIdentity()) {
            return $this->redirect('index/index/index');
        }
        return $this->redirect($this->oauth2Client->getAuthorizationUrl());
    }

    /**
     * @throws \app\authorize\AccessTokenRestoreException
     */
    public function actionTest()
    {
        $token = new Token();
        $accessToken = $token->getAccessToken();
        $refreshToken = $token->getRefreshToken();
        return sprintf('AccessToken: %s, RefreshToken: %s', $accessToken, $refreshToken);
    }

    /**
     * @return string
     */
    public function actionCallback()
    {
        $code = \Yii::$app->request->get('code');
        try {
            $accessToken = $this->oauth2Client->getAccessToken($code);
            $store = new Token($accessToken);
            $store->save();
            $this->redirect('/login/index/test');
        } catch (IdentityProviderException $exception) {
            $response = $exception->getResponseBody();
            $reason = isset($response['message']) ? $response['message'] : 'Invalid request';
            $hint = isset($response['hint']) ? $response['hint'] : 'Unkown';
            return sprintf('Login server exception: %s, hint: %s', $reason, $hint);
        } catch (\Exception $exception) {
            \Yii::error($exception);
        }
        return 'Unkown exception.';
    }

}