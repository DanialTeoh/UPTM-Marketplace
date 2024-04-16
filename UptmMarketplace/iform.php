<?php
if(!isset($_SESSION['username'])) {
    session_start();
}

require_once('includes/functions.php');
check_cookie();
authenticate(); ?>


<div class="container">


    	<?php
        require('includes/db.php');
        
        if(!empty($_POST))
        {
            

		//add products, if the add product form is submitted
		if (isset($_POST['productname']) && isset($_POST['description'])) {

			$username = $_SESSION['username'];
			$prodname = stripslashes($_POST['productname']);
			$description = stripslashes($_POST['description']);
            $date = getdate();

            //SQL command to add a product on to the database
			$query = "INSERT INTO products VALUES (productid, '$username', '$prodname', '$description', CURRENT_TIMESTAMP)";

            $result = mysqli_query($connect, $query);
           
		} ?>


        <h1> Your Products: </h1>

        <?php 
        if(isset($_SESSION['username'])){
        	$username = $_SESSION['username'];

            $query = "SELECT * FROM `products` WHERE username='$username'";
            $result = mysqli_query($connect, $query) or die(mysqli_error());
       	    $rows = mysqli_num_rows($result);

       	    echo'<div id="product-list"> <ul id="item-list">';
       	    
       	    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

                if(isset($_POST['delete']) and is_numeric($_POST['delete'])){

                //gets the value of the productid to be deleted from the form (check the <button></button> tag attributes)
                $iddelete = $_POST['delete'];

               //SQL command to delete a product on to the database
                $query2 = "DELETE FROM `products` WHERE productid='$iddelete'";
                $result2 = mysqli_query($connect, $query2);
                refresh();
        

                } ?>

       	    	<li> <b>Productid : </b><?php echo $row['productid'] ?><br>
                     <b>Product name: </b><?php echo $row['name']  ?><br>
                     <b>Product Description:</b><?php echo $row['description'] ?><br>
                     <b>Date Posted: </b><?php echo $row['timesql'] ?>
                </li>
                <form name = "delete" method = "POST">
                <button type="submit" name="delete" value = "' . $row['productid'].'" class="btn btn-success">Delete</button>
                </form>
                <br>
       	   <?php }

           echo '</ul></div>';
        }
    }
        
    ?>

    <h1> Add Products: </h1>

            <form  id="add-item-form">
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
                
                <button type="submit" id="insert" name="submit" class="btn btn-success">Add Product</button>
            </form>
        </div>

        <script>
            $(document).ready(function () {
                $('#add-item-form').on("submit", function(event) {
                    event.preventDefault();
                    
                    
                   $.ajax({
                       url: "iform.php",
                       method: "POST",
                       data:$('#add-item-form').serialize(),
                       beforeSend:function() { $('#insert').val("Adding...");},
                       success:function(data)
                       {
                            $('#insert').val("Insert");
                            $('#add-item-form')[0].reset(); 
                            $('.container').html(data); 
                            
                           
                       }
                   })
                });
            });

            
        </script>
    
</div>