<?php

$db_host = "localhost"; // Host name
$db_username = "team24"; // Mysql username
$db_password = "marketplace"; // Mysql password
$db_name = "registration"; // Database name

$connect = mysqli_connect($db_host, $db_username, $db_password, $db_name);
// Check connection
if (!$connect) {
    die("Database Connection Failed" . mysqli_error());
}

?>