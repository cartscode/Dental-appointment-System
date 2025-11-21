<?php
// submit_appointment.php — with session support
include('config.php'); 
session_start(); 

// PHPMailer requirements
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../Admin/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../Admin/PHPMailer/src/SMTP.php';
require __DIR__ . '/../Admin/PHPMailer/src/Exception.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Must be logged in
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Please log in first!'); window.location.href='/Login/login.html';</script>";
        exit();
    }

    // Retrieve logged-in user data
    $user_id = $_SESSION['user_id'];
    $name    = $_SESSION['name'];
    $email   = $_SESSION['email'];

    // Form data
    $service = $_POST['service'] ?? ''; 
    $date    = $_POST['date'] ?? '';    
    $time    = $_POST['time'] ?? '';    

    // 1️⃣ Check if user already has pending appointment
    $check_sql = "SELECT id FROM appointments WHERE user_id = ? AND status = 'Pending'";
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

    // 2️⃣ Insert appointment
    $sql = "INSERT INTO appointments (user_id, name, email, service_name, appointment_date, appointment_time)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "isssss", $user_id, $name, $email, $service, $date, $time);

        if (mysqli_stmt_execute($stmt)) {

            // 3️⃣ Send confirmation email NOW!
            $mail = new PHPMailer(true);

            try {
                // SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'Healthcare.plus12300@gmail.com';
                $mail->Password = 'sivu zuwe cmbm nmew';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Email details
                $mail->setFrom('Healthcare.plus12300@gmail.com', 'Dental Clinic');
                $mail->addAddress($email, $name);

                $mail->isHTML(true);
                $mail->Subject = "Appointment Confirmation — Dental+";
                $mail->Body = "
                    <h2>Appointment Confirmed</h2>
                    <p>Hi <b>$name</b>,</p>
                    <p>Your appointment has been booked successfully.</p>
                    <p><b>Service:</b> $service<br>
                    <b>Date:</b> $date<br>
                    <b>Time:</b> $time</p>
                    <p>Thank you for choosing <b>Dental+</b>.</p>
                ";

                $mail->send();

            } catch (Exception $e) {
                // Email failed, but appointment still saved
                error_log("Email Error: " . $mail->ErrorInfo);
            }

            // 4️⃣ Redirect after successful booking + email
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
