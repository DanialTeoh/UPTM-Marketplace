<?php
date_default_timezone_set('America/New_York');
$pagename = "MESSAGES";
require_once('includes/functions.php');
check_cookie();
authenticate();

require_once('includes/header.php');
?>

<div class="container">
<br>

<?php
if (isset($_GET['view'])) {
    $view = sanitizeString($_GET['view']);
} else {
    $view = $username;
}

if (isset($_POST['text'])) {
    $text = sanitizeString($_POST['text']);

    if ($text != "") {
        $pm   = substr(sanitizeString($_POST['pm']), 0, 1);
        $time = time();
        queryMysql("INSERT INTO messages VALUES(NULL, '$username', '$view', '$pm', $time, '$text')");
    }
}

if ($view != "") {
    if ($view == $username) {
        $name1 = $name2 = "Your";
    } else {
        $name1 = "<a href='members.php?view=$view'>$view</a>'s";
        $name2 = "$view's";
    }

    echo "<h3>$name1 Messages</h3>";
    // showProfile($view);
  
    echo <<<_END
    <form method='post' action='messages.php?view=$view'>
    <p class='lead'>Type here to leave a message:</p>
    <textarea class='col-9' name='text' rows='5'></textarea><br>
    <input type='radio' name='pm' value='0' id='public' checked='checked'><label for='public'>Public</label>
    <input type='radio' name='pm' value='1' id='private'><label for='private'>Private</label><br>
    <input type='submit' value='Post Message' class='btn btn-info'></form><br>
_END;

if (isset($_GET['erase'])) {
    $erase = sanitizeString($_GET['erase']);
    queryMysql("DELETE FROM messages WHERE id=$erase AND recip='$username'");
}
  
$query  = "SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC";
$result = queryMysql($query);
$num    = $result->num_rows;
  
for ($j = 0; $j < $num; $j++) {
    $row = $result->fetch_array(MYSQLI_ASSOC);

    echo"<div class='messages col-9 mt-1'>";

    if ($row['pm'] == 0 || $row['auth'] == $username || $row['recip'] == $username) {
        echo "<span class='text-muted small'>" . date('M jS \'y g:ia:', $row['time']) . "</span>";
        echo " <a href='messages.php?view=" . $row['auth'] . "'>" . $row['auth']. "</a>\n ";

        if ($row['pm'] == 0) {
            echo "wrote: &quot;" . $row['message'] . "&quot; ";
        } else {
            echo "whispered: &quot;" .
            $row['message']. "&quot;</span>\n ";
        }

        if ($row['recip'] == $username) {
            echo "<a class='text-danger' href='messages.php?view=$view" .
            "&erase=" . $row['id'] . "'>[delete]</a>\n";
        }
        echo "</div>\n";
        }
    }
}

if (!$num) {
    echo "<br>\n<span class='lead'>No messages yet</span>\n<br>\n<br>\n";
}

echo "<br>\n<br>\n
<a class='btn btn-info' href='messages.php?view=$view'>\n
Refresh messages</a>\n
<br>\n
";
?>


<br>
</div>

<?php
require_once("includes/footer.php");
?>
</body>
</html>