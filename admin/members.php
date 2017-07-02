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

        // if you enter this page from Dashaboard  through Pending Members
        $quer_string = '';
            if(isset($_GET['users']) && $_GET['users'] == 'pending'){
                $quer_string = 'AND RegStatus = 0';
            }
        // Select All From Users Except Admins
        $stmt = $conn->prepare("SELECT * FROM users WHERE GroupID != 1 $quer_string ORDER BY UserID DESC ");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if(!empty($rows)) {
            ?>
            <h1 class="text-center">Manage Member</h1>
            <div class="container">

                <div class="table-responsive">
                    <table class="main-table table table-bordered text-center">
                        <tr>
                            <td>ID</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Fullname</td>
                            <td>Register Date</td>
                            <td>Control</td>
                        </tr>
                        <?php foreach ($rows as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row['UserID']; ?></td>
                                <td><?php echo $row['Username']; ?></td>
                                <td><?php echo $row['Email']; ?></td>
                                <td><?php echo $row['FullName']; ?></td>
                                <td><?php echo $row['Date']; ?></td>
                                <td>
                                    <a href="members.php?do=edit&userid=<?php echo $row['UserID']; ?>"
                                       class="btn btn-success"><i class="fa fa-edit "></i> Edit</a>
                                    <a href="members.php?do=Delete&userid=<?php echo $row['UserID']; ?>"
                                       class="btn btn-danger confirm"><i class="fa fa-remove "></i> Delete</a>
                                    <?php
                                    if ($row['RegStatus'] == 0) {
                                        echo "<a href='members.php?do=Active&userid={$row["UserID"]}' class='btn btn-info'><i class='fa fa-check-square-o '></i> Active</a>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>


                <a class='btn btn-primary' href='members.php?do=Add'><i class="fa fa-plus"> Add New Member</i></a>

            </div>
            <?php
        }else{
            echo "<div class='container'>";
            echo "<h2 class='text-left'>Ther are no Members</h2>";
            echo '<a class="btn btn-primary" href="members.php?do=Add"><i class="fa fa-plus"> Add New Member</i></a>';
            echo "</div>";
        }
    }
    elseif ($do == 'Add') {
        ?>

        <h1 class="text-center">Add New Member</h1>
        <div class="container">

            <form class="form-horizontal" action="?do=Insert" method="post" autocomplete="off">

                <!-- Start Username -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="username" class="form-control" autocomplete="off" required="required">
                    </div>
                </div>

                <!-- Start Password -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">New Password (Optional)</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="password" name="password" class="password form-control" autocomplete="off" required="required">
                        <i class="fa fa-eye fa-2x show-pass"></i>
                    </div>
                </div>

                <!-- Start Email -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="email" name="email" class="form-control" autocomplete="off" required="required">
                    </div>
                </div>

                <!-- Start Full-name -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Fullname</label>
                    <div class="col-sm-10  col-md-6">
                        <input type="text" name="fullname" class="form-control" autocomplete="off" required="required">
                    </div>
                </div>

                <!-- Start Save Button -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" name="save" value="Add Member" class="btn btn-primary btn-lg">
                    </div>
                </div>

            </form>
        </div>

        <?php
    }
    elseif($do == 'Insert'){

        // Check if Access this Page From Save Button or Direct from Link
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            echo "<h1 class='text-center'>Insert Members</h1>";

            echo "<div class='container'>";

            // get values form Form after Edit
            $username   = $_POST['username'];
            $password = $_POST['password'];
            $hashedPassword = sha1($password);
            $email      = $_POST['email'];
            $fullname   = $_POST['fullname'];

            /*============= Validation Form ==============*/

            // Array to Store Errors
            $formErrors = array();

            // validate Username
            if(empty($username)){
                $formErrors[] = "You Must Fill Username Input ";
            }else{
                if(strlen($username) < 2){
                    $formErrors[] = "You Must type More than 2 Characters in Username Input ";
                }
                if(strlen($username) > 15){
                    $formErrors[] = "You Must type Less than 15 Characters in Username Input ";
                }
            }

            // Validate Password
            if(empty($password)){
                $formErrors[] = "You Must Fill Username Input";
            }else{
                if(strlen($password) < 4){
                    $formErrors[] = "You Must type More than 4 Characters in Password Input ";
                }
                if(strlen($password) > 16){
                    $formErrors[] = "You Must type Less than 15 Characters in Password Input ";
                }
            }

            // validate Email
            if(empty($email)){
                $formErrors[] = "You Must Fill Email Input ";
            }

            // validate FullName
            if(empty($fullname)){
                $formErrors[] = "You Must Fill Fullname Input ";
            }
            else{
                if(strlen($fullname) < 4){
                    $formErrors[] = "You Must type more than 4 Characters in Fullname Input ";
                }
                if(strlen($fullname) > 50){
                    $formErrors[] = "You Must type Less than 50 Characters in Fullname Input ";
                }
            }


            /*============= End Validation Form ==============*/

            //Update in Database If  No Errors Happened
            if(empty($formErrors)) {

                $user_exist = checkItem('Username', 'users', $username);
                if ($user_exist == 0) {

                    $stmt = $conn->prepare("INSERT INTO users (Username, Password, Email, FullName, RegStatus, Date) VALUES (?, ?, ?, ?, 1, now())");
                    $stmt->execute(array($username, $hashedPassword, $email, $fullname));


                    $MSG =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";
                    RedirectToHome($MSG);
                }else{
                    $MSG =  "<div class='alert alert-danger'>This Username is Exist</div>";
                    RedirectToHome($MSG, 'back');
                }
            }
            else{  // if there is Errors Then Print it
                foreach ($formErrors as $errors){
                    echo "<div class='alert alert-danger'>".$errors."</div>";
                }
                // Redirect to revious Page
                RedirectToHome('','back', 5);
            }

            // End Container
            echo "</div>";
        }
        else{
            echo "<div class='container'>";

            $MSG = "<div class='alert alert-danger'>You Can't Access This Page Directly</div>";
            RedirectToHome($MSG);

            echo "</div>";
        }

    }
    elseif($do == 'edit'){

        // Sanitize Value of ID from $_GET to Avoid Hacking
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // Select Data for This UserID
        $stmt =$conn->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        // if There is ID
        if($count > 0) {
            ?>

            <h1 class="text-center">Edit Member</h1>
            <div class="container">

                <form class="form-horizontal" action="?do=Update" method="post" autocomplete="off">

                    <input type="hidden" name="userid" value="<?php echo $row['UserID'] ?>">
                    <!-- Start Username -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="username" value="<?php echo $row['Username'] ?>" class="form-control" autocomplete="off" required="required">
                        </div>
                    </div>

                    <!-- Start Password -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">New Password (Optional)</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="hidden" name="oldpassword" value="<?php echo $row['Password']?>">
                            <input type="password" name="newpassword"  class="form-control" autocomplete="off">
                        </div>
                    </div>

                    <!-- Start Email -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" autocomplete="off" required="required">
                        </div>
                    </div>

                    <!-- Start Full-name -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Fullname</label>
                        <div class="col-sm-10  col-md-6">
                            <input type="text" name="fullname" value="<?php echo $row['FullName'] ?>" class="form-control" autocomplete="off" required="required">
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

        echo "<h1 class='text-center'>Update Members</h1>";

            echo "<div class='container'>";
            // Check if Access this Page From Save Button or Direct from Link
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                // get values form Form after Edit
                $userid     = $_POST['userid'];
                $username   = $_POST['username'];
                $email      = $_POST['email'];
                $fullname   = $_POST['fullname'];

                // Check if User Edit Password
                            // Condition ? True : False
                $password = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                /*============= Validation Form ==============*/

                // Array to Store Errors
                $formErrors = array();

                // validate Username
                if(empty($username)){
                    $formErrors[] = "You Must Fill Username Input ";
                }else{
                    if(strlen($username) < 2){
                        $formErrors[] = "You Must type More than 2 Characters in Username Input ";
                    }
                    if(strlen($username) > 15){
                        $formErrors[] = "You Must type Less than 15 Characters in Username Input ";
                    }
                }

                // validate Email
                if(empty($email)){
                    $formErrors[] = "You Must Fill Email Input ";
                }

                // validate FullName
                if(empty($fullname)){
                    $formErrors[] = "You Must Fill Fullname Input ";
                }
                else{
                    if(strlen($fullname) < 4){
                        $formErrors[] = "You Must type more than 4 Characters in Fullname Input ";
                    }
                    if(strlen($fullname) > 50){
                        $formErrors[] = "You Must type Less than 50 Characters in Fullname Input ";
                    }
                }

                /*============= End Validation Form ==============*/

                 //Update in Database If  No Errors Happened
                if(empty($formErrors)) {

                    //$check = checkItemAtUpdate('*', 'users', 'Username', 'UserID', $username, $userid);

                    $stmt2 = $conn->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?");
                    $stmt2->execute(array($username, $userid));
                    $check = $stmt2->rowCount();
                    if($check == 1){
                        $MSG = "<div class='alert alert-danger'>Sorry this user is Exist</div>";
                        RedirectToHome($MSG, 'back');
                    }
                    else {
                        // Check if User is Exist
                        $stmt = $conn->prepare("UPDATE users SET Username = ?, Password = ?, Email = ?, FullName = ? WHERE UserID = ?");
                        $stmt->execute(array($username, $password, $email, $fullname, $userid));

                        $MSG = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Updated</div>";
                        RedirectToHome($MSG, 'back');
                    }
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

        // Sanitize Value of ID from $_GET to Avoid Hacking
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // Check Data for This UserID
        $check = checkItem('UserID', 'users', $userid);
        // if There is ID
        if($check > 0) {
            // Delete Statement
            $stmt = $conn->prepare("DELETE FROM users WHERE UserID = ?");
            $stmt->execute(array($userid));
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
    elseif ($do == "Active"){
        // Sanitize Value of ID from $_GET to Avoid Hacking
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // Check Data for This UserID
        $check = checkItem('UserID', 'users', $userid);
        // if There is ID
        if($check > 0) {
            // Delete Statement
            $stmt = $conn->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
            $stmt->execute(array($userid));

            echo "<div class='container'>";
            $MSG =  "<div class='alert alert-success'>" .$stmt->rowCount()." Recored Updated</div>";
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