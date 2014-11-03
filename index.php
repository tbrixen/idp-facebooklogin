<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
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


    // Get the user object
    try {
        // Get user profile
        $user_profile = (new FacebookRequest(
            $session, 'GET', '/me'
        ))->execute()->getGraphObject(GraphUser::className());
    } catch(FacebookRequestException $e) {

        echo "Exception occured, code: " . $e->getCode();
        echo " with message: " . $e->getMessage();
    }   

    // Retreive the info
    $gender = $user_profile->getProperty('gender');
    $locale = $user_profile->getProperty('locale');

    echo "Hello " . $user_profile->getName() . "<br />";
    echo "You are a " . $gender. " with locale ";
    echo "locale: " . $locale . "<br />";


    if ($gender == "male" && $locale == "da_DK"){
        echo "You are male and from Denmark. You have access to this page";
    } else {
        echo "Sorry. To access this page, you need to be both male and originate from denmark";
    }
          
    echo '<br /><br /><a href="logout.php">Log out</a>';

} else {
    // There were no session

    echo '<a href="' . $helper->getLoginUrl() . '">Login with Facebook</a>';
}



?>
