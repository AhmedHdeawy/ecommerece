<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php getTitleInHeader() ?></title>

    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $css; ?>frontend.css">
</head>
<body>
<div class="upper-bar">
    <div class="container text-right">

        <?php
        if(isset($_SESSION['user'])){ ?>

        <img src="item.jpg" class="img-responsive img-thumbnail img-circle img-user">
        <div class="btn-group dropdown-user">
            <span class="btn dropdown-toggle" data-toggle="dropdown">
                <?php echo $sessionUser;?>
                <span class="caret"></span>
            </span>
            <ul class="dropdown-menu">
                <li><a href="profile.php">My Profile</a> </li>
                <li><a href="newads.php">New Item</a> </li>
                <li><a href="logout.php">Logout</a> </li>
            </ul>
        </div>

            <?php
        }else{
            echo '<a href="login.php" class="pull-right">Login/Register</a>';
        }
        ?>

    </div>
</div>
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
                <a class="navbar-brand" href="index.php">Home</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                        $category = getAllFormTable('categories', 'ID');
                    foreach ($category as $cat){
                        echo "<li>";
                            echo "<a href='categories.php?pageid=" .$cat['ID']. "'>" .$cat['Name']. "</a>";
                        echo "</li>";
                    }
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>