<?php
require_once('db.php');

if (isset($_POST) && !empty($_POST)) {
    $email = mysqli_real_escape_string($connect, $_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<div class="text-danger">Please enter a valid email address (name@domain.com).</div>';
    }else{
    	echo '<div class="text-success">Email is valid.</div>';
    }
}

?>