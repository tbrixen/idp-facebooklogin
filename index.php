<?php
session_start();


require_once __DIR__ . '/vendor/autoload.php';
require_once('SetFacebookSession.php');

use Facebook\FacebookRedirectLoginHelper;

$redirect_url = "http://localhost/idp-facebooklogin/user.php";

$helper = new FacebookRedirectLoginHelper($redirect_url);
echo '<a href="' . $helper->getLoginUrl() . '">Login with Facebook</a>';


?>
