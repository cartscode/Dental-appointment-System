<?php
require 'db_connect.php';

// Check if 'id' is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare and execute delete query
    $sql = "DELETE FROM email_sent_records WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Redirect back to dashboard (you can adjust the URL)
    header("Location: admin.php?view=email-records");
    exit;
} else {
    echo "Invalid request.";
}
?>
