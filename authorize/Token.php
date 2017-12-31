<?php

namespace app\authorize;

use League\OAuth2\Client\Token\AccessToken as ResponseAccessToken;

/**
 * Created by PhpStorm.
 * User: jiang
 * Date: 2017/12/30
 * Time: 16:23
 */
class Token
{
    /**
     * @var string
     */
    const ACCESS_TOKEN_SESSION_KEY = 'ACCESS_TOKEN_SESSION_KEY';

    /**
     * @var int
     */
    const AHEAD_EXPIRES_TIME = 100;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var int
     */
    private $expiresTime;

    /**
     * @var ResponseAccessToken
     */
    private $token;

    /**
     * AccessToken constructor.
     * @param ResponseAccessToken $token
     */
    public function __construct(ResponseAccessToken $token = null)
    {
        \Yii::trace($token);
        $this->token = $token;
    }

    /**
     * @return void
     */
    public function save()
    {
        $this->accessToken = $this->token->getToken();
        $this->refreshToken = $this->token->getRefreshToken();
        $this->expiresTime = $this->token->getExpires();
        \Yii::$app->session->set(static::ACCESS_TOKEN_SESSION_KEY, [
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_time' => $this->expiresTime,
        ]);
    }

    /**
     * @return $this
     * @throws AccessTokenRestoreException
     */
    public function restore()
    {
        $token = \Yii::$app->session->get(static::ACCESS_TOKEN_SESSION_KEY);
        if (!$token) {
            throw new AccessTokenRestoreException('Fail to restore access token.');
        }
        $this->accessToken = $token['access_token'];
        $this->refreshToken = $token['refresh_token'];
        $this->expiresTime = $token['expires_time'];
        return $this;
    }

    /**
     * @return string
     * @throws AccessTokenRestoreException
     */
    public function getAccessToken()
    {
        if (!$this->accessToken) {
            $this->restore();
        }
        return $this->accessToken;
    }

    /**
     * @return string
     * @throws AccessTokenRestoreException
     */
    public function getRefreshToken()
    {
        if (!$this->refreshToken) {
            $this->restore();
        }
        return $this->refreshToken;
    }

    /**
     * @return bool
     */
    public function hasExpired()
    {
        return $this->expiresTime < time() - static::AHEAD_EXPIRES_TIME;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        if (!in_array($name, ['getAccessToken', 'getRefreshToken'])) {
            throw new \BadMethodCallException('No such method: ' . $name);
        }
        return call_user_func_array(array(new static(), $name), $arguments);
    }

}