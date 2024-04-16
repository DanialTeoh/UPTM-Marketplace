<?php
if(!isset($_SESSION['username'])) {
    session_start();
}
$pagename = "CONTACT US";
require_once('includes/functions.php');
check_cookie();

require_once('includes/header.php');
?>

    <div class="jumbotron rounded-0">
        <h2>Contact us</h2>
        <p class="lead">Send us an email and we will get back to you.</p>  
    </div>

    <br>

    <div class="container">

        <form class="form" action="" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required="required" />
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required="required" />
                </div>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <div class="input-group">
                    <textarea name="message" id="message" class="form-control" rows="9" required="required" placeholder="Message"></textarea>
                </div>
                <br>
                <div class="input-group">
                    <input type="submit" id="submit" name="submit" value="Send Message" class="btn btn-primary">
                </div>
            </div>
        </form>

    </div>

<?php
include("includes/footer.php");
?>
</body>
</html>