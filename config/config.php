<?php
namespace FacebookPageWrapper;
session_start();
date_default_timezone_set('America/Chicago');
define("BASE_URL",'http://home.me.com/FacebookApp');
define("DS",DIRECTORY_SEPARATOR);
define("BASE_PATH",dirname(__FILE__) . DS . ".." . DS);
require_once BASE_PATH . "vendor/autoload.php";

class Configuration{
    public static $facebook_config = array(
                'app_id' => "<your app id>",
                'app_secret' => '< your app secret >',
                'default_graph_version' => 'v2.8'
    );

    private $base_dir;
    public function __construct()
    {
        $this->base_dir = dirname(__FILE__) . "/../";
    }


    public function getBaseDirectory(){
        return $this->base_dir;
    }
}
