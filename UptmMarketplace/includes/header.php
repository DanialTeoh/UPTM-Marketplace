<?php
if (!isset($_SESSION)) {
    session_start();
}

$appname = "UPTM Marketplace";

require_once("functions.php");

echo "<!DOCTYPE html>\n<html>\n<head>\n";

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $loggedin = true;
} else {
    $loggedin = false;
}

  echo "<title>$appname" . " | " . "$pagename</title>\n" .
        "<meta charset='UTF-8'>\n" .
        "<meta name='viewport' content='width=device-width, initial-scale=1'>\n" .
        "<link rel='stylesheet' href='font-awesome/css/font-awesome.min.css' />\n" .
        "<link rel='stylesheet' href='css/bootstrap.min.css'>\n" .
        "<link rel='stylesheet' href='css/styles.css' />\n" .
        "<link rel='stylesheet' href='css/forms.css' />\n" .        
        "</head>\n" .
        "<body>\n";

require_once('includes/navigation-bar.php');       

?>