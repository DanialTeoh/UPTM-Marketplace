<?php
if(!isset($_SESSION['username'])) {
    session_start();
}
$pagename = "PROFILE";
require_once('includes/functions.php');
check_cookie();
authenticate();

require_once('includes/header.php');

require_once('includes/db.php');

if(isset($_POST['deleteyes'])) {
    $iddelete1 = $_POST['deleteyes'];
    $query12 = "DELETE FROM `users` WHERE username='$iddelete1'";
    $result12 = mysqli_query($connect, $query12);
    session_destroy();
    header("Location: index.php");
} elseif(isset($_POST['deleteno'])) {
    header("Location: profile.php");
}

if(isset($_POST['deleteimgid'])) {
    $iddelete = $_POST['deleteimgid'];
    $query3 = "UPDATE `users` SET profimg=NULL WHERE username='$iddelete'";
    $result3 = mysqli_query($connect, $query3);
}
?>

<html>
<div class="jumbotron rounded-0 p-tron">
    <div class="container">
        <div id="refreshpic">
            <h1><?php echo ' I am <span id="firstname">' . $_SESSION['firstname'] . '</span>'; ?></h1>
            <?php
            $user = $_SESSION['username'];
            $query = "SELECT * FROM `users` WHERE username='$user'";
            $result = mysqli_query($connect, $query);
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                if(isset($row['profimg']) && !empty($row['profimg'])) {
                    echo '<img src="'.$row['profimg'].'" height="200" width="200">';
                } else {
                    echo '<img src="img/profimg/generic-profile.jpg" height="200" width="200">';
                }
            }
            ?>
        </div>
    </div>
</div>

<div class='container'>
    <table class="table table-bordered" id="profile_table">
        <tr>
            <th>Name</th>
            <td><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname']; ?></td>
        </tr>
        <tr>
            <th>Username</th>
            <td><?php echo $_SESSION['username'] ?></td>
        </tr>
        <tr>
            <th>Email address</th>
            <td><?php echo $_SESSION['email'] ?></td>
        </tr>
    </table>
</div>

<br>

<div class="container">
    <button type="button" class="btn btn-danger btn-sm float-left" data-toggle="modal" data-target="#deleteModal">Delete Account</button>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">There is no turning back!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete your account?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="profile.php" class="deleteuser" id="<?php echo $_SESSION["username"]; ?>">
                        <button type="submit" name="deleteyes" value="<?php echo $_SESSION["username"]; ?>" class="btn btn-danger">Delete Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br><br><br><br>

    <?php
    $user = $_SESSION['username'];
    echo '
    <h3>Update account Information</h3>
    <h4 class="lead">Upload Profile Picture</h4>
    <form action="includes/profupload.php" method="POST" enctype="multipart/form-data" class="picupload form-inline">
        <input type="file" name="image" class="image form-control-file"/>
        <button type="submit" class="uploadsubmit btn btn-info">Upload</button>
        <input type="hidden" name="idprofimg" value="'.$user.'">
    </form>
    <br>
    <form action="profile.php" method="POST" class="del_pic" id="'.$user.'">
        <button type="submit" name="deleteimgid" value="'.$user.'" class="btn btn-danger btn-sm float-left">Delete Profile Photo</button>
    </form>
    <br>
    <form class="form" id="update_form">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" class="form-control" name="firstname" id="xfirstname" placeholder="Enter your first name">
                </div>
            </div>
            <div class="col">
                <div class="form-group" method="">
                    <label for="lastname">Last Name</label>
                    <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Enter your last name">
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="password">New password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter desired password">
        </div>
        <br>
        <div class="form-group">
            <h4>Verify changes with current password</h4>
            <label for="oldpassword">Current password</label>
            <input type="password" class="form-control" name="oldpassword" id="oldpassword" placeholder="Enter current password">
        </div>
        <input type="submit" name="insert" id="insert" value="Insert" class="btn btn-info" />
    </form>
</div>
<br>';
include("includes/footer.php");
?>

<script>
    $(document).ready(function () {
        $('#update_form').on("submit", function (event) {
            event.preventDefault();
            $.ajax({
                url: "pform.php",
                method: "POST",
                data: $('#update_form').serialize(),
                beforeSend: function () {
                    $('#insert').val("Updating...");
                },
                success: function (data) {
                    $('#insert').val("Insert");
                    $('#update_form')[0].reset();
                    $('#profile_table').html(data);
                }
            })
        });
    });

    $("body").on("submit", ".picupload", function (e) {
        e.preventDefault();
        if ($(this).children('input')[0].files.length > 0) {
            $.ajax({
                type: "POST",
                url: "includes/profupload.php",
                data: new FormData(this),
                cache: false,
                contentType: false,
                processData: false,
                success: function (response, textStatus, jqXHR) {
                    $result = $(response).find("#refreshpic");
                    $("#refreshpic").html($result);
                }
            })
        }
    });

    $("body").on("submit", ".del_pic", function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "profile.php",
            dataType: "html",
            data: {deleteimgid: e.target.id},
            success: function (response) {
                $result2 = $(response).find("#refreshpic");
                $("#refreshpic").html($result2);
            }
        })
    });
</script>
<script>
    function action() {
        document.getElementById('deleteprof').style.visibility = 'hidden';
    };
</script>
</body>
</html>
