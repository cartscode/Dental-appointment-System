<?php
require 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Update appointment to Cancelled
    $sql = "UPDATE appointments SET status='Cancelled' WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    // Redirect back to patient dashboard
    header("Location: patient_dashboard.php?cancel_success=1");
    exit;
} else {
    echo "No appointment ID found.";
}
?>
