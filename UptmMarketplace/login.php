<?php
$pagename = "LOGIN";
require_once('includes/functions.php');
check_cookie();
redirect();
require_once('includes/header.php');
?>

    <div class="container">
    <br>

    <?php
        require_once('includes/db.php');
        if(!isset($_SESSION)) {
            session_start();
        }
        // If form submitted, insert values into the database.
    if (isset($_POST['submit']) and isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        //Checking if user exists in the database or not
        $query = "SELECT * FROM `users` WHERE username='$username' and password='".md5($password)."'";
        $result = mysqli_query($connect, $query) or die(mysqli_error());
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            $_SESSION['username'] = $username;
            $_SESSION['loggedin'] = true;

            $profile = mysqli_fetch_assoc($result);
            $_SESSION['id'] = $profile['id'];
            $_SESSION['firstname'] = $profile['firstname'];
            $_SESSION['lastname'] = $profile['lastname'];
            $_SESSION['email'] = $profile['email'];

            if (isset($_POST['remember'])) {
                //setting the relevant cookies
                setcookie("username", $username, time()+86400, "/");
                setcookie("password", md5($password), time()+86400, "/");
            }

            header("Location: index.php");
        } else {
            echo '<div class="jumbotron alert-danger">
            <h3>Failed to login.</h3>
            <div class="lead">Incorrect username or password</div>
            <div class="lead">Click here to <a href="login.php">try again</a></div>
            </div>';
        }
    } else {
        ?>
    
    <form class="form bg-light" action="" method="post" name="login">
    <h2>Login to your account</h2>
    <br>
    <div class="form-group">
    <label for="username">User Name</label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
        <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username">
        </div>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-unlock" aria-hidden="true"></i></span>
        <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
        </div>
    </div>
    <div class="form-group">
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Remember me</label>
    </div>
    <button type="submit" name="submit" class="btn btn-info">Login</button>
    </form>

    <br>

    <p>Not registered yet? <a href="register.php">Register Here</a></p>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <?php } ?>

<?php
require_once("includes/footer.php");
?>
</body>
</html>