<?php
namespace FacebookPageWrapper;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

require_once dirname(__FILE__) . "/../config/config.php";
require_once dirname(__FILE__) . "/PagePost.php";
class Page
{
    private $id;
    private $access_token = null;
    private $name;

    private $facebook;

    private $unpublished_posts = [];
    private $published_posts = [];
    
    public function __construct($id)
    {
        $this->id =  htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
        if($this->id != null){
            $this->facebook = new Facebook(Configuration::$facebook_config);
            $this->facebook->setDefaultAccessToken($_SESSION['facebook_access_token']);
            $this->loadPageDetails();
        }
        else{
            throw new \Exception("You are not allowed to load a page without an ID");
        }

    }

    public function loadPageDetails(){

        try{
            $response = $this->facebook->get('/' . $this->id . '?fields=name,access_token');
            $page_details = json_decode($response->getBody());
            $this->name = $page_details->name;
            $this->access_token = $page_details->access_token;
            $response = $this->facebook->get('/' . $this->id . '/promotable_posts?fields=id,message,created_time,admin_creator,is_published',$this->access_token);
            $tmpPosts = json_decode($response->getBody());
            foreach($tmpPosts->data as $fbPost){
                if(!isset($fbPost->message)){
                    continue;
                }
                $post = new PagePost($fbPost->id,$this->access_token);
                $post->setCreatedTime($fbPost->created_time);
                $post->setAdminCreator(isset($fbPost->admin_creator) ? $fbPost->admin_creator : null);
                $post->setIsPublished($fbPost->is_published);
                $post->setMessage(isset($fbPost->message) ? $fbPost->message : "");
                if($post->getIsPublished()){
                    $this->published_posts[] = $post;
                }
                else{
                    $this->unpublished_posts[] = $post;
                }
            }

        }
        catch(FacebookResponseException $e){
            echo $e->getMessage();
            exit;
        }
        catch(FacebookSDKException $e){
            echo $e->getMessage();
            exit;
        }


    }

    public function createPagePost($message, $published=true,$scheduled_publish_time = 0){

        $post = new PagePost(null,$this->access_token);
        $post->setMessage($message);
        $post->setIsPublished($published);
        $post->setPageId($this->id);
        $post->setScheduledPublishTime($scheduled_publish_time);
        $post->create();

    }

    public function getName(){
        return $this->name;
    }

    public function getPublishedPosts(){
        return $this->published_posts;
    }
    
    public function getUnpublishedPosts(){
        return $this->unpublished_posts;
    }

    public function getAccessToken(){
        return $this->access_token;
    }
}