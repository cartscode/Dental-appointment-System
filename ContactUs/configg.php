<?php
// configg.php - Database connection file

$servername = "localhost";
$username = "root";       // <-- CHECK: If your password is NOT blank, change this.
$password = "";           // <-- CHECK: If your password is NOT blank, enter it here.
$dbname = "dental_db";  // The database name

// Establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // If connection fails, stop the script and output the error
    die("Connection failed: " . $conn->connect_error);
}

// Connection successful! The $conn variable is now ready to be used.
?>