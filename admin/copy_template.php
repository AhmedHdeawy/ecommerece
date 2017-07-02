<?php


ob_start();

session_start();
// We Need Navbar Here, So we type { true }
$navbar =  true;
$titleHeader = "";

if(isset($_SESSION['username'])) {
    include "init.php";

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if($do == "Manage"){
        echo "Welcome";
    }
    elseif ($do == 'Add'){

    }
    elseif($do == 'Insert'){

    }
    elseif($do == 'edit'){

    }
    elseif ($do == 'Update'){

    }
    elseif ($do == 'Delete'){

    }
    // Calling Footer Scripts
    include $template . "footer.php";

}
else{
    header("Location: index.php");
}


ob_end_flush();


?>