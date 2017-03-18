<?php
namespace FacebookPageWrapper;
if(!session_status() == PHP_SESSION_ACTIVE){
    session_start();
}
require "config/config.php";
require_once('classes/Page.php');
if(!isset($_GET['page_id'])) exit("Invalid page access");

if(isset($_POST['btnSubmit'])){
    $page = new Page(base64_decode($_GET['page_id']));
    $message = htmlspecialchars($_POST['message']);
    $published = isset($_POST['published']);
    $publish_time = 0;
    if(strtotime($_POST['scheduled_publish_time']) != null){
        $publish_time = strtotime($_POST['scheduled_publish_time']);
    }
    $page->createPagePost($message,$published,$publish_time);
    header('location:index.php?page_id=' . $_GET['page_id']);
}

?>
<?php require 'partials/application_head.php'; ?>
    <form action="<?= $_SERVER['PHP_SELF'] . '?page_id=' . $_GET['page_id']; ?>" method="post">
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea class="form-control" id="message" name="message" required></textarea>
        </div>

        <div class="checkbox">
            <label><input type="checkbox" name="published" checked> Published?</label>
        </div>
        <div class="form-group">
            <label for="scheduleTime">Sheduled Post Date:</label>
            <input type="date" name="scheduled_publish_time">
        </div>
        <button type="submit" class="btn btn-default" name="btnSubmit">Submit</button>
    </form>
<?php require 'partials/application_footer.php'; ?>