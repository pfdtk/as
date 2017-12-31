<?php

namespace app\authorize;

use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use yii\base\Component;

/**
 * Created by PhpStorm.
 * User: jiang
 * Date: 2017/12/29
 * Time: 18:45
 */
class OAuth2Client extends Component
{
    /**
     * @var string
     */
    public $clientId;

    /**
     * @var string
     */
    public $clientSecret;

    /**
     * @var string
     */
    public $redirectUri;

    /**
     * @var string
     */
    public $urlAuthorize;

    /**
     * @var string
     */
    public $urlAccessToken;

    /**
     * @var string
     */
    public $urlResourceOwnerDetails;

    /**
     * @var string
     */
    public $scope;

    /**
     * @var GenericProvider
     */
    private $provider;

    /**
     * init client
     */
    public function init()
    {
        $this->provider = new GenericProvider([
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'redirectUri' => $this->redirectUri,
            'urlAuthorize' => $this->urlAuthorize,
            'urlAccessToken' => $this->urlAccessToken,
            'urlResourceOwnerDetails' => $this->urlResourceOwnerDetails,
        ]);
    }

    /**
     * @return string
     */
    public function getAuthorizationUrl()
    {
        return $this->provider->getAuthorizationUrl(['scope' => $this->scope]);
    }

    /**
     * @param string $code
     * @return AccessToken
     */
    public function getAccessToken($code)
    {
        return $this->provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);
    }

    /**
     * @param AccessToken $accessToken
     * @return array
     */
    public function getResourceOwner(AccessToken $accessToken)
    {
        $resourceOwner = $this->provider->getResourceOwner($accessToken);
        return $resourceOwner->toArray();
    }

}