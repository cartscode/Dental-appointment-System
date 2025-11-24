<?php
// ====================================================================
// SEND EMAIL REMINDERS AND LOG TO DATABASE
// ====================================================================

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require 'db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Target date: 2 days from today
$targetDate = date('Y-m-d', strtotime('+2 days'));

// Fetch pending appointments for that date
$sql = "SELECT * FROM appointments WHERE appointment_date = ? AND status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $targetDate);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {

    $email = $row['email'];
    $name = $row['name'];
    $date = date('m/d/Y', strtotime($row['appointment_date']));
    $time = date('h:i A', strtotime($row['appointment_time']));
    $subject = "Appointment Reminder";

    $body = "
        <h3>Hi $name,</h3>
        <p>This is a reminder that you have an appointment scheduled on:</p>
        <p><b>$date at $time</b></p>
        <p>Thank you and see you soon!</p>
    ";

    $mail = new PHPMailer(true);
    $status = 'Sent';

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'Healthcare.plus12300@gmail.com';
        $mail->Password = 'sivu zuwe cmbm nmew'; // Use Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email details
        $mail->setFrom('Healthcare.plus12300@gmail.com', 'Dental Clinic');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        echo "Email sent to: $email<br>";

    } catch (Exception $e) {
        echo "Failed to send to $email: " . $mail->ErrorInfo . "<br>";
        $status = 'Failed';
    }

    // Log email into database
    $insert_sql = "INSERT INTO email_sent_records (recipient_email, recipient_name, subject, message, sent_at, status)
                   VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmt_insert = $conn->prepare($insert_sql);
    $stmt_insert->bind_param("sssss", $email, $name, $subject, $body, $status);
    $stmt_insert->execute();
}

echo "All emails processed.";
?>
