<?php
// ====================================================================
// MARK MISSED APPOINTMENTS AND SEND PENALTY EMAILS
// ====================================================================

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require 'db_connect.php'; // Your DB connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Today's date
$today = date('Y-m-d');

// 1️⃣ Mark past pending appointments as Missed
$sql = "UPDATE appointments 
        SET status = 'Missed' 
        WHERE appointment_date < ? 
        AND status = 'Pending'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$stmt->close();

// 2️⃣ Fetch Missed or Cancelled appointments that haven't been notified
$sql2 = "SELECT * FROM appointments WHERE status IN ('Missed', 'Cancelled') AND (notified IS NULL OR notified = 0)";
$result = $conn->query($sql2);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $email = $row['email']; // Change if your column name is different

        // Build full name
        $first = $row['first_name'] ?? '';
        $last = $row['last_name'] ?? '';
        $name = trim($first . ' ' . $last);
        if ($name === '') $name = "Patient"; // fallback

        $date = date('m/d/Y', strtotime($row['appointment_date']));
        $time = date('h:i A', strtotime($row['appointment_time']));
        $subject = "Appointment {$row['status']} Notification";

        $body = "
            <h3>Hi $name,</h3>
            <p>Your appointment on <b>$date at $time</b> has been marked as <b>{$row['status']}</b>.</p>
            <p>Please note that a penalty may apply. Contact us if you have any questions.</p>
        ";

        $mail = new PHPMailer(true);
        $status = 'Sent';

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'Healthcare.plus12300@gmail.com'; // Your email
            $mail->Password = 'sivu zuwe cmbm nmew'; // Gmail App Password
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

        // 3️⃣ Log email to database
        $insert_sql = "INSERT INTO email_sent_records 
            (recipient_email, recipient_name, subject, message, sent_at, status)
            VALUES (?, ?, ?, ?, NOW(), ?)";
        
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("sssss", $email, $name, $subject, $body, $status);
        $stmt_insert->execute();
        $stmt_insert->close();

        // 4️⃣ Update notified column
        $update = $conn->prepare("UPDATE appointments SET notified = 1 WHERE id = ?");
        $update->bind_param("i", $row['id']);
        $update->execute();
        $update->close();
    }
} else {
    echo "No missed or cancelled appointments to notify today.";
}

$conn->close();
?>
