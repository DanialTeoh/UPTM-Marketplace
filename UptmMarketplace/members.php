<?php
if (!isset($_SESSION['username'])) {
    session_start();
}
$pagename = "MEMBERS";
require_once('includes/functions.php');
check_cookie();
authenticate();

require_once('includes/header.php');
?>

    <div class="jumbotron rounded-0">
        <h2>Members</h2>
        <div class="lead">Find other members here</div>
    </div>

<div class="container">
<br>

<?php

require_once('includes/db.php');

if (isset($_GET['view'])) {
    $view = $_GET['view'];
    if ($view == $username) {
        $name = "Your";
    } else {
        $name = "$view's";
    }
    echo "<div class='card w-50'><div class='card-header'>" . 
    "<h3>$name Profile</h3></div><div class='card-block container'>";
    showProfile($view);
    echo "<br><a class='btn btn-info' href='messages.php?view=$view'>" .
    "<i class='fa fa-envelope' aria-hidden='true'></i>" . 
    "View $name messages</a>" . 
    "<br><br></div></div>";
    die("</div></body></html>");
}

if (isset($_GET['add'])) {
    $add = sanitizeString($_GET['add']);

    $result = queryMysql("SELECT * FROM friends WHERE username='$add' AND friend='$username'");
    if (!$result->num_rows) {
        queryMysql("INSERT INTO friends (username, friend) VALUES ('$add', '$username')");
    }
} elseif (isset($_GET['remove'])) {
    $remove = sanitizeString($_GET['remove']);
    queryMysql("DELETE FROM friends WHERE username='$remove' AND friend='$username'");
}

$result = queryMysql("SELECT username FROM users ORDER BY username");
$num    = $result->num_rows;

echo "<h3>Other Members</h3><ul><br>";

for ($j = 0; $j < $num; $j++) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    if ($row['username'] == $username) {
        continue;
    }
    
    echo "<li><div><i class='fa fa-caret-right' aria-hidden='true'></i> <a class='lead' href='members.php?view=" .
    $row['username'] . "'>" . $row['username'] . "</a>";
    $follow = "follow";

    $result1 = queryMysql("SELECT * FROM friends WHERE
      username='" . $row['username'] . "' AND friend='$username'");
    $t1      = $result1->num_rows;
    $result1 = queryMysql("SELECT * FROM friends WHERE
      username='$username' AND friend='" . $row['username'] . "'");
    $t2      = $result1->num_rows;

    if (($t1 + $t2) > 1) {
        echo " <span class='lead'><i class='fa fa-arrows-h' aria-hidden='true'></i> is a mutual friend</span>";
    } elseif ($t1) {
        echo " <span class='lead'><i class='fa fa-long-arrow-left' aria-hidden='true'></i> Already following</span>";
    } elseif ($t2) {
        echo " <span class='lead'><i class='fa fa-long-arrow-right' aria-hidden='true'></i> is following you</span>";
        $follow = "follow back";
    }
    
    if (!$t1) {
        echo " [<a href='members.php?add="   .$row['username'] . "'>$follow</a>]</div></li><br>";
    } else {
        echo " [<a href='members.php?remove=".$row['username'] . "'><i class='fa fa-times text-danger' aria-hidden='true'></i></a>]</div></li><br>";
    }
}
?>

</div>

<?php
require_once("includes/footer.php");
?>
</body>
</html>