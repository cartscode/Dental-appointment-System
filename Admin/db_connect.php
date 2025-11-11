<?php
// FILE: db_connect.php - Database Connection Configuration

// Hostname is usually localhost for XAMPP
$servername = "localhost";

// Default username is 'root' for XAMPP MySQL
$username = "root";

// Default password is an empty string for XAMPP MySQL (no password)
// IMPORTANT: Change this if you have set a password for the root user!
$password = "";

// The name of the database you created earlier
$dbname = "dental_db";

// Create connection using MySQLi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection failed
if (!$conn) {
    // If connection fails, stop the script and display the error
    die("Connection failed: " . mysqli_connect_error());
}
// If you see this line after fixing the path, you are connected! (Remove later)
// echo "Database Connected Successfully!"; 
?>