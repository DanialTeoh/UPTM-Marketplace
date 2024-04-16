<?php
if (!isset($_SESSION)) {
    session_start();
}
$pagename = "MARKETPLACE";
require_once('includes/functions.php');
check_cookie();
authenticate();
require_once('includes/header.php');

?>
    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

    

    
    <?php 
    require_once('includes/db.php');

    //runs if the UPDATE RATING button is pressed, updates the rating from the logged in user
    $username = $_SESSION['username'];
    if(isset($_POST['rating'])) {

        $idreview = $_POST['rating'];
        $ratingval = $_POST['ratingval'];
          
        //searching if ratings from that user exists
        //select avg from the raitngs column
        $query3 = "SELECT 'rating' FROM `reviews` WHERE authorid='$username' and productid='$idreview'";
        $result3=mysqli_query($connect, $query3);
        $row3 = mysqli_num_rows($result3);  

        //rating for that product from the user exists, update it
        if($row3>0){

             $query21 = "UPDATE `reviews` SET rating ='$ratingval' WHERE productid='$idreview' AND authorid='$username'";
             $result21=mysqli_query($connect, $query21);

        }
        else{

        //INSERT rating into the database
        $query4 = "REPLACE INTO `reviews` (`productid`, `rating`, `authorid`) VALUES ('$idreview', '$ratingval' , '$username')";
        $result4=mysqli_query($connect, $query4);
        }
                
        
        }
        
        //if the post comment is clicked
        if(isset($_POST['postcomment'])) {
            $idcomment = $_POST['postcomment'];
            $commentinput = $_POST['commentinput'];


            //INSERT the comment into the database
            $query6 = "REPLACE INTO `reviews` (`productid`, `comments`, `authorid`) VALUES ('$idcomment', '$commentinput' , '$username')";
            $result6=mysqli_query($connect, $query6);
            //refresh();
            
        }

        if(isset($_POST['username']) && isset($_POST['productid']) && isset($_POST['uniquecommentnum'])){

            $author = $_POST['username'];
            $productid = $_POST['productid'];
            $commentv = $_POST['uniquecommentnum'];

            //SQL command to delete a product on to the database

            $query2 = "UPDATE `reviews` SET comments = NULL WHERE productid='$productid' AND authorid='$author' AND num='$commentv'";
            //"DELETE FROM `reviews` WHERE productid='$productid' AND authorid='$author'";
            $result2 = mysqli_query($connect, $query2);
      }
         ?>

        <?php 
        require_once('includes/db.php');
        if(isset($_SESSION['username'])){
            $username2 = $_SESSION['username'];


            echo' 
            <div id = "container" class="container">
            <div id="refreshajax">';

            $query = "SELECT * FROM `products`";
            $result = mysqli_query($connect, $query) or die(mysqli_error());
            $rows = mysqli_num_rows($result);

            $items = 0;
            
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                echo'<br><br>';
                $items++;

                if ($items % 2 != 0) {
                    echo '<div class="row">';
                }

                $currentprodid= $row['productid'];

                //pulling the ratings from the reviews table
                //select avg from the raitngs column
                $query3 = "SELECT AVG(`rating`) as avg_rating FROM `reviews` WHERE productid='$currentprodid'";
                $result3=mysqli_query($connect, $query3);
                $row3 = mysqli_fetch_assoc($result3);

                
                if(isset($row['productid'])) {
                    echo '
                    <div class="card-container col-md-6 col-sm-12">' . "\r\n" .
                    '<div class="card">' . "\r\n" .
                    '<div class="card-header">' . "\r\n" .
                    '<h4 class="item-name card-title">' . $row['name'] . '</h4>' . "\r\n" .
                    '<a class="pull-right btn btn-info" href="messages.php?view='.$row['username'].'">Contact seller</a>' .
                    '</div>' . "\r\n" .
                    '<div class="card-body">';
                    
                    if(isset($row['img1'])) {
                        echo'<img src="'.$row['img1'].'" height="200" width="200">'. "\r\n";
                    }
                    if(isset($row['img2'])) {
                        echo'<img src="'.$row['img2'].'" height="200" width="200">'. "\r\n";
                    }
                    if(isset($row['img3'])) {
                        echo'<img src="'.$row['img3'].'" height="200" width="200">'. "\r\n";
                    }
                    ;
                    
                    echo
                    '<br><br><span >Posted: </span>' . $row['timesql'] . "\r\n" .
                    '<br><span >SellerId: </span>' . $row['username'] . "\r\n" . 
                    '<br><span >Description: </span>' . $row['description'] ."\r\n" .
                    '<br><span >Average Rating: </span>' . round($row3['avg_rating'], 2) . "\r\n" 
                    ;
                    
                    echo'
                    <br><br><span >Your Rating: </span>
                    <!-- UPDATE RATINGS - MOVE TO COMMON MARKET!--> 
                    <form method="POST" class="form_rating">
                    <select name = "ratingval">
                    <option type ="number" value = "1">1</option>
                    <option type ="number" value = "2">2</option>
                    <option type ="number" value = "3">3</option>
                    <option type ="number" value = "4">4</option>
                    <option type ="number" value = "5">5</option>
                    </select>
                    ' .
                    '
                    <button type="submit" name="rating" method ="POST" value="' . $row['productid'] .'" class="btn btn-default btn-link">Rate</button>
                    </form>
                    <!-- UPDATE RATINGS - MOVE TO COMMON MARKET END!-->
                    <br>                  

                    <span >Comments : </span>';
                    
                    //SQL commands for pulling comments from reviews table
                    $query5 = "SELECT * FROM `reviews` WHERE productid='$currentprodid'";
                    $result5=mysqli_query($connect, $query5);
                    $rows5 = mysqli_num_rows($result5);
                    
                    while($row5 = mysqli_fetch_array($result5, MYSQLI_ASSOC)) {
                        if(isset($row5["comments"]) && isset($row5["num"]) && $row5["authorid"] == $username2) {
                            echo '<br><span class="pt-1">•  ' . $row5["comments"] . '<span class="small text-muted"> by: ' . $row5["authorid"] . '</span> &nbsp;<button class="btn btn-sm btn-danger" name="delete_comment" username="' . $row5["authorid"] . '" productid="'.$row["productid"].'" uniquecommentnum="' . $row5["num"] . '"> Delete </button></span><br>';
                        }
                        
                        else{
                        if(isset($row5["comments"])) {
                            echo'
                            <br>•  ' . $row5["comments"] . '<span class="small text-muted"> by: ' . $row5["authorid"] . '</span>';
                        }}
                    }


                    echo '
                    <div >
                    <!-- form for adding a comment !-->
                    <br> <br>  Add Comment:
                    <form id = "' . $row['productid'].'" name="comment" method="POST" class="form_comment form-inline">
                    <input name="commentinput" type="text">
                    <button type="submit" name="postcomment" value = "' . $row['productid'].'" class="btn ml-3">Post Comment</button>
                    </form>
                    <br>
                    </div>
                    '; 
                }
                echo '
                </div>
                </div>
                </div>
                <br>
                <br>';
                if ($items % 2 == 0) {
                    echo '</div>';
                }
            }
        }
        ?>
    </div>
    
    </div>




<script type="text/Javascript">
//ajax for adding comments items
    $("body").on("submit", ".form_comment", function (e) {
        e.preventDefault();
        var commentinputv = $(this).find('input').val();
        var postcommentv = e.target.id;
        //alert(postcommentv);


    $.ajax({
         url: "marketplace.php",
         method: "POST",
         dataType: "html",
         data: {postcomment: postcommentv, commentinput:commentinputv},
        
         success: function (response) {
           //alert(response);
           $result = $(response).find("#refreshajax");
           $(document).find('#refreshajax').html($result);
           //$('#refreshajax').html($result);

        }
    })

    });



    //ajax for adding rating items
    $("body").on("submit", ".form_rating", function (e) {
        e.preventDefault();
        var ratingvalv = $(this).find('select').val();
        var ratingv = $(this).find('button').attr("value");
       // alert(ratingv);


    $.ajax({
         url: "marketplace.php",
         method: "POST",
         dataType: "html",
         data: {rating: ratingv, ratingval: ratingvalv},
        
         success: function (response) {
           //alert(response);
           $result = $(response).find("#refreshajax");
           $(document).find('#refreshajax').html($result);
        }
        })

    });
    

    //ajax for deleting comment
    $("body").on("click", ".btn.btn-sm", function (e) {
       
       var usernamev = $(this).attr("username");
       var prodid = $(this).attr("productid");
       var commentv= $(this).attr("uniquecommentnum");


    $.ajax({
         url: "marketplace.php",
         method: "POST",
         dataType: "html",
         data: {username: usernamev, productid: prodid, uniquecommentnum: commentv},
        
         success: function (response) {
           //alert(response);
           $result = $(response).find("#refreshajax");
           $(document).find('#refreshajax').html($result);
        }
        })

    });




</script>

    <?php
    require_once('includes/footer.php');
    ?>
</body>
</html>