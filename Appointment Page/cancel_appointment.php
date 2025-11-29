<?php
// cancel_appointment.php
include('config.php');
session_start();

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Project in IS104/Login/login.html");
    exit();
}

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Update appointment status
    $sql = "UPDATE appointments 
            SET status = 'Cancelled' 
            WHERE id = '$appointment_id' 
            AND user_id = '$user_id'"; // security: cannot cancel others' appts";

    mysqli_query($conn, $sql);

    // Redirect back with message
    header("Location: MyAppointments.php?cancel=success");
    exit();
} else {
    header("Location: MyAppointments.php?cancel=invalid");
    exit();
}
?>
