<?php


ob_start();

    session_start();
    // We Need Navbar Here, So we type { true }
    $navbar =  true;
    $titleHeader = "Categroies";

    if(isset($_SESSION['admin'])) {
        include "init.php";

        $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

        if($do == "Manage"){

            $sort = 'ASC';
            $sort_arrey = array('ASC','DESC');

            if(isset($_GET["sort"]) && in_array($_GET["sort"], $sort_arrey)){
                $sort = $_GET["sort"];
            }

            $stmt = $conn->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
            $stmt->execute();
            $cat = $stmt->fetchAll();
            if(!empty($cat)) {
                ?>
                <h1 class="text-center">Manage Categories</h1>
                <div class="container category">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Manage Categories
                            <div class="ordering pull-right">
                                [ Order By:
                                <a class="<?php echo $sort == "ASC" ? "active" : '' ?>" href="?sort=ASC">ASC </a> |
                                <a class="<?php echo $sort == "DESC" ? "active" : '' ?>" href="?sort=DESC">DESC</a> ]
                                [ View:
                                <span class="active" data-view="full">Full</span> |
                                <span data-view="classic">Classic</span> ]

                            </div>
                        </div>
                        <div class="panel-body">

                            <?php
                            foreach ($cat as $category) {
                                echo "<div class='cat'>";

                                echo "<div class='hidden-buttons'>";
                                echo "<a href='categories.php?do=edit&catid=" . $category["ID"] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'> Edit</i></a>";
                                echo "<a href='categories.php?do=Delete&catid=" . $category["ID"] . "' class='btn btn-xs btn-danger confirm'><i class='fa fa-remove'> Delete</i></a>";
                                echo "</div>";

                                echo "<h3>" . $category["Name"] . "</h3>";

                                echo "<div class='full_view'>";
                                echo "<p>";
                                if ($category["Description"] == '') {
                                    echo "This Category Has Not Description";
                                } else {
                                    echo $category["Description"];
                                }
                                echo "</p>";

                                if ($category["Visibility"] == 1) {
                                    echo "<span class='visibility'> Hidden</span>";
                                }

                                if ($category["Allow_Comment"] == 1) {
                                    echo "<span class='commenting'> Comment Disabled</span>";
                                }
                                if ($category["Allow_Ads"] == 1) {
                                    echo "<span class='ads'> Ads Disabled</span>";
                                }
                                echo "</div>";
                                echo "</div>";
                                echo "<hr/>";
                            }

                            ?>

                        </div>
                    </div>
                    <div class="add_category">
                        <a href="categories.php?do=Add" class="btn btn-lg btn-primary"><i class="fa fa-plus"></i> Add
                            New Category</a>
                    </div>
                </div>
                <?php
            }else{
                echo "<div class='container'>";
                    echo "<h2 class='text-left'>Ther are no Categories</h2>";
                    echo '<a href="categories.php?do=Add" class="btn btn-lg btn-primary"><i class="fa fa-plus"></i> Add
                            New Category</a>';
                echo "</div>";
            }
        }
        elseif ($do == 'Add'){
?>
            <h1 class="text-center">Add New Categroy</h1>
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
                            <input type="text" name="description" class="form-control" autocomplete="off" >
                        </div>
                    </div>

                    <!-- Start Ordering -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Ordering</label>
                        <div class="col-sm-10 col-md-6">
                            <input type="number" name="ordering" min="1" class="form-control" >
                        </div>
                    </div>

                    <!-- Start Visibility  -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Visibility</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input type="radio" id="vis-yes" name="visibility" value="0" checked>
                                <label for="vis-yes">Yes</label>
                            </div>
                            <div>
                                <input type="radio" id="vis-no" name="visibility" value="1">
                                <label for="vis-no">No</label>
                            </div>
                        </div>
                    </div>

                    <!-- Start Allow Comment  -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Comment</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input type="radio" id="allow" name="allow_comment" value="0" checked>
                                <label for="allow">Yes</label>
                            </div>
                            <div>
                                <input type="radio" id="not_allow" name="allow_comment" value="1">
                                <label for="not_allow">No</label>
                            </div>
                        </div>
                    </div>

                    <!-- Start Allow Ads  -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Allow Ads</label>
                        <div class="col-sm-10 col-md-6">
                            <div>
                                <input type="radio" id="allow_ads" name="allow_ads" value="0" checked>
                                <label for="allow_ads">Yes</label>
                            </div>
                            <div>
                                <input type="radio" id="not_allow_ads" name="allow_ads" value="1">
                                <label for="not_allow_ads">No</label>
                            </div>
                        </div>
                    </div>

                    <!-- Start Save Button -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" name="save" value="Add Category" class="btn btn-primary btn-lg">
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
                echo "<h1 class='text-center'>Insert Category</h1>";

                echo "<div class='container'>";

                // get values form Form after Edit
                $catname   = $_POST['name'];
                $description = $_POST['description'];
                $ordering = $_POST['ordering'];
                $visibility      = $_POST['visibility'];
                $comment = $_POST['allow_comment'];
                $ads = $_POST['allow_ads'];

                /*============= Validation Form ==============*/

                // Array to Store Errors
                $formErrors = array();

                // validate Username
                if(empty($catname)){
                    $formErrors[] = "You Must Fill Category Name Input ";
                }else{
                    if(strlen($catname) < 2){
                        $formErrors[] = "You Must type More than 2 Characters in Name Input ";
                    }
                    if(strlen($catname) > 100){
                        $formErrors[] = "You Must type Less than 15 Characters in Name Input ";
                    }
                }

                /*============= End Validation Form ==============*/

                //Update in Database If  No Errors Happened
                if(empty($formErrors)) {

                    $category_exist = checkItem('Name', 'categories', $catname);
                    if ($category_exist == 0) {

                        $stmt = $conn->prepare("INSERT INTO categories ( Name, Description, Ordering, Visibility, Allow_Comment, Allow_Ads) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->execute(array($catname, $description, $ordering, $visibility, $comment, $ads));


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
            $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

            // Select Data for This UserID
            $stmt =$conn->prepare("SELECT * FROM categories WHERE ID = ? LIMIT 1");
            $stmt->execute(array($catid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            // if There is ID
            if($count > 0) {
                ?>

                <h1 class="text-center">Edit Category</h1>
                <div class="container">

                    <form class="form-horizontal" action="?do=Update" method="post" autocomplete="off">

                        <input type="hidden" name="catid" value="<?php echo $row['ID'] ?>">

                        <!-- Start Categroy Name -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name" value="<?php echo $row['Name'] ?>" class="form-control"  required="required">
                            </div>
                        </div>

                        <!-- Start Categroy Description -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="description" value="<?php echo $row['Description'] ?>" class="form-control" >
                            </div>
                        </div>

                        <!-- Start Ordering -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Ordering</label>
                            <div class="col-sm-10 col-md-6">
                                <input type="number" name="ordering" min="1" class="form-control" value="<?php echo $row['Ordering'] ?>" >
                            </div>
                        </div>

                        <!-- Start Visibility  -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Visibility</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" id="vis-yes" name="visibility" value="0" <?php if($row["Visibility"] == 0){ echo 'checked';} ?> >
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" id="vis-no" name="visibility" value="1" <?php if($row["Visibility"] == 1){ echo 'checked';} ?> >
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>

                        <!-- Start Allow Comment  -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Comment</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" id="allow" name="allow_comment" value="0" <?php if($row["Allow_Comment"] == 0){ echo 'checked';} ?> >
                                    <label for="allow">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" id="not_allow" name="allow_comment" value="1" <?php if($row["Allow_Comment"] == 1){ echo 'checked';} ?> >
                                    <label for="not_allow">No</label>
                                </div>
                            </div>
                        </div>

                        <!-- Start Allow Ads  -->
                        <div class="form-group form-group-lg">
                            <label class="col-sm-2 control-label">Allow Ads</label>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" id="allow_ads" name="allow_ads" value="0" <?php if($row["Allow_Ads"] == 0){ echo 'checked';} ?> >
                                    <label for="allow_ads">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" id="not_allow_ads" name="allow_ads" value="1" <?php if($row["Allow_Ads"] == 1){ echo 'checked';} ?> >
                                    <label for="not_allow_ads">No</label>
                                </div>
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
            echo "<h1 class='text-center'>Update Category</h1>";

            echo "<div class='container'>";
            // Check if Access this Page From Save Button or Direct from Link
            if($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                // get values form Form after Edit
                $catid     = $_POST['catid'];
                $name   = $_POST['name'];
                $description = $_POST['description'];
                $ordering = $_POST['ordering'];
                $visibility      = $_POST['visibility'];
                $comment = $_POST['allow_comment'];
                $ads = $_POST['allow_ads'];

                /*============= Validation Form ==============*/

                // Array to Store Errors
                $formErrors = array();

                // validate Username
                if(empty($name)){
                    $formErrors[] = "You Must Fill Name Input ";
                }else{
                    if(strlen($name) < 2){
                        $formErrors[] = "You Must type More than 2 Characters in Name Input ";
                    }
                    if(strlen($name) > 15){
                        $formErrors[] = "You Must type Less than 15 Characters in Name Input ";
                    }
                }
                /*============= End Validation Form ==============*/

                //Update in Database If  No Errors Happened
                if(empty($formErrors)) {

                    $stmt2 = $conn->prepare("SELECT * FROM categories WHERE Name = ? AND ID != ?");
                    $stmt2->execute(array($name, $catid));
                    $check = $stmt2->rowCount();
                    if($check == 1){
                        $MSG = "<div class='alert alert-danger'>Sorry this Category is Exist</div>";
                        RedirectToHome($MSG, 'back');
                    }else{
                        $stmt = $conn->prepare("UPDATE categories SET Name = ?, Description = ?, Ordering = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ?
                                            WHERE ID = ?");
                        $stmt->execute(array($name, $description, $ordering, $visibility, $comment, $ads, $catid));

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
                $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

                // Check Data for This Category ID
                $check = checkItem('ID', 'categories', $catid);
                // if There is ID
                if ($check > 0) {
                    // Delete Statement
                    $stmt = $conn->prepare("DELETE FROM categories WHERE ID = ?");
                    $stmt->execute(array($catid));
                    echo "<div class='container'>";

                    $MSG = "<div class='alert alert-success'>" . $stmt->rowCount() . " Recored Deleted</div>";
                    RedirectToHome($MSG, 'back');

                    echo "</div>";

                } else {
                    echo "<div class='container'>";

                    $MSG = "<div class='alert alert-danger'>Ther is No This ID</div>";
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