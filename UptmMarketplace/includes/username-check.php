<?php
require_once('db.php');

if (isset($_POST) && !empty($_POST)) {
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $query = "SELECT * FROM `users` WHERE username='$username'";
    $result = mysqli_query($connect, $query);
    $count = mysqli_num_rows($result);
    if ($count == 1) {
        echo '<div class="text-danger">Username Not Available</div>';
    } else {
        echo '<div class="text-success">Username Available</div>';
    }
}

?>