<?php
ob_start();


session_start();
$titleHeader = "New Item";

include "init.php";

if($_SESSION['user']) {

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country = filter_var($_POST['country_made'], FILTER_SANITIZE_STRING);
        $status = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);

        $formErrors = array();

        if(strlen(trim($name)) < 4){
            $formErrors[] = "you must type more than 4 characters in Name Item";
        }

        if(strlen(trim($description)) < 20){
            $formErrors[] = "you must Describe Item with more than 20 characters";
        }

        if(empty($price)){
            $formErrors[] = "you must Type Price of Item";
        }

        if(strlen(trim($country)) < 2){
            $formErrors[] = "you must type full name in Country Input";
        }

        if(empty($status)){
            $formErrors[] = "you must Specific Status for Item";
        }

        if(empty($category)){
            $formErrors[] = "you must Choose Category for this item";
        }

        if(empty($formErrors)){

            $stmt = $conn->prepare("INSERT INTO items (Name, Description, Price, Country_Made, Status, Add_Date, Member_ID, Cat_ID) 
                                            VALUES(:name, :description, :price, :country, :status, now(), :member_id, :cat_id)");
            $stmt->execute(array(
                'name'         =>   $name,
                'description'  =>   $description,
                'price'        =>   $price,
                'country'      =>   $country,
                'status'       =>   $status,
                'member_id'    =>   $_SESSION['userid'],
                'cat_id'       =>   $category
            ));

            if($stmt){
                echo "<div class='alert alert-success'>Item Added</div>";
            }

        }


    }

?>

    <div class="create-ads">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">Details</div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">

                                <!-- Start name -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-10 col-md-8">
                                        <input
                                            pattern=".{5,}"
                                            title="This Field Require at least 5 Characters"
                                            type="text" name="name" class="form-control live-name" autocomplete="off" required="required">
                                    </div>
                                </div>

                                <!-- Start Description -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10 col-md-8">
                                        <textarea name="description" class="form-control live-desc" autocomplete="off" required="required"></textarea>
                                    </div>
                                </div>

                                <!-- Start Price -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Price</label>
                                    <div class="col-sm-10 col-md-8">
                                        <input type="text" name="price" class="form-control live-price" autocomplete="off" required="required">
                                    </div>
                                </div>

                                <!-- Start Caountry -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-label">Made in </label>
                                    <div class="col-sm-10 col-md-8">
                                        <input type="text" name="country_made" class="form-control" autocomplete="off" required="required">
                                    </div>
                                </div>

                                <!-- Start Status -->
                                <div class="form-group form-group-lg">
                                    <Label class="col-sm-2 control-label">Status</Label>
                                    <div class="col-sm-10 col-md-8">
                                        <select name="status" class="form-control" required>
                                            <option value="">...</option>
                                            <option value="1">New</option>
                                            <option value="2">Like New</option>
                                            <option value="3">Used</option>
                                            <option value="4">Old</option>
                                        </select>
                                    </div>
                                </div>


                                <!-- Start Status -->
                                <div class="form-group form-group-lg">
                                    <Label class="col-sm-2 control-label">Categroies</Label>
                                    <div class="col-sm-10 col-md-8">
                                        <select name="category" class="form-control" required>
                                            <option value="">...</option>
                                            <?php

                                            $category = getAllFormTable('categories', 'ID');

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
                        <div class="col-md-4">
                            <div class='thumbnail item_box live-item'>";
                                <div class='price_tag'>$</div>
                                <img class='img-responsive' src='item.jpg' alt='Item' />
                                <div class='caption'>
                                    <h3>Title</h3>
                                    <p>Description</p>
                                    </div>
                            </div>
                        </div>
                    </div>

                    <?php
                        if(!empty($formErrors)) {
                            foreach ($formErrors as $error) {
                                echo "<div class='alert alert-danger'>.$error.</div>";
                            }
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