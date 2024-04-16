<?php

if(!isset($_SESSION['username'])) {
    session_start();
}

require_once('includes/functions.php');
check_cookie();
authenticate();

        require_once('includes/db.php');
        if(!empty($_POST))
        {
            $output = '';
           
            $username = $_SESSION['username'];
            
                        $query = "SELECT * FROM `users` WHERE username='$username'";
                        $result = mysqli_query($connect, $query) or die(mysqli_error());
                        $profile = mysqli_fetch_assoc($result);

                        $firstname = $profile['firstname'];
                        $lastname = $profile['lastname'];
                        

                        $passwordcheck = $profile['password'];
                        $passwordconfirm = stripslashes(md5($_POST['oldpassword']));
            
                        if ($passwordconfirm == $passwordcheck) {
            
                            if(empty($_POST['firstname'])){
                                $firstname = $profile['firstname'];
                            }
                            else{
                                $firstname = stripslashes($_POST['firstname']);
                            }
                            if(empty($_POST['lastname'])){
                                $lastname = $profile['lastname'];
                            }
                            else{
                                $lastname = stripslashes($_POST['lastname']);
                            }
                            if(empty($_POST['password'])){
                                $password = $profile['password'];
                            }
                            else{
                                $password = stripslashes(md5($_POST['password']));
                                $oldpassword = stripslashes(md5($_POST['oldpassword']));
                            }
                            
                            $update = "UPDATE users 
                            SET
                            firstname = '$firstname', 
                            lastname = '$lastname', 
                            password = '$password'
                            WHERE username = '$username'
                            ";

                            $result2 = mysqli_query($connect, $update);
                            if (mysqli_query($connect, $update)) {
                            $_SESSION['firstname'] = $firstname;
                            $_SESSION['lastname'] = $lastname;

                            
                            
                            

                            ?>

                        
    
    <script>
        document.getElementById("navname").innerHTML = "<?php echo $firstname ?>";
        document.getElementById("firstname").innerHTML = "<?php echo $firstname ?>";
    </script>

    <table class="table table-bordered" id="profile_table">
        <tr>
            <th>Name</th>
            <td>
                <?php echo $firstname . " " . $lastname ?>
            </td>
        </tr>
        <tr>
            <th>Username</th>
            <td>
                <?php echo $_SESSION["username"] ?>
            </td>
        </tr>
        <tr>
            <th>Email address</th>
            <td>
                <?php echo $_SESSION["email"] ?>
            </td>
        </tr>
    </table>

    <?php
                            // header("Refresh:1");
                            // header("Location: profile.php");
                        } else {
                            echo '<div class="alert alert-danger">
                            <h3>Error updating profile.</h3>
                            <div>Error: ' . mysqli_error($connect); 
                            echo '</div></div>';
                        }
                    } else { 

                        

                        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        <h3>Incorrect Password.</h3>
                        <div>Please verify changes with your current password</div>
                        </div>';

                        ?>

        <?php
            
        }
    }

?>


