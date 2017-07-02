<?php

ob_start();

session_start();
// We Need Navbar Here, So we type { true }
$navbar =  true;
$titleHeader = "Members";

if(isset($_SESSION['admin'])){
    include "init.php";

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == "Manage") {


        // Select All From Comments
        $stmt = $conn->prepare("SELECT
                                comments.*,
                                 items.Name AS Item_Name,
                                 users.Username AS User_Name
                                 FROM comments
                                 INNER JOIN items ON items.Item_ID = comments.Item_ID
                                 INNER JOIN users ON users.UserID = comments.User_ID
                                ORDER BY Comment_ID DESC ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if(!empty($rows)) {
            ?>
            <h1 class="text-center">Manage Comments</h1>
            <div class="container">

                <div class="table-responsive">
                    <table class="main-table table table-bordered text-center">
                        <tr>
                            <td>ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Date</td>
                            <td>Control</td>
                        </tr>
                        <?php foreach ($rows as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row['Comment_ID']; ?></td>
                                <td><?php echo $row['Comment']; ?></td>
                                <td><?php echo $row['Item_Name']; ?></td>
                                <td><?php echo $row['User_Name']; ?></td>
                                <td><?php echo $row['Comment_Date']; ?></td>
                                <td>
                                    <a href="comments.php?do=edit&commid=<?php echo $row['Comment_ID']; ?>"
                                       class="btn btn-success"><i class="fa fa-edit "></i> Edit</a>
                                    <a href="comments.php?do=Delete&commid=<?php echo $row['Comment_ID']; ?>"
                                       class="btn btn-danger confirm"><i class="fa fa-remove "></i> Delete</a>
                                    <?php
                                    if ($row['Status'] == 0) {
                                        echo "<a href='comments.php?do=Approve&commid={$row["Comment_ID"]}' class='btn btn-info'><i class='fa fa-check-square-o '></i> Approve</a>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>

            <?php
        }else{
            echo "<div class='container'>";
            echo "<h1 class=''>There are no Comments</h1>";
            echo "</div>";
        }
    }
    elseif($do == 'edit'){

        // Sanitize Value of ID from $_GET to Avoid Hacking
        $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;

        // Select Data for This UserID
        $stmt =$conn->prepare("SELECT * FROM comments WHERE Comment_ID = ? LIMIT 1");
        $stmt->execute(array($commid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        // if There is ID
        if($count > 0) {
            ?>

            <h1 class="text-center">Edit Comments</h1>
            <div class="container">

                <form class="form-horizontal" action="?do=Update" method="post" autocomplete="off">

                    <input type="hidden" name="comm_id" value="<?php echo $row['Comment_ID'] ?>">
                    <!-- Start Username -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-6">
                            <textarea type="text" name="comment"  class="form-control" required="required"><?php echo $row['Comment'] ?></textarea>
                        </div>
                    </div>

                    <!-- Start Save Button -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" name="save" value="Save" class="btn btn-primary btn-lg">
                        </div>
                    </div>

                </form>
            </div>

            <?php
        }
        // if type Wrong ID
        else{
            echo "<div class='container'>";

            $MSG =  "<div class='alert alert-danger'>Ther is No This ID</div>";
            RedirectToHome($MSG);  // Redirect to HomePage

            echo "</div>";
        }
    }
    elseif ($do == 'Update'){

        echo "<h1 class='text-center'>Update Comments</h1>";

        echo "<div class='container'>";
        // Check if Access this Page From Save Button or Direct from Link
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            // get values form Form after Edit
            $comment_id = $_POST['comm_id'];
            $comment = $_POST['comment'];

            /*============= Validation Form ==============*/

            // Array to Store Errors
            $formErrors = array();

            // validate Username
            if(empty($comment)){
                $formErrors[] = "You Must Type Comment ";
            }
            /*============= End Validation Form ==============*/

            //Update in Database If  No Errors Happened
            if(empty($formErrors)) {

                $stmt = $conn->prepare("UPDATE comments SET Comment = ? WHERE Comment_ID = ?");
                $stmt->execute(array($comment, $comment_id));

                $MSG = "<div class='alert alert-success'>" . $stmt->rowCount() . " Comment Updated</div>";
                RedirectToHome($MSG, 'back');
            }
            else{  // if there is Errors Then Print it
                foreach ($formErrors as $errors){
                    echo "<div class='alert alert-danger'>".$errors."</div>";
                }
                // Redirect to Previous Page
                RedirectToHome('','back', 5);
            }
        }
        else{
            $MSG = "<div class='alert alert-warning'>You Can't Access This Page Direct</div>";
            RedirectToHome($MSG);
        }
        // End Container
        echo "</div>";
    }
    elseif ($do == 'Delete'){

        echo "<h1 class='text-center'>Delete Comments</h1>";

        // Sanitize Value of ID from $_GET to Avoid Hacking
        $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;

        // Check Data for This UserID
        $check = checkItem('Comment_ID', 'comments', $commid);
        // if There is ID
        if($check > 0) {
            // Delete Statement
            $stmt = $conn->prepare("DELETE FROM comments WHERE Comment_ID = ?");
            $stmt->execute(array($commid));
            echo "<div class='container'>";

            $MSG =  "<div class='alert alert-success'>" .$stmt->rowCount()." Recored Deleted</div>";
            RedirectToHome($MSG, 'back');

            echo "</div>";

        }else{
            echo "<div class='container'>";

            $MSG =  "<div class='alert alert-danger'>Ther is No This ID</div>";
            RedirectToHome($MSG);  // Redirect to HomePage

            echo "</div>";
        }
    }
    elseif ($do == "Approve"){

        echo "<h1 class='text-center'>Approve Comment</h1>";

        // Sanitize Value of ID from $_GET to Avoid Hacking
        $commid = isset($_GET['commid']) && is_numeric($_GET['commid']) ? intval($_GET['commid']) : 0;

        // Check Data for This UserID
        $check = checkItem('Comment_ID', 'comments', $commid);
        // if There is ID
        if($check > 0) {
            // Delete Statement
            $stmt = $conn->prepare("UPDATE comments SET Status = 1 WHERE Comment_ID = ?");
            $stmt->execute(array($commid));

            echo "<div class='container'>";
            $MSG =  "<div class='alert alert-success'>" .$stmt->rowCount()." Comment Approved</div>";
            RedirectToHome($MSG, 'back');
            echo "</div>";

        }else{
            echo "<div class='container'>";
            $MSG =  "<div class='alert alert-danger'>Ther is No This ID</div>";
            RedirectToHome($MSG);  // Redirect to HomePage

            echo "</div>";
        }
    }



    // Calling Footer Scripts
    include $template . "footer.php";

}
else{
    header("Location: index.php");
}


ob_end_flush();
?>