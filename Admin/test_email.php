<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'Healthcare.plus12300@gmail.com';   // your gmail
    $mail->Password = 'sivu zuwe cmbm nmew';     // your app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('Healthcare.plus12300@gmail.com', 'Dental Clinic');
    $mail->addAddress('cartercarig@gmail.com');   // send to YOURSELF for test

    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body = 'This is a test email from your XAMPP system.';

    $mail->send();
    echo "Email sent!";
} 
catch (Exception $e) {
    echo "Error: " . $mail->ErrorInfo;
}
