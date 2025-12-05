<?php
include('config.php');
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../Admin/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../Admin/PHPMailer/src/SMTP.php';
require __DIR__ . '/../Admin/PHPMailer/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please log in first!'); window.location.href='/Login/login.html';</script>";
        exit();
    }

    $user_id    = $_SESSION['user_id'];
    $first_name = $_SESSION['first_name'] ?? '';
    $last_name  = $_SESSION['last_name'] ?? '';
    $email      = $_SESSION['email'] ?? '';

    $full_name = trim($first_name . ' ' . $last_name);
    if(empty($full_name)) $full_name = "User";

    // Get POST data
    $service_name = trim($_POST['service'] ?? '');
    $date         = trim($_POST['date'] ?? '');
    $time         = trim($_POST['time'] ?? '');

    if(empty($service_name) || empty($date) || empty($time)){
        echo "<script>alert('Please select a service, date, and time.'); window.history.back();</script>";
        exit();
    }

    // Check pending appointment
    $check_sql = "SELECT id FROM appointments WHERE user_id = ? AND status = 'Pending'";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $user_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    if(mysqli_num_rows($result) > 0){
        echo "<script>alert('You already have a pending appointment!'); window.location.href='/Project in IS104/Appointment Page/MyAppointments.php';</script>";
        exit();
    }
    mysqli_stmt_close($check_stmt);

    // Insert appointment
    $sql = "INSERT INTO appointments (user_id, first_name, last_name, email, service_name, appointment_date, appointment_time) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issssss", $user_id, $first_name, $last_name, $email, $service_name, $date, $time);
    if(!mysqli_stmt_execute($stmt)){
        die("Insert Error: " . mysqli_stmt_error($stmt));
    }
    mysqli_stmt_close($stmt);

    // Send confirmation email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'Healthcare.plus12300@gmail.com';
        $mail->Password = 'sivu zuwe cmbm nmew';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('Healthcare.plus12300@gmail.com', 'Dental Clinic');
        $mail->addAddress($email, $full_name);
        $mail->isHTML(true);
        $mail->Subject = "Appointment Confirmation — Dental+";
        $mail->Body = "
            <h2>Appointment Confirmed</h2>
            <p>Hi <b>$full_name</b>,</p>
            <p>Your appointment has been booked successfully.</p>
            <p><b>Service:</b> $service_name<br>
            <b>Date:</b> $date<br>
            <b>Time:</b> $time</p>
            <p>Thank you for choosing <b>Dental+</b>.</p>
        ";
        $mail->send();
    } catch (Exception $e) {
        error_log("Email Error: " . $mail->ErrorInfo);
    }

    echo "<script>alert('Appointment booked successfully!'); window.location.href='/Project in IS104/Appointment Page/MyAppointments.php';</script>";

    mysqli_close($conn);
}
?>
