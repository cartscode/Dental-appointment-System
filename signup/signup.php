<?php
include('config.php');

if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $emergency = $_POST['emergency'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $month = $_POST['month'];
    $day = $_POST['day'];
    $year = $_POST['year'];
    $gender = $_POST['gender'];

    $sql = "INSERT INTO users (name, number, email, emergency_contact, username, password, birth_month, birth_day, birth_year, gender)
            VALUES ('$name', '$number', '$email', '$emergency', '$username', '$password', '$month', '$day', '$year', '$gender')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Signup successful!'); window.location.href='\Project in IS104\Login\login.html';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
