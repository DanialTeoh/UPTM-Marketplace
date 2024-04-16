<?php
// Include the database connection file
require_once('db.php');

// Check if the image file and user ID are set
if(isset($_FILES['image']) && isset($_POST['idprofimg'])) {

    // Initialize errors array
    $errors = array();

    // Get file details
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Define allowed file extensions
    $allowed_extensions = array("jpeg", "jpg", "png");

    // Check if the file extension is allowed
    if(!in_array($file_ext, $allowed_extensions)) {
        $errors[] = "Only JPEG, JPG, and PNG files are allowed.";
    }

    // Check if file size exceeds 2MB
    if($file_size > 2097152) {
        $errors[] = "File size must be less than 2 MB.";
    }

    // If no errors, proceed with uploading and updating database
    if(empty($errors)) {
        // Sanitize user input
        $username = mysqli_real_escape_string($connect, $_POST['idprofimg']);
        $profurl = "img/profimg/" . $file_name;

        // Update database with profile image URL
        $query = "UPDATE users SET profimg='$profurl' WHERE username='$username'";
        $result = mysqli_query($connect, $query);

        // Check if database update was successful
        if($result) {
            // Move uploaded file to destination folder
            move_uploaded_file($file_tmp, "../img/profimg/" . $file_name);
            // Redirect back to profile page
            header('Location: ../profile.php');
            exit();
        } else {
            $errors[] = "Error updating database.";
        }
    }
}

// If there are any errors, print them
if(!empty($errors)) {
    foreach($errors as $error) {
        echo $error . "<br>";
    }
}
?>
