<?php

$db_host = "localhost"; // Host name
$db_username = "team24"; // MySQL username
$db_password = "marketplace"; // MySQL password
$db_name = "registration"; // Database name

// Create connection
$connect = mysqli_connect($db_host, $db_username, $db_password, $db_name);

// Check connection
if (!$connect) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Function to create a table in the database
function createTable($name, $query) {
    global $connect;
    $result = queryMysql("CREATE TABLE IF NOT EXISTS $name ($query)");
    if ($result) {
        echo "Table '$name' created or already exists.";
    } else {
        echo "Error creating table: " . $connect->error;
    }
}

// Function to execute SQL queries
function queryMysql($query) {
    global $connect;
    $result = $connect->query($query);
    if (!$result) {
        // Get the full error message
        $error_message = "Error executing query: " . $connect->error;
        // Output the full error message
        echo "Fatal error: " . $error_message;
        // Optionally, you can also log the error message to a file or database for further analysis
        // For example: error_log($error_message, 3, "/path/to/error.log");
        // Terminate the script execution
        die();
    }
    return $result;
}


// Function to destroy the session
function destroySession() {
    session_start();
    $_SESSION = array();
    session_unset();
    session_destroy();
    // Deleting cookies
    setcookie("username", "", time()-1, "/");
    setcookie("password", "", time()-1, "/");
    header("Location: ../login.php");
}

// Function to sanitize input string
function sanitizeString($var) {
    global $connect;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connect->real_escape_string($var);
}

// Function to display user profile based on username
function showProfile($username) {
    global $connect;
    
    // Prepare the SQL query with a placeholder for the username
    $query = "SELECT * FROM users WHERE username=?";
    
    // Prepare the statement
    $statement = $connect->prepare($query);
    if (!$statement) {
        die("Error preparing statement: " . $connect->error);
    }
    
    // Bind the parameter (username)
    $statement->bind_param("s", $username);
    
    // Execute the statement
    if (!$statement->execute()) {
        die("Error executing statement: " . $statement->error);
    }
    
    // Get the result
    $result = $statement->get_result();
    if (!$result) {
        die("Error getting result: " . $statement->error);
    }
    
    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch the row as an associative array
        $row = $result->fetch_assoc();
        
        // Output profile information
        echo "Name: " . stripslashes($row['firstname']) . " " . stripslashes($row['lastname']) . "<br>";
        echo "Username: " . stripslashes($row['username']) . "<br>";
        echo "Email: " . stripslashes($row['email']) . "<br><br>";
    } else {
        // No matching user found
        echo "User not found.";
    }
    
    // Close the statement
    $statement->close();
}

// Function to authenticate user session
function authenticate() {
    if (!isset($_SESSION['username'])) {
        session_start();
    }
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
    }
}

// Function to check cookie and set session variables
function check_cookie() {
    global $connect;

    // If the cookie is set, use those values to login
    if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
        if (!isset($_SESSION)) {
            session_start();
        }

        // Using cookies to set the session variables
        $username = $_SESSION['username'] = $_COOKIE['username'];
        $password = $_COOKIE['password'];
        $_SESSION['loggedin'] = true;

        // Construct and execute the SQL query
        $query = "SELECT * FROM `users` WHERE username='$username' and password='$password'";
        $result = queryMysql($query);
        $rows = $result->num_rows;

        if ($rows == 1) {
            $profile = $result->fetch_assoc();
            $_SESSION['firstname'] = $profile['firstname'];
            $_SESSION['lastname'] = $profile['lastname'];
            $_SESSION['email'] = $profile['email'];
        }
    }
}

// Function to redirect user if already logged in
function redirect() {
    if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
        header('Location: index.php');
    }
    session_start();
    if (isset($_SESSION['username'])) {
        header('Location: index.php');
    }
}

// Function to refresh the page
function refresh() {
    header("Refresh:0");
}

?>
