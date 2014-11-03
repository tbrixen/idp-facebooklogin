<?php
session_start();


require_once __DIR__ . '/vendor/autoload.php';
require_once('SetFacebookSession.php');

use Facebook\FacebookRedirectLoginHelper;
use Facebook\Facebookrequest;
use Facebook\FacebookSession;
use Facebook\GraphUser;

// Create the helper for redirection 
$redirect_url = "http://localhost/idp-facebooklogin/index.php";

$helper = new FacebookRedirectLoginHelper($redirect_url);

// Check if a session already exists
if (isset( $_SESSION ) && isset($_SESSION['fb_token']) ) {
    $session = new FacebookSession( $_SESSION['fb_token'] ) ;

    // See if it's still valid
    try {
        if (!$session->validate()){
            $session = null;
        }     
    } catch (Exception $e) {
        echo $e;
        $session = null;
    }
} else {
    // Then no session exists
    try {
        $session = $helper->getSessionFromRedirect();
    } catch(FacebookRequestException $e) {
        // When Facebook returns an error
        echo $e;
    } catch(\Exception $e) {
        // When validation fails or other local issues
        echo $e;
    }
}



if ( isset($session) ) {
    // Save the session
    $_SESSION['fb_token'] = $session->getToken();

    // Create a session using the saved token
    $session = new FacebookSession($session->getToken());

    $logoutURL = $helper->getLogoutUrl( $session, 'http://localhost/idp-facebooklogin/logout.php');

    echo '<a href="' . $logoutURL . '">Log out</a>';

} else {
    // There were no session

    echo '<a href="' . $helper->getLoginUrl() . '">Login with Facebook</a>';
}

//    try {
//        $user_profile = (new FacebookRequest(
//            $session, 'GET', '/me'
//        ))->execute()->getGraphObject(GraphUser::className());
//
//        echo "Name: " . $user_profile->getName();
//
//          
//
//        echo print_r($user_profile, 1);
//
//    } catch(FacebookRequestException $e) {
//
//        echo "Exception occured, code: " . $e->getCode();
//        echo " with message: " . $e->getMessage();
//
//    }   
//
//} else {
//}


?>
