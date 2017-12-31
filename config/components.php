<?php

return [
    'oauth2_client' => [
        'class' => \app\authorize\OAuth2Client::class,
        'clientId' => 'client1',
        'clientSecret' => 'client1_secret',
        'redirectUri' => 'http://sysyii.jhj.com/login/callback',
        'urlAuthorize' => 'http://sysyii_oauth.jhj.com/authorize',
        'urlAccessToken' => 'http://sysyii_oauth.jhj.com/authorize/access_token',
        'urlResourceOwnerDetails' => 'http://sysyii_oauth.jhj.com/user/info',
        'scope' => 'scope1',
    ],
];
