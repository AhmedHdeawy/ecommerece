<?php
ob_start();

session_start();

$titleHeader = "Home";

include "init.php";
?>

    <div class="container">
        <div class="row">
            <?php

            $items = getAllFormTable('items', 'Item_ID', 'WHERE Approve = 1');
            if($items) {
                foreach ($items as $item) {
                    echo "<div class='col-sm-6 col-md-3'>";
                    echo "<div class='thumbnail item_box'>";
                    echo "<div class='price_tag'>" . $item['Price'] . " $</div>";
                    echo "<img class='img-responsive' src='item.jpg' alt='Item' />";
                    echo "<div class='caption'>";
                    echo "<h1><a href='items.php?itemid=".$item['Item_ID']."'> " . $item['Name'] . "</a></h1>";
                    echo "<p class='desc-item'>" . $item['Description'] . "</p>";
                    echo "<p class='date'>" . $item['Add_Date'] . "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            }
            else{

                echo "<div class='container'>";

                $MSG =  "<div class='alert alert-danger'>Ther is No This ID</div>";
                RedirectToHome($MSG,null, 5);  // Redirect to HomePage

                echo "</div>";
            }
            ?>
        </div>
    </div>


<?php
include $template . "footer.php";
ob_end_flush();

?>