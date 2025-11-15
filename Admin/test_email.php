<?php
require __DIR__ . '/Project in IS104/Admin/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/Project in IS104/Admin/PHPMailer/src/SMTP.php';
require __DIR__ . '/Project in IS104/Admin/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'Healthcare.plus12300@gmail.com';
    $mail->Password = 'sivu zuwe cmbm nmew'; // App password, NOT Gmail password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Send to yourself
    $mail->setFrom('cartercarig@gmail.com', 'Test');
    $mail->addAddress('cartercarig@gmail.com', 'You');

    $mail->isHTML(true);
    $mail->Subject = 'PHPMailer Test';
    $mail->Body = 'PHPMailer is working!';

    $mail->send();
    echo 'Message sent successfully!';
} catch (Exception $e) {
    echo 'Error: ' . $mail->ErrorInfo;
}
