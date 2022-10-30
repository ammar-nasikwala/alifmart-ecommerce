<?php
session_start();
include_once("src/Google_Client.php");
include_once("src/contrib/Google_Oauth2Service.php");
######### edit details ############

global $env_prod;
      if($env_prod===true)//This is for production...
      {    
        $clientId = 'google client id'; //Google CLIENT ID
        $clientSecret = 'Your secret'; //Google CLIENT SECRET
        $redirectUrl = "http://".$_SERVER["SERVER_NAME"]."/google_login/";  //return url (url to script)
        $homeUrl ="http://".$_SERVER["SERVER_NAME"]."/";  //return to home
      }
      else// this is for test...
      {
        $clientId = 'google client id'; //Google CLIENT ID
        $clientSecret = 'Your secret'; //Google CLIENT SECRET
        $redirectUrl = "http://".$_SERVER["SERVER_NAME"]."/google_login/";  //return url (url to script)
        $homeUrl ="http://".$_SERVER["SERVER_NAME"]."/";  //return to home
      }
        
$gClient = new Google_Client();
$gClient->setApplicationName('Login');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectUrl);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>