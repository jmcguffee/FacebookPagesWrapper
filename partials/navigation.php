<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Facebook Page Manager</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="<?= BASE_URL ?>">Home</a></li>
                <?php if(isset($pages)): ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Pages <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php foreach($pages->data as $page): ?>
                                <li><a href="<?= BASE_URL . '?page_id=' . base64_encode($page->id)?>"><?= $page->name ?></a></li>
                            <?php endforeach; ?>

                        </ul>
                    </li>
                <?php endif; ?>

            </ul>
            <?php if(isset($_GET['page_id'])): ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="add_post.php<?= '?page_id=' . $_GET['page_id'] ?>">Add Post</a></li>
                </ul>
            <?php elseif(isset($_SESSION['facebook_access_token'])): ?>

            <?php else: ?>
                <ul class="nav navbar-nav navbar-right">
                    <li><?php echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>'; ?></li>
                </ul>
            <?php endif; ?>

        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>
