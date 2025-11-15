<?php
include 'config.php';

if (!isset($_GET['id'])) {
    die("No ID provided.");
}

$id = intval($_GET['id']);

$sql = "DELETE FROM contacts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Message deleted successfully!'); window.location.href='admin.php';</script>";
} else {
    echo "Error deleting message.";
}
?>
