<?php
include("config.php");

if (isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    echo $stmt->execute() ? "success" : "error";

    $stmt->close();
    $conn->close();
}
?>
