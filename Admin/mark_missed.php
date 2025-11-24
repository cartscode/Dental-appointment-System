<?php
include(__DIR__ . '/config.php');

// Today’s date
$today = date('Y-m-d');

// Update all yesterday and past appointments that were NOT attended
$sql = "UPDATE appointments 
        SET status = 'Missed' 
        WHERE appointment_date < ? 
        AND status = 'Scheduled'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);

if ($stmt->execute()) {
    echo "Missed appointments updated successfully.";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
