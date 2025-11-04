<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /Project in IS104/Login/login.php");
    exit();
}

// Get the appointment ID from the URL
if (isset($_GET['id'])) {
    $appt_id = intval($_GET['id']); // secure way to get numeric ID
    $user_id = $_SESSION['user_id'];

    // Delete only the user's own appointment
    $query = "DELETE FROM appointments WHERE id = '$appt_id' AND user_id = '$user_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Appointment canceled successfully!'); window.location.href='/Project in IS104/Appointment Page/MyAppointments.php';</script>";
    } else {
        echo "<script>alert('Failed to cancel the appointment. Please try again.'); window.location.href='/Project in IS104/Appointment Page/MyAppointments.php';</script>";
    }
} else {
    header("Location: /Project in IS104/Appointment Page/MyAppointments.php");
    exit();
}
?>
