<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require 'db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get appointments exactly 2 days from today
$targetDate = date('Y-m-d', strtotime('+2 days'));

$sql = "SELECT * FROM appointments WHERE appointment_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $targetDate);
$stmt->execute();
$result = $stmt->get_result();
echo "Target Date: $targetDate<br>";
echo "Rows found: " . $result->num_rows . "<br>";

while ($row = $result->fetch_assoc()) {

    $email = $row['email'];
    $name = $row['name'];
    $date = $row['appointment_date'];
    $time = $row['appointment_time'];

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
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
        $mail->Subject = "Appointment Reminder";
        $mail->Body = "
            <h3>Hi $name,</h3>
            <p>This is a reminder that you have an appointment scheduled on:</p>
            <p><b>$date at $time</b></p>
            <p>Thank you and see you soon!</p>
        ";

        $mail->send();
        echo "Sent to: $email<br>";

    } catch (Exception $e) {
        echo "Failed to send to $email: " . $mail->ErrorInfo . "<br>";
    }
}
?>
