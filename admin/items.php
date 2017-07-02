<?php


ob_start();

session_start();
// We Need Navbar Here, So we type { true }
$navbar =  true;
$titleHeader = "Items";

if(isset($_SESSION['admin'])) {
    include "init.php";

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == "Manage"){


        // Select All From Items and Join with Another tables
        $stmt = $conn->prepare("SELECT items.*,
                                categories.Name AS category_name,
                                 users.Username
                                FROM items
                                INNER JOIN categories ON categories.ID = items.Cat_ID
                                INNER JOIN users on users.UserID = items.Member_ID
                                ORDER BY Item_ID DESC ");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        if(!empty($rows)) {
            ?>
            <h1 class="text-center">Manage Items</h1>
            <div class="container">

                <div class="table-responsive">
                    <table class="main-table table table-bordered text-center">
                        <tr>
                            <td>ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Register Date</td>
                            <td>Category Name</td>
                            <td>UserName</td>
                            <td>Control</td>
                        </tr>
                        <?php foreach ($rows as $row) {
                            ?>
                            <tr>
                                <td><?php echo $row['Item_ID']; ?></td>
                                <td><?php echo $row['Name']; ?></td>
                                <td><?php echo $row['Description']; ?></td>
                                <td><?php echo $row['Price']; ?>$</td>
                                <td><?php echo $row['Add_Date']; ?></td>
                                <td><?php echo $row['category_name']; ?></td>
                                <td><?php echo $row['Username']; ?></td>
                                <td>
                                    <a href="items.php?do=edit&itemid=<?php echo $row['Item_ID']; ?>"
                                       class="btn btn-success"><i class="fa fa-edit "></i> Edit</a>
                                    <a href="items.php?do=Delete&itemid=<?php echo $row['Item_ID']; ?>"
                                       class="btn btn-danger confirm"><i class="fa fa-remove "></i> Delete</a>
                                    <?php
                                    if ($row['Approve'] == 0) {
                                        echo "<a href='items.php?do=Approve&itemid={$row["Item_ID"]}' class='btn btn-info'><i class='fa fa-check-square-o '></i> Aprove</a>";
                                    }
                                    ?>

                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>


                <a class='btn btn-primary' href='items.php?do=Add'><i class="fa fa-plus"> Add New Item</i></a>

            </div>
            <?php
        }else{
            echo "<div class='container'>";
                echo "<h1 class=''>There are no Items</h1>";
                echo "<a class='btn btn-primary' href='items.php?do=Add'><i class='fa fa-plus'> Add New Item</i></a>";
            echo "</div>";
        }
    }
    elseif ($do == 'Add'){

        ?>

        <h1 class="text-center">Add New Items</h1>
        <div class="container">

            <form class="form-horizontal" action="?do=Insert" method="post" autocomplete="off">

                <!-- Start name -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" autocomplete="off" required="required">
                    </div>
                </div>

                <!-- Start Description -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="form-control" autocomplete="off" required="required">
                    </div>
                </div>

                <!-- Start Price -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="price" class="form-control" autocomplete="off" required="required">
                    </div>
                </div>

                <!-- Start Caountry -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Made in </label>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="country_made" class="form-control" autocomplete="off" required="required">
                    </div>
                </div>

                <!-- Start Status -->
                <div class="form-group form-group-lg">
                    <Label class="col-sm-2 control-label">Status</Label>
                    <div class="col-sm-10 col-md-6">
                        <select name="status" class="form-control">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Used</option>
                            <option value="4">Old</option>
                        </select>
                    </div>
                </div>


                <!-- Start Members -->
                <div class="form-group form-group-lg">
                    <Label class="col-sm-2 control-label">Members</Label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member" class="form-control">
                            <option value="0">...</option>
                            <?php
                                $stmt = $conn->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();

                                foreach ($users as $user){
                                    echo "<option value=".$user["UserID"].">".$user["Username"]."</option>";
                                }

                            ?>
                        </select>
                    </div>
                </div>


                <!-- Start Status -->
                <div class="form-group form-group-lg">
                    <Label class="col-sm-2 control-label">Categroies</Label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category" class="form-control">
                            <option value="0">...</option>
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM categories");
                            $stmt->execute();
                            $category = $stmt->fetchAll();

                            foreach ($category as $cat){
                                echo "<option value=".$cat["ID"].">".$cat["Name"]."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- Start Save Button -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" name="save" value="Add Item" class="btn btn-primary btn-lg">
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
            echo "<h1 class='text-center'>Insert Items</h1>";

            echo "<div class='container'>";

            // get values form Form after Edit
            $name         =  $_POST['name'];
            $description  =  $_POST['description'];
            $price        =  $_POST["price"];
            $country      =  $_POST["country_made"];
            $status       =  $_POST["status"];
            $member_id    =  $_POST["member"];
            $cat_id       =  $_POST["category"];

            /*============= Validation Form ==============*/

            // Array to Store Errors
            $formErrors = array();

            // validate Username
            if(empty($name)){
                $formErrors[] = "You Must Fill Item Name Input ";
            }else{
                if(strlen($name) < 2){
                    $formErrors[] = "You Must type More than 2 Characters in Name Input ";
                }
                if(strlen($name) > 100){
                    $formErrors[] = "You Must type Less than 15 Characters in Name Input ";
                }
            }
            if(empty($description)){
                $formErrors[] = "You Must Fill Item Description Input";
            }

            if(empty($price)){
                $formErrors[] = "You Must Fill Item Price Input";
            }

            if(empty($country)){
                $formErrors[] = "You Must Determine Item Caountry Input";
            }

            if($status == 0){
                $formErrors[] = "You Must Specifying Country ";
            }

            /*============= End Validation Form ==============*/

            //Update in Database If  No Errors Happened
            if(empty($formErrors)) {


                    $stmt = $conn->prepare("INSERT INTO items (Name, Description, Price, Country_Made, Status, Add_Date, Member_ID, Cat_ID)
                                            VALUES(:name, :description, :price, :country, :status, now(), :member_id, :cat_id)");
                    $stmt->execute(array(
                        'name'         =>   $name,
                        'description'  =>   $description,
                        'price'        =>   $price,
                        'country'      =>   $country,
                        'status'       =>   $status,
                        'member_id'    =>   $member_id,
                        'cat_id'       =>   $cat_id
                    ));

                    // Print Result
                    $MSG =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Item Inserted</div>";
                    RedirectToHome($MSG);

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
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // Select Data for This UserID
        $stmt =$conn->prepare("SELECT * FROM items WHERE Item_ID = ? LIMIT 1");
        $stmt->execute(array($itemid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        // if There is ID
        if($count > 0) {
            ?>

            <h1 class="text-center">Edit Items</h1>
            <div class="container">

                <form class="form-horizontal" action="?do=Update" method="post">

                    <input type="hidden" name="item_id" value="<?php echo $row["Item_ID"]; ?>">
                    <!-- Start name -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="name" value="<?php echo $row["Name"]?>" class="form-control" autocomplete="off" required="required">
                        </div>
                    </div>

                    <!-- Start Description -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="description" value="<?php echo $row["Description"]?>" class="form-control" autocomplete="off" required="required">
                        </div>
                    </div>

                    <!-- Start Price -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="price" value="<?php echo $row["Price"]?>" class="form-control" autocomplete="off" required="required">
                        </div>
                    </div>

                    <!-- Start Caountry -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Made in </label>
                        <div class="col-sm-10 col-md-6">
                            <input type="text" name="country_made" value="<?php echo $row["Country_Made"]?>" class="form-control" autocomplete="off" required="required">
                        </div>
                    </div>

                    <!-- Start Status -->
                    <div class="form-group form-group-lg">
                        <Label class="col-sm-2 control-label">Status</Label>
                        <div class="col-sm-10 col-md-6">
                            <select name="status" class="form-control">
                                                 <!--  echo [Condition] if(true){echo 'selected'} else {echo ''} -->
                                <option value="1"<?php echo $row["Status"] == 1 ? 'selected' : '' ?> >New</option>
                                <option value="2"<?php echo $row["Status"] == 2 ? 'selected' : '' ?> >Like New</option>
                                <option value="3"<?php echo $row["Status"] == 3 ? 'selected' : '' ?> >Used</option>
                                <option value="4"<?php echo $row["Status"] == 4 ? 'selected' : '' ?>>Old</option>
                            </select>
                        </div>
                    </div>


                    <!-- Start Members -->
                    <div class="form-group form-group-lg">
                        <Label class="col-sm-2 control-label">Members</Label>
                        <div class="col-sm-10 col-md-6">
                            <select name="member" class="form-control">
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();

                                foreach ($users as $user){
                                    echo "<option value=".$user["UserID"]." ";
                                    if($row["Member_ID"] == $user["UserID"]){echo 'selected';}
                                    echo ">".$user["Username"]."</option>";
                                }

                                ?>
                            </select>
                        </div>
                    </div>


                    <!-- Start Categroies -->
                    <div class="form-group form-group-lg">
                        <Label class="col-sm-2 control-label">Category</Label>
                        <div class="col-sm-10 col-md-6">
                            <select name="category" class="form-control">
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $category = $stmt->fetchAll();

                                foreach ($category as $cat){
                                    echo "<option value=".$cat["ID"]." ";
                                    if($row["Cat_ID"] == $cat["ID"]){echo 'selected';}
                                    echo ">".$cat["Name"]."</option>";
                                }

                                ?>
                            </select>
                        </div>
                    </div>


                    <!-- Start Save Button -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" name="save" value="Save" class="btn btn-primary btn-lg">
                        </div>
                    </div>

                </form>

<!-- Display Comments For This Item -->

                <?php
                // Select All From Comments
                $stmt = $conn->prepare("SELECT
                comments.*,
                users.Username AS User_Name
                FROM comments
                INNER JOIN users ON users.UserID = comments.User_ID
                WHERE Item_ID = ?
                ");
                $stmt->execute(array($itemid));
                $rows = $stmt->fetchAll();

                // Check if there is Comments for this item or No
                if(!empty($rows)){
                ?>
                <h1 class="text-center">Manage [ <?php echo $row["Name"]?> ] Comments</h1>

                    <div class="table-responsive">
                        <table class="main-table table table-bordered text-center">
                            <tr>
                                <td>Comment</td>
                                <td>User Name</td>
                                <td>Date</td>
                                <td>Control</td>
                            </tr>
                            <?php foreach ($rows as $row) {
                                ?>
                                <tr>
                                    <td><?php echo $row['Comment']; ?></td>
                                    <td><?php echo $row['User_Name']; ?></td>
                                    <td><?php echo $row['Comment_Date']; ?></td>
                                    <td>
                                        <a href="comments.php?do=edit&commid=<?php echo $row['Comment_ID']; ?>" class="btn btn-success"><i class="fa fa-edit "></i> Edit</a>
                                        <a href="comments.php?do=Delete&commid=<?php echo $row['Comment_ID']; ?>" class="btn btn-danger confirm"><i class="fa fa-remove "></i> Delete</a>
                                        <?php
                                        if($row['Status'] == 0){
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
                <?php }
                      else{
                          echo "<h1 class='text-center'>There are no Comments for this Item</h1>";
                      }


                ?>




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


        echo "<h1 class='text-center'>Update Items</h1>";

        echo "<div class='container'>";
        // Check if Access this Page From Save Button or Direct from Link
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            // get values form Form after Edit
            $item_id      =  $_POST["item_id"];
            $name         =  $_POST['name'];
            $description  =  $_POST['description'];
            $price        =  $_POST["price"];
            $country      =  $_POST["country_made"];
            $status       =  $_POST["status"];
            $member_id    =  $_POST["member"];
            $cat_id       =  $_POST["category"];


            /*============= Validation Form ==============*/

            // Array to Store Errors
            $formErrors = array();

            // validate Username
            if(empty($name)){
                $formErrors[] = "You Must Fill Item Name Input ";
            }else{
                if(strlen($name) < 2){
                    $formErrors[] = "You Must type More than 2 Characters in Name Input ";
                }
                if(strlen($name) > 100){
                    $formErrors[] = "You Must type Less than 15 Characters in Name Input ";
                }
            }
            if(empty($description)){
                $formErrors[] = "You Must Fill Item Description Input";
            }

            if(empty($price)){
                $formErrors[] = "You Must Fill Item Price Input";
            }

            if(empty($country)){
                $formErrors[] = "You Must Determine Item Caountry Input";
            }

            if($status == 0){
                $formErrors[] = "You Must Specifying Country ";
            }

            /*============= End Validation Form ==============*/

            //Update in Database If  No Errors Happened
            if(empty($formErrors)) {

                $stmt2 = $conn->prepare("SELECT * FROM items WHERE Name = ? AND Item_ID != ?");
                $stmt2->execute(array($name, $item_id));
                $check = $stmt2->rowCount();
                if($check == 1){
                    $MSG = "<div class='alert alert-danger'>Sorry this Item is Exist</div>";
                    RedirectToHome($MSG, 'back');
                }else {

                    $stmt = $conn->prepare("UPDATE items SET Name = ?, Description = ?, Price = ?, Country_Made = ?, Status = ?, Member_ID = ?, Cat_ID = ?
                                            WHERE Item_ID = ?");
                    $stmt->execute(array($name, $description, $price, $country, $status, $member_id, $cat_id, $item_id));

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
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // Check Data for This UserID
        $check = checkItem('Item_ID', 'items', $itemid);
        // if There is ID
        if($check > 0) {
            // Delete Statement
            $stmt = $conn->prepare("DELETE FROM items WHERE Item_ID = ?");
            $stmt->execute(array($itemid));
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
    elseif ($do == 'Approve'){

        // Sanitize Value of ID from $_GET to Avoid Hacking
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // Check Data for This UserID
        $check = checkItem('Item_ID', 'items', $itemid);
        // if There is ID
        if($check > 0) {
            // Delete Statement
            $stmt = $conn->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
            $stmt->execute(array($itemid));

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
