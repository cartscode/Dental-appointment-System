<?php
// submit_appointment.php — with session support
include('config.php'); 
session_start(); // ✅ Start the session to access logged-in data

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Make sure user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please log in first!'); window.location.href='/Login/login.html';</script>";
        exit();
    }

    // Retrieve user info from session
    $user_id = $_SESSION['user_id'];
    $name    = $_SESSION['name'];
    $email   = $_SESSION['email'];

    // Get form data
    $service = $_POST['service'] ?? ''; 
    $date    = $_POST['date'] ?? '';    
    $time    = $_POST['time'] ?? '';    

    // ✅ Step 1: Check if the user already has an appointment
    $check_sql = "SELECT * FROM appointments WHERE user_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $user_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>
                alert('You already have an appointment booked!');
                window.location.href='/Project in IS104/Appointment Page/MyAppointments.php';
              </script>";
        exit();
    }

    mysqli_stmt_close($check_stmt);

    // ✅ Step 2: Insert new appointment
    $sql = "INSERT INTO appointments (user_id, name, email, service_name, appointment_date, appointment_time)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind parameters — all are strings except user_id (integer)
        mysqli_stmt_bind_param($stmt, "isssss", $user_id, $name, $email, $service, $date, $time);

        // Execute and check for success
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Appointment booked successfully!');
                    window.location.href='/Project in IS104/Appointment Page/MyAppointments.php';
                  </script>";
        } else {
            echo "<script>alert('Database Error: " . mysqli_error($conn) . "'); window.history.back();</script>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error preparing statement: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }

    mysqli_close($conn);
}
?>
