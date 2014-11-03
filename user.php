<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once('SetFacebookSession.php');

use Facebook\FacebookRedirectLoginHelper;


$helper = new FacebookRedirectLoginHelper();
try {
  $session = $helper->getSessionFromRedirect();
} catch(FacebookRequestException $ex) {
    // When Facebook returns an error
    echo "Facebook returned an error";
    echo $ex;
} catch(\Exception $ex) {
    // When validation fails or other local issues
    echo "validation failed or other local issues";
}
if ($session) {
    // Loged in
    echo "You are logged in"
}

?>
