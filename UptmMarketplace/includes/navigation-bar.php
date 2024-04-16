<nav class="navbar navbar-expand-sm">
    <a class="navbar-brand" href="index.php">
    <img src="img/uptmmarketplace1.png" alt="Logo" width="100" height="100">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse">
        <div class="navbar-nav">
            <?php
            if (isset($_SESSION["username"])) {
                echo
                "<a class='nav-item nav-link' href='members.php' title='Members' alt='Members'><i class='fa fa-users' aria-hidden='true'></i></a>\n 
                <a class='nav-item nav-link' href='friends.php' title='Friends' alt='Friends'><i class='fa fa-address-book' aria-hidden='true'></i></a>\n
                <a class='nav-item nav-link' href='messages.php' title='Messages' alt='Messages'><i class='fa fa-envelope' aria-hidden='true'></i></a>\n
                ";
            }
            ?>
        </div>
        <ul class="navbar-nav ml-auto">
            <?php
                if (!isset($_SESSION["username"])) {
                        echo
                        "<li class='nav-item'>
                        <a class='nav-link' href='login.php'>Login</a>\n
                        </li>\n
                        <li class='nav-item'>\n
                        <a class='nav-link' href='register.php'>Register</a>\n
                        </li>\n";
                }
                ?>
                <?php
                if (isset($_SESSION["username"])) {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="marketplace.php" title='Marketplace' alt='Marketplace'><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="myproducts.php" title='My Products' alt='My Products'><i class="fa fa-archive" aria-hidden="true"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="navname" href="profile.php" title='My Profile' alt='My Profile'>
                            <?php echo $_SESSION['firstname'] ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="includes/logout.php" title='Logout' alt='Logout'><i class='fa fa-sign-out' aria-hidden='true'></i></a>
                    </li>
                    <?php ;
                }
                ?>
        </ul>
    </div>
</nav>
