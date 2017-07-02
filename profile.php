<?php
ob_start();


session_start();
$titleHeader = "Profile";

include "init.php";

if($_SESSION['user']) {
?>

    <div class="information">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">Info</div>
                <div class="panel-body">
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM users WHERE Username = ?");
                    $stmt->execute(array($sessionUser));
                    $user = $stmt->fetch();
                    ?>
                    <ul class="list-unstyled list-group">
                        <li class="list-group-item">
                            <i class="fa fa-unlock-alt"></i>
                            <span>Username</span> : <?php echo $user['Username']?>
                        </li>

                        <li class="list-group-item">
                            <i class="fa fa-envelope-o"></i>
                            <span>Email</span> : <?php echo $user['Email']?>
                        </li>

                        <li class="list-group-item">
                            <i class="fa fa-user"></i>
                            <span>FullName</span> : <?php echo $user['FullName']?>
                        </li>

                        <li class="list-group-item">
                            <i class="fa fa-calendar"></i>
                            <span>Register Date</span> : <?php echo $user['Date']?>
                        </li>

                    </ul>
                    <a href="#" class="btn btn-default" style="margin: 5px 0 5px 20px;">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>

    <div class="my_ads">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">Ads</div>
                <div class="panel-body">
                    <?php
                    $items = getItems('Member_ID', $user['UserID'], null);
                    if(!empty($items)) {
                        foreach ($items as $item) {
                            echo "<div class='col-sm-6 col-md-3'>";
                            echo "<div class='thumbnail item_box'>";
                            echo "<div class='price_tag'>" . $item['Price'] . " $</div>";
                            echo "<img class='img-responsive' src='item.jpg' alt='Item' />";
                            echo "<div class='caption'>";
                            echo "<h3><a href='items.php?itemid=".$item['Item_ID']."'>" . $item['Name'] . "</a></h3>";
                            echo "<p class='desc-specific'>" . $item['Description'] . "</p>";
                            echo "<p class='date'>" . $item['Add_Date'] . "</p>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }else{
                        echo "There are no Ads for this user";
                        echo "<a class='btn btn-primary' href='newads.php'>New Ads</a>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="my_comment">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">Latest Comment</div>
                <div class="panel-body">
                    <?php
                    $stmt = $conn->prepare("SELECT comment FROM comments WHERE User_ID = ?");
                    $stmt->execute(array($user['UserID']));
                    $rows = $stmt->fetchAll();

                    if(!empty($rows)){

                        foreach ($rows as $comment){
                            echo $comment['comment'] . "<br>";
                        }
                    }else{
                        echo "There are no Comments for this user";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
}else{
    header('Location:login.php');
    exit();
}
include $template . "footer.php";
ob_end_flush();

?>