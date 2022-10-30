
<?php
session_start();
// added in v4.0.0
require_once 'autoload.php';
//require("../lib/inc_connection.php");
require ("functions.php"); 

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
// init app with app id and secret

global $env_prod;
      if($env_prod===true)//This is for production...
      {  
        FacebookSession::setDefaultApplication( '','' );
// login helper with redirect_uri
        $helper = new FacebookRedirectLoginHelper("http://".$_SERVER["SERVER_NAME"]."/facebook_login/fbconfig.php" );
      }
        else// this is for test...
        {
        FacebookSession::setDefaultApplication( '','' );
// login helper with redirect_uri
        $helper = new FacebookRedirectLoginHelper("http://".$_SERVER["SERVER_NAME"]."/facebook_login/fbconfig.php" );
        }

$permissions = array(
        scope =>'email'
                
    );
    

try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}
// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest(
  $session,
  'GET',
  '/me',
  array(
    'fields' => 'id,first_name,last_name,email'
  )
);

$response = $request->execute();

 $graphObject = $response->getGraphObject(GraphUser::className());
     	$fbid = $graphObject->getId();              // To Get Facebook ID
 	    $fbname = $graphObject->getFirstName(); 
       $fblastname = $graphObject->getLastName();// To Get Facebook full name
	    $femail = $graphObject->getEmail();    // To Get Facebook email ID
	/* ---- Session Variables -----*/
	              
      $_SESSION['uid'] =  $fbid;
	    $_SESSION['EMAIL'] =  $femail;
         checkuser($fbid,$fbname,$fblastname,$femail);
         
    /* ---- header location after session ----*/
   
   header("Location: index.php");
} else {
  $loginUrl = $helper->getLoginUrl($permissions);
 header('Location: ' . filter_var($loginUrl, FILTER_SANITIZE_URL));
}
?>