<?php
include(__DIR__ . '/config.php');

// Today’s date
$today = date('Y-m-d');

// Update past appointments that were not attended
$sql = "UPDATE appointments 
        SET status = 'Missed' 
        WHERE appointment_date < ? 
        AND status = 'Pending'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);

$stmt->execute();

$stmt->close();
$conn->close();
?>
