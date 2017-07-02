<?php
    session_start();
    // We Need Navbar Here, So we type { true }
    $navbar =  true;
    $titleHeader = "Dashboard";

    if(isset($_SESSION['admin'])){
        include "init.php";
        /* Start Dashboard */
?>

    <div class="container home-stata text-center">
        <h1>Dshaboard</h1>

        <div class="row">

            <div class="col-md-3 col-sm-6">
                <div class="stat st-member">
                    Total Members
                    <span><a href="members.php"> <?php echo calculateItems('Username', 'users'); ?> </a></span>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="stat st-pending">
                    Pending Members
                    <span><a href="members.php?do=Manage&users=pending"> <?php echo calculateItems('Username', 'users', 'WHERE RegStatus = 0'); ?></a></span>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="stat st-items">
                    Totoal Items
                    <span><a href="items.php?do=Manage"><?php echo calculateItems('Item_ID', 'items'); ?></a></span>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="stat st-comments">
                    Total Comments
                    <span><a href="comments.php?do=Manage"><?php echo calculateItems('Comment_Id', 'comments')?></a> </span>
                </div>
            </div>
        </div>
    </div>

    <div class="container latest">

        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php
                            $limit = 5;
                            $latestUsers = getLatest('Username, UserID', 'users', 'UserID', $limit)
                        ?>
                        <i class="fa fa-users"></i> Latest <?php echo $limit; ?> Users Register
                        <span class="pull-right toggle_info">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body ">
                        <ul class="list-group">
<?php
                        foreach ($latestUsers as $user){
                            echo "<li class='list-group-item'>".$user['Username'] . "<a href='members.php?do=edit&userid=".$user['UserID']."' class='btn btn-success pull-right'><i class='fa fa-edit'></i></i> Edit</a></li>";
                        }
?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php
                        $limit = 5;
                        $latestItems = getLatest('Name, Item_ID, Approve', 'items', 'Item_ID', $limit)
                        ?>
                        <i class="fa fa-tag"></i> Latest <?php echo $limit; ?> Items Added
                        <span class="pull-right toggle_info">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body ">
                        <ul class="list-group">
                            <?php
                            if(!empty($latestItems)) {
                                foreach ($latestItems as $item) {
                                    echo "<li class='list-group-item'>" . $item['Name'] .
                                        "<a href='items.php?do=edit&itemid=" . $item['Item_ID'] . "'
                                      class='btn btn-success pull-right'>
                                      <i class='fa fa-edit'>
                                      </i> 
                                      Edit
                                      </a>";

                                    if ($item["Approve"] == 0) {
                                        echo "<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "'
                                     class='btn btn-info pull-right approve'>
                                     <i class='fa fa-check'>
                                     </i> 
                                     Approve
                                     </a>";
                                    }

                                    echo "</li>";
                                }
                            }else{
                                echo "<p class='empty_message'>There are no Items</p>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start Show Comments -->
        <div class="row">
            <div class="col-sm-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php $limit = 5 ?>
                        <i class="fa fa-comments"></i> Latest Comments
                        <span class="pull-right toggle_info">
                            <i class="fa fa-plus fa-lg"></i>
                        </span>
                    </div>
                    <div class="panel-body ">
                        <?php
                        $stmt = $conn->prepare("SELECT
                                comments.*,
                                 users.Username AS User_Name,
                                 users.UserID AS User_ID
                                 FROM comments
                                 INNER JOIN users ON users.UserID = comments.User_ID
                                LIMIT $limit");
                        $stmt->execute();
                        $comments = $stmt->fetchAll();

                        if(!empty($comments)){

                            foreach ($comments as $comment){
                                echo "<div class=comment_box>";

                                    echo "<a href='members.php?do=edit&userid=".$comment["User_ID"]."' class='member_name'>" .$comment["User_Name"]. "</a>";
                                echo "<p class='member_comment'>" .$comment["Comment"]. "</p>";

                                echo "</div";
                            }
                        }else{
                            echo "<p class='empty_message'>There are no Comments</p>";
                        }

                        ?>
                    </div>
                </div>
            </div>


        </div>
    </div>

<?php
        // Calling Footer Scripts
        include $template . "footer.php";

    }
    else{
        header("Location: index.php");
    }

?>