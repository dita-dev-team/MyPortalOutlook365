<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use App\Utils\Utils;

class AuthController extends Controller
{


    public function signin(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        //We then Initialize OAuth Client
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
           'clientId' => env('OAUTH_APP_ID'),
           'clientSecret' => env('OAUTH_APP_PASSWORD'),
           'redirectUrl' => env('OAUTH_REDIRECT_URI'),
            'urlAuthorize' => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
            'urlAccessToken' => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
            'urlResourceOwnerDetails' =>'',
            'scopes' => env('OAUTH_SCOPES')
        ]);
       $authorizeUrl = $oauthClient->getAuthorizationUrl();
        $_SESSION['oauth_state'] = $oauthClient->getState();

        header('Location: '.$authorizeUrl);
      // echo 'Auth URL: '.$oauthClient->getAuthorizationUrl();
        exit();
    }
    public function getToken(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        if(isset($_GET['code'])){
            if(empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth_state'])){
                exit('State provided in redirect does not match expected value');
            }
            unset($_SESSION['oauth_state']);

            $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId' => env('OAUTH_APP_ID'),
                'clientSecret' => env('OAUTH_APP_PASSWORD'),
                'redirectUrl' => env('OAUTH_REDIRECT_URI'),
                'urlAuthorize' => env('OAUTH_AUTHORITY').env('OAUTH_AUTHORIZE_ENDPOINT'),
                'urlAccessToken' => env('OAUTH_AUTHORITY').env('OAUTH_TOKEN_ENDPOINT'),
                'urlResourceOwnerDetails' =>'',
                'scopes' => env('OAUTH_SCOPES')
            ]);

            try {
                $utilsController = new Utils();
                $accessToken = $utilsController->fetchCredentials()->getAccessToken('authorization_code',[
                   'code'=>$_GET['code']
                ]);

                /*$accessToken = $oauthClient->getAccessToken('authorization_code',[
                   'code'=>$_GET['code']
                ]);*/
                echo 'Access token: '.$accessToken->getToken();
            }catch(IdentityProviderException $ex){
                exit('Error Getting Tokens: '.$ex->getMessage());
            }
            exit();

        }elseif(isset($_GET['error'])){
            exit('Error: '.$_GET['error'].'-'.$_GET['error_description']);
        }

    }
}
