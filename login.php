<?php
    session_start();
    $titleHeader = "Login";

    if(isset($_SESSION['user'])){
        header('Location: index.php');
        exit();
    }

    // Include Require Files
    include "init.php";



    // Check if user coming Request Method
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        // Declare Array to Store Errors
        $formErrors = array();

        // Dealing With Login Form
        if(isset($_POST['login']) == 'login') {

            // Get values from Inputs
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedpass = sha1($pass);

            // Check from User is Exist or no
            $stmt = $conn->prepare("SELECT UserID, Username, Password FROM users WHERE Username=? AND Password=?");
            $stmt->execute(array($user, $hashedpass));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            // if Exist Store it in Session and Move user to index Page
            if ($count > 0) {
                $_SESSION['user'] = $user;
                $_SESSION['userid'] = $row['UserID'];
                header('Location: index.php');
                exit();
            }else{
                $formErrors[] = "Username/Password Incorrect";
            }
        }
        // Dealing WIth SignUp Form
        else{

            $username = $_POST['username'];
            $password = $_POST['password'];
            $password_again = $_POST["password_again"];
            $email = $_POST["email"];

            // Sanitize Values
            $username = filter_var($username, FILTER_SANITIZE_STRING);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);

            /*=======
                 Validation Inputs and Store Errors
            ========*/
            if(strlen(trim($username)) < 2 ){
                // if less than 2 characters then Errors
                $formErrors[] = "Must be Contain at least 2 characters";
            }

            if(strlen(trim($password)) < 4 || strlen(trim($password)) > 16){
                $formErrors[] = "Password Must be between 4 & 16 Characters";
            }
            else{
                // Encrypt Password if Input Correct
                $password = sha1($password);
                $password_again = sha1($password_again);
            }

            // if Password and Confirm are not Compatible
            if($password !== $password_again){

                $formErrors[] = "Password Doesn't Match";

            }

            if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
                $formErrors[] = "Email Not Validate";
            }

            /*=======
                End Validation Inputs
            ========*/

            // Check Whether there is errors or No

            if(empty($formErrors)){

                $check = checkItem('Username', 'users', $username);
                if($check == 1){

                    $formErrors[] = "Sorry This User is Exist Please Change it";
                }else{

                    $stmt = $conn->prepare("INSERT INTO users (Username, Password, Email, Date) 
                                                        VALUES (?, ?, ?, now())");
                    $stmt->execute(array($username, $password, $email));

                    $success = "Congratulation You are User Now";
                }

            }
        }
    }

?>


<div class="container login-page">

    <h1 class="text-center">
        <span class="active" data-class="login">Login</span> | <span data-class="signup">Register</span>
    </h1>

    <!-- Login From -->
    <form class="login" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

        <div class="input_control">
            <input type="text" class="form-control" name="username" placeholder="Type Your Username" autocomplete="off" required>
        </div>

        <div class="input_control">
            <input type="password" class="form-control" name="password" placeholder="Type your Password" autocomplete="new-password" required>
        </div>

        <input type="submit" class="btn btn-primary btn-block" value="login" name="login">
    </form>


    <!-- SignUp Form -->
    <form class="signup" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"
          method="post">

        <div class="input_control">
            <input type="text"
                   pattern=".{2,}" title="Username Must be more than 2 Characters"
                   class="form-control" name="username" placeholder="Type Your Username"
                   autocomplete="off" required>
        </div>

        <div class="input_control">
            <input type="password"
                   minlength="4"
                   maxlength="16"
                   class="form-control" name="password" placeholder="Type a complex Password"
                   autocomplete="new-password" required>
        </div>
        <div class="input_control">
            <input type="password"
                   minlength="4"
                   maxlength="16"
                   class="form-control" name="password_again" placeholder="Type Password again"
                   autocomplete="new-password" required>
        </div>

        <div class="input_control">
            <input type="email"
                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"
                   title="Email Not Validate"
                   class="form-control" name="email" placeholder="Type a valid Email" autocomplete="on"
                   required>
        </div>

        <input type="submit" class="btn btn-success btn-block" value="SignUp" name="signup">
    </form>

    <!-- End Form Register -->


    <!-- Show Errors -->

    <div class="errors text-center">
        <ul class="list-group list-unstyled">
            <?php
                if(!empty($formErrors)) {
                    foreach ($formErrors as $error) {
                        echo "<li class='list-group-item-warning'>".$error."</li>";
                    }
                }

                if(isset($success)){
                    echo "<li class='list-group-item-warning'>".$success."</li>";
                }
            ?>
        </ul>
    </div>

</div>


<?php include $template . "footer.php"; ?>
