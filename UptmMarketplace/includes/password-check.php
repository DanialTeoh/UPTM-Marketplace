<?php
require_once('db.php');

if (isset($_POST) && !empty($_POST)) {
    $password = mysqli_real_escape_string($connect, $_POST['password']);
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    if (strlen($password) < 8) {
        echo '<div class="text-danger">Password must be 8 characters long.</div>';
    }elseif (!preg_match("/[A-z]/", $password)) {
        echo '<div class="text-danger">Password must contain a letter.</div>';
    }elseif (!preg_match("/[A-Z]/", $password)) {
        echo '<div class="text-danger">Password must one capital letter.</div>';
    }elseif (!preg_match("/\d/", $password)) {
        echo '<div class="text-danger">Password must one number.</div>';
    }elseif (strcmp($username, $password)==0) {
        echo '<div class="text-danger">Your password cannot be the same as your username.</div>';
    }else{
    	echo '<div class="text-success">Valid password.</div>';
    }
}

?>