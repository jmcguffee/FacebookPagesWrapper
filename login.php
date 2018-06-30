<?php
use FacebookPageWrapper\Configuration;
use Facebook\Facebook;

require "config/config.php";
$fb = new Facebook(Configuration::$facebook_config);

$helper = $fb->getRedirectLoginHelper();
try {
    $accessToken = $helper->getAccessToken();
	echo $accessToken ? $accessToken : 'Inavlid';
} catch(\Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (isset($accessToken)) {
    $_SESSION['facebook_access_token'] = (string) $accessToken;
    header('location:' . BASE_URL);
} elseif ($helper->getError()) {
    echo 'User denied requests';
    exit;
}
