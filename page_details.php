<?php
    require_once "classes/Page.php";
    require_once "classes/PagePost.php";

    $page = new \FacebookPageWrapper\Page(base64_decode($_GET['page_id']));

?>

    <b><span style="font-size: 40px">Details: <?= $page->getName(); ?></span></b>
    <?php if($page->getPublishedPosts() || $page->getUnpublishedPosts()): ?>
        <div id="posts">
            <h4>Published Posts</h4>
            <?php if(count($page->getPublishedPosts()) == 0) { echo '<h4>No Published Posts</h4>'; } ?>
            <?php /** @var $post \FacebookPageWrapper\PagePost */ ?>
            <?php foreach($page->getPublishedPosts() as $post): ?>
                <?= $post->getPostCardHtml(); ?>
            <?php endforeach; ?>
            <h4>Unpublished Posts</h4>
            <?php if(count($page->getUnpublishedPosts()) == 0) { echo '<h4>No Unpublished Posts</h4>'; } ?>
            <?php foreach($page->getUnpublishedPosts() as $post): ?>
                <?= $post->getPostCardHtml(); ?>
            <?php endforeach; ?>
    <?php else: ?>
        <h4>No Posts</h4>
    <?php endif; ?>
        </div>