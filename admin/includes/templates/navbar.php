<nav class="navbar navbar-inverse">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="dashboard.php"><?php echo lang('Home-Admin'); ?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="nav navbar-nav">
                <li><a href="categories.php"><?php echo lang('Categories'); ?></a></li>
                <li><a href="items.php"><?php echo lang('Items'); ?></a></li>
                <li><a href="members.php"><?php echo lang('Members'); ?></a></li>
                <li><a href="comments.php"><?php echo lang('Comments'); ?></a></li>
                <li><a href="#"><?php echo lang('Statistics'); ?></a></li>
                <li><a href="#"><?php echo lang('Logs'); ?></a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="text-center"><a href="../index.php">WebSite</a></li>
                        <li class="text-center"><a href="members.php?do=edit&userid=<?php echo $_SESSION['userid'] ?>"><?php echo lang('Edit Profile'); ?></a></li>
                        <li class="text-center"><a href="#"><?php echo lang('Setting'); ?></a></li>
                        <li class="text-center"><a href="logout.php"><?php echo lang('Log out'); ?></a></li>

                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>