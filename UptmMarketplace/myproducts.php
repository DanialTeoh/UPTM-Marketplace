<?php
if (!isset($_SESSION)) {
    session_start();
}
$pagename = "MY MARKETPLACE";
require_once('includes/functions.php');
check_cookie();
authenticate();

require_once('includes/header.php');

?>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

<div class="jumbotron rounded-0">
    <h2>
        <?php echo '<span>' . $_SESSION['firstname'] ."'s Marketplace" . '</span>' ?>
    </h2>
    
    <p class="lead">Add, Update or Remove Products!</p>
</div>

<br>

<div class="container">

    <?php
	require_once('includes/db.php');

//INSERTION--------------------------------------------------------
if (isset($_POST['productname']) && isset($_POST['description'])) {

	$username = $_SESSION['username'];
	$prodname = sanitizeString($_POST['productname']);
	$description = sanitizeString($_POST['description']);
        

        //SQL command to add a product on to the database
	$query = "INSERT INTO products (username, name, description, timesql) VALUES ('$username', '$prodname', '$description', CURRENT_TIMESTAMP)";

	$result = mysqli_query($connect, $query);

} 

//DELETION-------------------------------------------------------
if(isset($_POST['delete']) and is_numeric($_POST['delete'])){

//gets the value of the productid to be deleted from the form (check the <button></button> tag attributes)
$iddelete = sanitizeString($_POST['delete']);

//SQL command to delete a product on to the database
$query2 = "DELETE FROM products WHERE productid='$iddelete'";
$result2 = mysqli_query($connect, $query2);

}

//DELETE PICTURES-----------------------------------------------
if(isset($_POST['deleteimgid']) && isset($_POST['imgnum'])){
  $iddelete = sanitizeString($_POST['deleteimgid']);
  $imgnum = sanitizeString($_POST['imgnum']);

  if($imgnum=="img1"){

    $query2 = "UPDATE products SET img1 = NULL WHERE productid='$iddelete'";
    $result2 = mysqli_query($connect, $query2);
  }
  elseif($imgnum=="img2"){

    $query2 = "UPDATE products SET img2 = NULL WHERE productid='$iddelete'";
    $result2 = mysqli_query($connect, $query2);
  }
  elseif($imgnum=="img3"){

    $query2 = "UPDATE products SET img3 = NULL WHERE productid='$iddelete'";
    $result2 = mysqli_query($connect, $query2);
  }

}

?>
<h3> Your Current Products: </h3>
<br>

<div id="refreshajax">

<?php 
  if(isset($_SESSION['username'])){
  	$username = $_SESSION['username'];

      $query = "SELECT * FROM products WHERE username='$username'";
      $result = mysqli_query($connect, $query) or die(mysqli_error());
 	    $rows = mysqli_num_rows($result);

 	    echo'<ul>';
 	    
 	    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

          $currentprodid= $row['productid'];
          
          //SQL commands for pulling ratings from reviews table
          //select avg from the raitngs column
          $query3 = "SELECT AVG(`rating`) as avg_rating FROM reviews WHERE productid='$currentprodid'";
          $result3=mysqli_query($connect, $query3);
          $row3 = mysqli_fetch_assoc($result3);

             echo '
             <li> 
             <div class="card">' .
             '<div class="card-header">' .
             '<h4 class="card-title">' . $row['name'] . '</h4>' .
             '</div>' .
             '<div class="card-body">'
             ;

             echo'
             <br>';
             
             if(isset($row['img1'])){
                 echo'<div class="img-container"><img src="'.$row['img1'].'" height="200" width="200"><a deleteimgid="'.$row['productid'].'" imgnum="img1" class="deleteicon text-danger"><i class="fa fa-times-circle" style="position:absolute; top:0; right:0;" aria-hidden="true"></i></a></div>';
                }
  
            if(isset($row['img2'])){
                echo'<div class="img-container"><img src="'.$row['img2'].'" height="200" width="200"><a deleteimgid="'.$row['productid'].'" imgnum="img2" class="deleteicon text-danger"><i class="fa fa-times-circle" style="position:absolute; top:0; right:0;" aria-hidden="true"></i></a></div>';
            }
            if(isset($row['img3'])){
                echo'<div class="img-container"><img src="'.$row['img3'].'" height="200" width="200"><a deleteimgid="'.$row['productid'].'" imgnum="img3" class="deleteicon text-danger"><i class="fa fa-times-circle" style="position:absolute; top:0; right:0;" aria-hidden="true"></i></a></div>';
            }
            echo'
            <br>' .

             '<b>Product ID : </b> ' . $row['productid'] . 
             '<br><b>Description: </b>' . $row['description']  . 
             '<br><b>Date Posted: </b>' . $row['timesql'] . 
             '<br><b>Average Rating: ' . round($row3['avg_rating'],2) . '</b>
             <br>
             <b> Comments :</b>
             ';

          //SQL commands for pulling commentsfrom reviews table
          $query5 = "SELECT * FROM reviews WHERE productid='$currentprodid'";
          $result5=mysqli_query($connect, $query5);
          

           while($row5 = mysqli_fetch_array($result5, MYSQLI_ASSOC)){

              if(isset($row5["comments"])){
              echo'
              <br>•  ' . $row5["comments"] . '<span class="small text-muted"> by: ' . $row5["authorid"] . '</span>';
          }
      }

   echo'
   <br>

   <div>
   <!----UPLOAD IMAGE HTML FORM !-->
   <br>
   <h4 class="lead">Upload image for this item</h4>
   <form action="includes/upload.php" method="POST" enctype="multipart/form-data" class="upload form-inline">
   <input type = "file" name ="image" class = "image form-control-file" attribute ="'.$currentprodid.'"/>
   <button type="submit" class="uploadsubmit btn btn-info">Upload</button>
   <input type="hidden" name="idproductimg" value="'.$currentprodid.'">
   </form>
   </div>
   <br>
   <!--Delete Button !-->
   <button name="delete" id="'.$currentprodid.'" class="btn btn-danger btn-sm float-left">Delete Item</button>
   </div>
   </div>
   <br>
   <br>
   ';
}

   echo '
   </li>
   </ul>
   ';
  }
?>
</div>

  <form class="form bg-light" id="add-item-form">
      <h3> Add new product: </h3>
      <div class="row">
          <div class="col">
              <div class="form-group">
                  <label for="productname">Product Name</label>
                  <input type="text" class="form-control" name="productname" id="productname" placeholder="Enter Product Name">
              </div>
          </div>
          <div class="col">
              <div class="form-group">
                  <label for="description">Product Description</label>
                  <input type="text" class="form-control" name="description" id="description" placeholder="Enter product description">
              </div>
          </div>
      </div>
      <button type="submit" id="insert" name="submit" class="btn btn-info">Add Product</button>
  </form>

<script>

//------------------------ajax for deleting items
$("body").on('click', '.btn.btn-danger', function (e) {



    $.ajax({
        url: "myproducts.php",
        method: "POST",
        dataType: "html",
        data: {
            delete: e.target.id
        },

        success: function (response, textStauts, jqXHR) {

            $result = $(response).find("#refreshajax");
            $("#refreshajax").html($result);

        }
    })

});


//-----------------------ajax for uploading pictures
$("body").on("submit", ".upload", function (e) {

    e.preventDefault();

    //if an image is selected, run ajax
    if ($(this).children('input')[0].files.length > 0) {

        $.ajax({
            type: "POST",
            url: "includes/upload.php",
            data: new FormData(this),
            cache: false,
            contentType: false,
            processData: false,
            success: function (response, textStatus, jqXHR) {
                $result = $(response).find("#refreshajax");
                $("#refreshajax").html($result);
            }
        })
    }
});




//---------------------------ajax for adding items
$('#add-item-form').on("submit", function (event) {

    event.preventDefault(); //prevents refresh

    $.ajax({
        url: "myproducts.php",
        method: "POST",
        dataType: 'html',
        data: $('#add-item-form').serialize(),

        success: function (response, textStauts, jqXHR) {
            $result = $(response).find('#refreshajax');
            $('#refreshajax').html($result);

        }
    })
});


//ajax for deleting images
$("body").on("click", ".deleteicon", function (e) {
   
   var prodid = $(this).attr("deleteimgid");
   var imgnumv= $(this).attr("imgnum");
   //alert(prodid);


$.ajax({
     url: "myproducts.php",
     method: "POST",
     dataType: "html",
     data: {deleteimgid: prodid, imgnum: imgnumv},
    
     success: function (response) {
       //alert(response);
       $result = $(response).find("#refreshajax");
       $(document).find('#refreshajax').html($result);
    }
    })

});
</script>

<?php
require_once('includes/footer.php'); ?>
</body>
</html>
