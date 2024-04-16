<?php
if(!isset($_SESSION['username'])) {
    session_start();
}
$pagename = "HOME";
require_once('includes/functions.php');
check_cookie();

require_once('includes/header.php');
?>

    <div class="jumbotron rounded-0">
        <div class="container">
            <?php 
            if(!empty($_SESSION["username"])) {
                echo '<h1>Welcome Back to UPTM Marketplace</h1> 
                <p class="text-lead">Go to your <a href="profile.php">profile</a> to begin!</p>';
            }
            ?>
            <?php 
            if(!isset($_SESSION["username"])) {
                echo '<h1>Welcome to UPTM Marketplace</h1>
                <p class="lead">Login to begin buying and selling.</p>
                <a class="btn btn-info" href="login.php">Login</a>
                <a class="btn btn-dark" href="register.php">Register</a>';
            }
            ?>
        </div>
    </div>

    <div class="container">

        
        
        
        <div class="why">
            <p><strong>Why Use UPTM Marketplace? <br> <small class="text-muted"><em>Buying</em> and <em>selling</em> made safe and easy.</small> </strong></p>
            <p></p>

            <article>
            <p>
                UPTM Marketplace is an online marketplace desgined specifically for UPTM students. <br> Upload items to your profile that you
                want to sell, or browse others' profiles for things to buy.
            </p>
            
            
        </article>

            <div class="card-deck">
                <div class="card">
                    <img class="card-img-top" src="img/Money.PNG" alt="Card image cap">
                    <div class="card-body">
                        <h4 class="card-title">Get the Best Price</h4>
                        <p>We help you get more money for your things by connecting you directly with a buyer. </p>
                        <p>
                            <a href="myproducts.php"><small class="text-muted">Sell Your Things</small></a>
                        </p>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top" src="img/Save.PNG" alt="Card image cap" height="289">
                    <div class="card-body">
                        <h4 class="card-title">Save Tons</h4>
                        <p class="card-text">We save you the time of buying from a main distributor, saving you up to 50%.</p>
                        <p class="card-text">
                            <a href="marketplace.php"><small class="text-muted">Browse the Marketplace</small></a>
                        </p>
                    </div>
                </div>
                <div class="card">
                    <img class="card-img-top" src="img/Safe.PNG" alt="Card image cap" height="289">
                    <div class="card-body">
                        <h4 class="card-title">Safety Guaranteed</h4>
                        <p class="card-text">Our platform ensures the safety of the students by including faculty to oversee each transaction.</p>
                        <p class="card-text">
                            <a href="rules.php"><small class="text-muted">Read our Rules</small></a>
                        </p>
                    </div>
                </div>
            </div>




</div>

</div>

<?php
require_once("includes/footer.php");
?>
</body>
</html>