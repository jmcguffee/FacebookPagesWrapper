<?php
namespace FacebookDemoApp;
if(!session_status() == PHP_SESSION_ACTIVE){
    session_start();
}
require "config/config.php";
require_once('classes/Page.php');
if(!isset($_GET['post_id'])) exit("Invalid page access");
$post = new PagePost(base64_decode($_GET['post_id']),$_SESSION['facebook_access_token']);
$post->loadPagePost();
$checked = $post->getIsPublished() ? 'checked disabled' : '';
if(isset($_POST['btnSubmit'])){
    $page_id = explode('_',$post->getId());
    $page_id = $page_id[0];
    $page = new Page($page_id);
    $post->setAccessToken($page->getAccessToken());
    $message = htmlspecialchars($_POST['message']);
    $published = isset($_POST['published']);
    $post->setMessage($message);
    $needs_published = false;
    if(!$post->getIsPublished() && $published){
        $post->setIsPublished($published);
        $needs_published = true;
    }
    $post->save($needs_published);
    header('location:index.php?page_id=' . base64_encode($page_id));
}

?>
<?php require 'partials/application_head.php'; ?>
    <form action="<?= $_SERVER['PHP_SELF'] . '?post_id=' . $_GET['post_id']; ?>" method="post">
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea class="form-control" id="message" name="message" required><?= $post->getMessage(); ?></textarea>
        </div>

        <div class="checkbox">
            <label><input type="checkbox" name="published" <?= $checked; ?>> Published?</label>
        </div>
        <button type="submit" class="btn btn-default" name="btnSubmit">Submit</button>
    </form>
<?php require 'partials/application_footer.php'; ?>