<?php
include('config.php');

$date = $_GET['date'] ?? null;

if (!$date) {
    echo json_encode(["error" => "Missing date"]);
    exit;
}

$slots = [
    "08:00:00",
    "09:00:00",
    "10:00:00",
    "12:00:00",
    "14:00:00",
    "15:00:00",
    "16:00:00",
    "17:00:00"
];

$response = [];

foreach ($slots as $slot) {
    $sql = "SELECT COUNT(*) AS total 
            FROM appointments 
            WHERE appointment_date = ?
            AND appointment_time = ?
            AND status != 'Cancelled'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $date, $slot);
    $stmt->execute();
    $count = $stmt->get_result()->fetch_assoc()['total'];

    $response[$slot] = ($count >= 10); // true = full
}

// Check if ALL time slots are full
$allFull = true;
foreach ($response as $isFull) {
    if (!$isFull) {
        $allFull = false;
        break;
    }
}

$response['all_full'] = $allFull;

echo json_encode($response);
?>
