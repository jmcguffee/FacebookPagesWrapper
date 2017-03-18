<?php

use Facebook\Facebook;
require "config/config.php";
$facebook = new Facebook(FacebookPageWrapper\Configuration::$facebook_config);

if(!isset($_SESSION['facebook_access_token'])){

    $handler = $facebook->getRedirectLoginHelper();
    $permissions = ['manage_pages','publish_actions','read_insights','read_audience_network_insights','pages_show_list','publish_pages'];
    $loginUrl = $handler->getLoginUrl("http://home.me.com/FacebookApp/login.php",$permissions);
}
else{
    $facebook->setDefaultAccessToken($_SESSION['facebook_access_token']);
    try{
        $response = $facebook->get('/me/accounts');
        $pages = json_decode($response->getBody());
    }
    catch(\Facebook\Exceptions\FacebookResponseException $e){
        echo $e->getMessage();
        exit;
    }
    catch(\Facebook\Exceptions\FacebookSDKException $e){
        echo $e->getMessage();
        exit;
    }
}
?>
<?php require 'partials/application_head.php'; ?>
        <?php if(!isset($_GET['page_id']) && (isset($_SESSION['facebook_access_token']) && ['facebook_access_token'] != null)): ?>
            <h2>Select a page to load information</h2>
        <?php elseif(isset($_GET['page_id'])): ?>
            <?php include dirname(__FILE__) . DS . 'page_details.php'; ?>
        <?php endif; ?>
<?php require_once 'partials/application_footer.php'; ?>
