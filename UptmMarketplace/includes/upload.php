<?php

   require_once('db.php');

   if(isset($_FILES['image']) && isset($_POST['idproductimg'])){

   		

      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size = $_FILES['image']['size'];
      $file_tmp = $_FILES['image']['tmp_name'];
      $file_type = $_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));



        //insert imageurl to the database
   		$username = $_SESSION['username'];
		$prodid = stripslashes($_POST['idproductimg']);
		$produrl = "img/prodimg/".$file_name;

         
		//checking to see if images exist
		 $query1 = "SELECT * FROM `products` WHERE productid='$prodid'";
         $result1 = mysqli_query($connect, $query1) or die(mysqli_error());

         while($row = mysqli_fetch_array($result1, MYSQLI_ASSOC)){
          	if(!isset($row['img1'])){
          		 //SQL command to add a product on to the database
				$query2 = "UPDATE products SET img1='$produrl' WHERE productid='$prodid'";
				$result2 = mysqli_query($connect, $query2);
          	}
          	else
          		if(!isset($row['img2'])){
          		 //SQL command to add a product on to the database
				$query3 = "UPDATE products SET img2='$produrl' WHERE productid='$prodid'";
				$result3 = mysqli_query($connect, $query3);
          	}
          	else
          		if(!isset($row['img3'])){
          		 //SQL command to add a product on to the database
				$query4 = "UPDATE products SET img3='$produrl' WHERE productid='$prodid'";
				$result4 = mysqli_query($connect, $query4);
          	}
          }

      $expensions= array("jpeg","jpg","png");
      
     /* if(in_array($file_ext, $expensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }*/
      
      if($file_size > 2097152*10) {
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true) {
         move_uploaded_file($file_tmp,"../img/prodimg/".$file_name);
         header( 'Location: ../myproducts.php' ) ;
      }else{
         print_r($errors);
      }
   }
?>