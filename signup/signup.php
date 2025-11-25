<?php
include('config.php'); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['signup'])) {
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $username = $_POST['username']; // <-- added
    $email = $_POST['email']; 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $month = $_POST['month'];
    $day = $_POST['day'];
    $year = $_POST['year'];
    $gender = $_POST['gender'];          
    $terms_accepted = isset($_POST['terms']) ? 1 : 0; // checkbox

    // Insert data into database
    $sql = "INSERT INTO users (first_name, last_name, username, month, day, year, gender, email, password, terms_accepted)
            VALUES ('$first_name', '$last_name', '$username', '$month', '$day', '$year', '$gender', '$email', '$password', $terms_accepted)";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Signup successful!'); window.location.href='login.html';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>
