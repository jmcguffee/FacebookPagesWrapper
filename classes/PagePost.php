<?php
namespace FacebookPageWrapper;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class PagePost
{
    private $id;
    private $access_token;
    private $message;
    private $created_time;
    private $admin_creator;
    private $is_published;
    private $scheduled_publish_time;
    private $page_id;

    /** @var  $facebook Facebook */
    private $facebook;
    
    public function __construct($id,$access_token){
        $this->id = $id;
        $this->access_token = $access_token;
    }

    public function checkFacebookLoader(){
        if($this->access_token == null){
            $this->access_token = $_SESSION['facebook_access_token'];
        }
        if($this->facebook == null){
            $this->facebook = new Facebook(Configuration::$facebook_config);
            $this->facebook->setDefaultAccessToken($this->access_token);
        }
    }

    public function loadPagePost(){
        $this->checkFacebookLoader();
        $response = $this->facebook->get('/' . $this->id . '?fields=id,message,created_time,admin_creator,is_published',$this->access_token);
        $fbPost = json_decode($response->getBody());
        $this->created_time = $fbPost->created_time;
        $this->admin_creator = isset($fbPost->admin_creator) ? $fbPost->admin_creator : null;
        $this->is_published = $fbPost->is_published;
        $this->message = isset($fbPost->message) ? $fbPost->message : "";
    }

    public function getViews(){
        $this->checkFacebookLoader();
        $response = $this->facebook->get('/' . $this->id . '/insights/post_impressions_unique/lifetime',$this->access_token);
        $dataArr = json_decode($response->getBody())->data[0]->values;
        $views = 0;
        if($dataArr){
            foreach($dataArr as $value){
                $views += $value->value;
            }
        }
        return $views;
    }

    public function getPostCardHtml(){
        $html = "<div class=\"jumbotron\">";
        $html .= "<div><a href='edit_post.php?post_id=" . base64_encode($this->id) ."'>Edit Post</a></div>";
        $html .= "<div><b>Created:</b>" . date('m/d/Y h:i:s A',strtotime($this->created_time)) . "</div>";
        if($this->admin_creator != null) {
            $html .= "<div >
                        <b > Created By:</b >
                        <a href = \"{$this->admin_creator->link}\" > {$this->admin_creator->name} </a >
                      </div >";
        }
        $html .= "<div><b>Published:</b>" . ($this->is_published ? 'Yes' : 'No') . "</div>
                    <b>Post Views: </b>{$this->getViews()}";

        if($this->hasHtml($this->message)){
            $html .= "<div><b>Message: </b><pre style='border: 0; background-color: transparent;'>" . htmlentities($this->message) . "</pre></div>";
        }
        else{
            $html .= "<div><b>Message: </b>" . $this->message . "</div>";
        }

        $html .= "</div>";

        return $html;
    }
    
    public function create(){
        $this->checkFacebookLoader();
        $postArray = [
            'message' => $this->message
        ];
        if(!$this->is_published){
            $postArray['published'] = false;
            if($this->scheduled_publish_time > 0) {
                $postArray['scheduled_publish_time'] = $this->scheduled_publish_time;
            }
        }


        try{
            $this->facebook->post(
                '/' . $this->page_id . '/feed',
                $postArray,
                $this->access_token
            );
        }
        catch(FacebookResponseException $e){
            exit($e->getMessage());
        }
        catch(FacebookSDKException $e){
            exit($e->getMessage());
        }
    }
    
    public function save($needs_published){
        $this->checkFacebookLoader();
        $postArray = [
            'message' => $this->message,
        ];
        
        if($needs_published){
            $postArray['is_published'] = true;
        }

        try{
            $this->facebook->post(
                '/' . $this->id,
                $postArray,
                $this->access_token
            );
        }
        catch(FacebookResponseException $e){
            exit($e->getMessage());
        }
        catch(FacebookSDKException $e){
            exit($e->getMessage());
        }
    }
    
    
    public function publish(){
        
    }
    

    function hasHtml($string)
    {
        if ( $string != strip_tags($string) )
        {
            return true;
        }
        return false;
    }
    

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param mixed $access_token
     */
    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        /*if(strlen($message) > 200){
            $message = substr($message,0,200) . '...';
        }*/
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getCreatedTime()
    {
        return $this->created_time;
    }

    /**
     * @param mixed $created_time
     */
    public function setCreatedTime($created_time)
    {
        $this->created_time = $created_time;
    }

    /**
     * @return mixed
     */
    public function getAdminCreator()
    {
        return $this->admin_creator;
    }

    /**
     * @param mixed $admin_creator
     */
    public function setAdminCreator($admin_creator)
    {
        $this->admin_creator = $admin_creator;
    }

    /**
     * @return mixed
     */
    public function getIsPublished()
    {
        return $this->is_published;
    }

    /**
     * @param mixed $is_published
     */
    public function setIsPublished($is_published)
    {
        $this->is_published = $is_published;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @param mixed $page_id
     */
    public function setPageId($page_id)
    {
        $this->page_id = $page_id;
    }

    /**
     * @return mixed
     */
    public function getScheduledPublishTime()
    {
        return $this->scheduled_publish_time;
    }

    /**
     * @param mixed $scheduled_publish_time
     */
    public function setScheduledPublishTime($scheduled_publish_time)
    {
        $this->scheduled_publish_time = $scheduled_publish_time;
    }

    
    
}