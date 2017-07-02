<?php

ob_start();
session_start();
$titleHeader = "Items";

include "init.php";

    $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

    $stmt =$conn->prepare("SELECT items.*,
                           categories.Name AS cat_name,
                           users.Username AS username
                          FROM items 
                          INNER JOIN categories ON categories.ID = items.Cat_ID
                          INNER JOIN users ON users.UserID = items.Member_ID
                          WHERE Item_ID = ? LIMIT 1");
    $stmt->execute(array($itemid));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    // if There is ID
    if($count > 0) {
        ?>

        <div class="container items-page">
            <h1 class="text-center"><?php echo $row['Name'];?></h1>
            <div class="row">
                <div class="col-md-3">
                    <?php
                    echo "<img class='img-responsive img-thumbnail center-block' src='item.jpg' alt='Item' />";
                    ?>
                </div>

                <div class="col-md-9 item-info">

                    <h3><?php echo $row['Name'] ?> </h3>
                    <p class='desc-specific'><span></span><?php echo $row['Description'] ?> </p>

                    <ul class="list-unstyled list-group">
                        <li class="list-group-item">
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Date </span> : <?php echo $row['Add_Date'] ?>
                        </li>

                        <li class="list-group-item">
                            <i class="fa fa-building fa-fw"></i>
                            <span>Made in </span> : <?php echo $row['Country_Made'] ?>
                        </li>

                        <li class="list-group-item">
                            <i class="fa fa-tags fa-fw"></i>
                            <span>Category </span> : <a href="categories.php?pageid=<?php echo $row['Cat_ID']?>"> <?php echo $row['cat_name'] ?></a>
                        </li>

                        <li class="list-group-item">
                            <i class="fa fa-user fa-fw"></i>
                            <span>Added By </span> : <?php echo $row['username'] ?>
                        </li>

                    </ul>
                </div>
            </div>
            <hr>
            <?php if(isset($_SESSION['user'])){  ?>
            <div class="row">
                <div class="item-comment">
                    <div class="col-md-offset-3">
                        <h3>Add Comment</h3>
                        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ."?itemid=". $row['Item_ID'];?>" method="post">
                            <textarea class="form-control" name="comment" required></textarea>
                            <input type="submit" class="btn btn-primary" value="Add Comment">
                        </form>

                        <?php
                            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                                $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                $user_id = $_SESSION['userid'];
                                $item_id = $row['Item_ID'];

                                if(!empty($comment)){
                                    $stmt= $conn->prepare("INSERT INTO comments(Comment, Status, Comment_Date, Item_ID, User_ID) 
                                                            VALUES (?, 0, now(), ?, ?)");
                                    $stmt->execute(array($comment, $item_id, $user_id));
                                    if($stmt){
                                        echo "<div class='alert alert-success'>Comment Added Successsfully</div>";
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
            <?php }
                    else{
                        echo '<a href="login.php">Login or register </a>';
                        echo "To Add Comment";
                    }

            ?>
            <hr>

            <!-- Display Comments for this Item -->

            <?php

            $stmt = $conn->prepare("SELECT
                                comments.*,
                                 users.Username AS User_Name
                                 FROM comments
                                 INNER JOIN users ON users.UserID = comments.User_ID
                                 WHERE Item_ID = ?
                                 AND Status = 1
                                ORDER BY Comment_ID DESC");
            $stmt->execute(array($row['Item_ID']));
            $comments = $stmt->fetchAll();

            foreach ($comments as $comment){
            ?>

            <div class="comment_box">
                <div class="row">
                    <div class="col-sm-2 user_image">
                        <?php
                        echo "<img class='img-responsive img-thumbnail' src='item.jpg' alt='Item' />";
                        ?>
                        <span class="text-center"><?php echo $comment['User_Name']; ?></span>
                    </div>
                    <div class="col-sm-10 user_comment">
                        <p class="lead"><?php echo $comment['Comment']; ?></p>
                        <span><?php echo $comment['Comment_Date']; ?></span>
                    </div>
                </div>
            </div>
            <hr>
            <?php } ?>
        </div>

        <?php
    }else
    {
        echo "<div class='container'>";

        $MSG =  "<div class='alert alert-danger'>Ther is No This ID</div>";
        RedirectToHome($MSG);  // Redirect to HomePage

        echo "</div>";
    }
 ?>

<?php
include $template . "footer.php";
ob_end_flush();
?>