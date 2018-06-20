<?php
/**
 * Created by PhpStorm.
 * User: brian-kamau
 * Date: 20/06/18
 * Time: 20:38
 */

namespace App\Http\Controllers;


use App\TokenStore\TokenCache;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use TheSeer\Tokenizer\Token;

class OutlookController
{
    public function mail(){
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
        try{
            $tokenCache  = new TokenCache();
            $graph = new Graph();
            $graph->setAccessToken($tokenCache->getAccessToken());
            $user = $graph->createRequest('GET','/me')
                ->setReturnType(Model\User::class)->execute();
            echo 'User:' .$user->getDisplayName().'<br/>';
            $messageQueryParams = array(
                "\$select" => "subject,receivedDateTime,from",
                "\$orderby" => "receivedDateTime DESC",
                "\$top" => 10
            );
            $getMessageUrl = '/me/mailfolders/inbox/messages?'.http_build_query($messageQueryParams);
            $messages = $graph->createRequest("GET",$getMessageUrl)->setReturnType(Model\Message::class)->execute();
            foreach ($messages as $msg){
                echo 'Message'.$msg->getSubject().'<br/>';
            }
        }catch (GraphException $ex){
            echo 'Exception: '.$ex->getMessage();
        }

    }

}