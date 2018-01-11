<?php

namespace app\modules\login\controllers;

use app\authorize\OAuth2Client;
use app\modules\login\services\Login;
use app\traits\JsonResponseTrait;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
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
     * @var Login
     */
    private $loginService;

    /**
     * IndexController constructor.
     * @param string $id
     * @param Module $module
     * @param array $config
     * @param Login $loginService
     */
    public function __construct(
        $id,
        Module $module,
        array $config = [],
        Login $loginService
    )
    {
        parent::__construct($id, $module, $config);
        $this->oauth2Client = \Yii::$app->get('oauth2_client');
        $this->loginService = $loginService;
    }

    /**
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $user = \Yii::$app->user;
        if ($user && $user->getIdentity()) {
            return $this->redirect('/index/index/index');
        }
        return $this->redirect($this->oauth2Client->getAuthorizationUrl());
    }

    /**
     * @return string
     */
    public function actionCallback()
    {
        $code = \Yii::$app->request->get('code');
        try {
            $accessToken = $this->oauth2Client->getAccessToken($code);
            \Yii::createObject('app\authorize\Token', [$accessToken])->save();
            $user = $this->oauth2Client->getResourceOwner($accessToken);
            if ($login = $this->loginService->signin($user)) {
                return $this->redirect('/login/index/index');
            }
            return 'Login fail.';
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