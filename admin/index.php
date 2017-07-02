<?php
session_start();
    // We don't Need Navbar Here, So we type  { false }
    $navbar = false;

    // Determine  Title of Page
$titleHeader = "Admin Login";

if(isset($_SESSION['admin'])){
    header('Location: dashboard.php');
    exit();
}

include "init.php";

// Check if user coming Request Method

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $user = $_POST['user'];
        $pass = $_POST['pass'];
        $hashedpass = sha1($pass);

        $stmt = $conn->prepare("SELECT UserID, Username, Password FROM users WHERE Username=? AND Password=? AND GroupID=1  LIMIT 1");
        $stmt->execute(array($user, $hashedpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if($count > 0){
            $_SESSION['admin'] = $user;
            $_SESSION['userid'] = $row['UserID'];
            header('Location: dashboard.php');
            exit();
        }


    }



?>


<form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

    <h4 class="text-center">Admin Login</h4>
    <input type="text" class="form-control" name="user" placeholder="Username" autocomplete="off">
    <input type="password" class="form-control" name="pass" placeholder="Password" autocomplete="new-password">

    <input type="submit" class="btn btn-primary btn-block" value="Login">

</form>

<?php
include $template . "footer.php";
?>