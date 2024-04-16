<?php
if(!isset($_SESSION['username'])) {
    session_start();
}
$pagename = "RULES";
require_once('includes/functions.php');
check_cookie();

require_once('includes/header.php');
?>

    <div class="jumbotron rounded-0">
        <div class="container">
            <h1>UPTM Marketplace Rules and Regulations</h1>
        </div>
    </div>

    <div class="container">

        <article>
            <h2>Basic Rules</h2>
            <ol>
                <li>You must be a student at the host school in order to participate.</li>
                <li>You must provide valid credit card information upon purchase.</li>
                <li>You must provide a clear, honest, and accurate description of your item.</li>
                <li>The item delivered must match the item described.</li>
                <li>You must abide by the terms of delivery set forth by the seller.</li>
            </ol>
            <h2>Prohibited Items</h2>
            <ol>
                <li>Chance listings that promote giveaways, random drawings, raffles, or prizes.</li>
                <li>Firearms, weapons, and knives.</li>
                <li>Offensive material, which includes items that are racially or ethnically inappropriate.</li>
                <li>Drugs and medications.</li>
                <li>Alcohol.</li>
                <li>Replicas, counterfeit items, and unauthorized copies.</li>
            </ol>
        </article>

    </div>

<?php
require_once("includes/footer.php");
?>
</body>
</html>