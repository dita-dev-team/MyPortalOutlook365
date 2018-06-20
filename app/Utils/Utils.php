<?php
/**
 * Created by PhpStorm.
 * User: brian-kamau
 * Date: 20/06/18
 * Time: 20:03
 */

namespace App\Utils;


class Utils
{
    public function fetchCredentials(){
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => env('OAUTH_APP_ID'),
            'clientSecret' => env('OAUTH_APP_PASSWORD'),
            'redirectUrl' => env('OAUTH_REDIRECT_URI'),
            'urlAuthorize' => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
            'urlAccessToken' => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
            'urlResourceOwnerDetails' =>'',
            'scopes' => env('OAUTH_SCOPES')
        ]);
        return $oauthClient;
    }

}