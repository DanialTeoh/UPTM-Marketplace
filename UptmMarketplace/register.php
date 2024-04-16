<?php
$pagename = "REGISTER";
require_once('includes/functions.php');
check_cookie();
redirect();

require_once('includes/header.php');
?>

    <div class="container">

        <br>

        <?php
        require('includes/db.php');
        // If form submitted, insert values into the database.
        if (isset($_POST['username']) && (isset($_POST['password']) == isset($_POST['password2']))){
            $firstname = stripslashes($_POST['firstname']);
            $lastname = stripslashes($_POST['lastname']);
            $username = stripslashes($_POST['username']);
            $email = stripslashes($_POST['email']);
            $unhashedpass = stripslashes($_POST['password']);
            $password = stripslashes(md5($_POST['password']));

            if($firstname=='' ||$lastname=='' || $username=='' || $email==''){
                echo '<div class="text-danger">Your form was missing information.</div>';
                echo '<a href="register.php">Return to Registration</a>';
            }elseif((strlen($unhashedpass) < 8)|| (!preg_match("/[A-z]/", $unhashedpass)) || (!preg_match("/[A-Z]/", $unhashedpass)) || (!preg_match("/\d/", $unhashedpass)) || (strcmp($username, $unhashedpass)==0)) {
                echo '<div class="text-danger">You have submitted an invalid password.'.$password.'</div>';
                echo '<a href="register.php">Return to Registration</a>';
            }else{
                $query = "INSERT INTO `users` (firstname, lastname, username, password, email) VALUES ('$firstname', '$lastname', '$username', '$password', '$email')";
                $usercheck = "SELECT * FROM `users` WHERE username='$username'";
                $result = mysqli_query($connect, $usercheck);
                $count = mysqli_num_rows($result);
                if ($count == 1) {
                    echo '<div class="lead text-danger">Please use another username</div>
                    <div class="lead">Click here to <a href="register.php">try again</a></div>';
                } else {
                    $result = mysqli_query($connect, $query);
                    if($result){
                        echo '<div class="jumbotron alert-success">
                        <h3>Registered successfully.</h3>
                        <div class="lead">Click here to <a href="login.php">login</a></div>
                        </div>';
                    } else { 
                        echo '<div class="jumbotron alert-danger">
                        <h3>Failed to register.</h3>
                        <div class="lead">Click here to <a href="register.php">try again</a></div>
                        </div>';
                    }
                }
            }
        } else {
        ?>

        <form class="form bg-light" action="" method="POST" name="registration">
            <h2>Register for an account</h2>
            <br>
            <div class="row">
            <div class="col">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Enter your first name">
            </div>
            </div>
            <div class="col">
            <div class="form-group" method="POST">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter your last name">
            </div>
            </div>
            </div>
            <div class="form-group">
                <label for="username">User Name</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                <input type="text" class="form-control" name="username" id="username" placeholder="Enter desired username">
                </div>
                <span id="usernameResult"></span>
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter your email address">
                </div>
                <span id="emailResult"></span>
                <small id="emailHelp" class="form-text text-muted">We won't share your email with anyone.</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter desired password">
                </div>
                <span id="passwordResult"></span>
                <small class="form-text text-muted">Password must contain: one letter, one number, one capital letter, and at least 8 characters.</small>
                <small class="form-text text-muted">Your password cannot be the same as your username.</small>
            </div>
            <div class="form-group">
                <label for="password2">Verify password</label>
                <input type="password" class="form-control" name="password2" id="password2" placeholder="Re-enter password">
            </div>
            <button type="submit" name="submit" class="btn btn-info">Register</button>
        </form>
    </div>

    <br>


    <?php
    require_once("includes/footer.php");
    ?>
    <script>
        $(document).ready(function () {
            $('#usernameLoading').hide();
            $('#username').keyup(function () {
                $('#usernameLoading').show();
                $.post("includes/username-check.php", {
                    username: $('#username').val()
                }, function (response) {
                    $('#usernameResult').fadeOut();
                    setTimeout("finishAjax('usernameResult', '" + escape(response) + "')", 400);
                });
                return false;
            });
        });

        function finishAjax(id, response) {
            $('#usernameLoading').hide();
            $('#' + id).html(unescape(response));
            $('#' + id).fadeIn();
        } //finishAjax
        //------------------AJAX for password check
        $(document).ready(function () {
            $('#passwordLoading').hide();
            $('#password').keyup(function (){
                $('#passwordLoading').show();
                $.post("includes/password-check.php", {
                    password: $('#password').val(), username: $('#username').val()
                }, function (response) {
                    $('#passwordResult').fadeOut();
                    setTimeout("finishAjax2('passwordResult', '" + escape(response) + "')", 400);
                });
                return false;
            });
        });
        function finishAjax2(id, response) {
            $('#passwordLoading').hide();
            $('#' + id).html(unescape(response));
            $('#' + id).fadeIn();
        } //finishAjax
        //------------------AJAX for email check
        $(document).ready(function () {
            $('#emailLoading').hide();
            $('#email').keyup(function (){
                $('#emailLoading').show();
                $.post("includes/email-check.php", {
                    email: $('#email').val()
                }, function (response) {
                    $('#emailResult').fadeOut();
                    setTimeout("finishAjax3('emailResult', '" + escape(response) + "')", 400);
                });
                return false;
            });
        });
        function finishAjax3(id, response) {
            $('#emailLoading').hide();
            $('#' + id).html(unescape(response));
            $('#' + id).fadeIn();
        } //finishAjax
    </script>
<?php } ?>

</body>
</html>

