<?php
include('config.php');
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id'])) {
    header("Location: /Project in IS104/Login/login.html");
    exit();
}

if (isset($_GET['count_date'])) {
    $countDate = $_GET['count_date'];

    $stmt = $conn->prepare("SELECT COUNT(*) FROM appointments WHERE date = ?");
    $stmt->bind_param("s", $countDate);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    header('Content-Type: application/json');
    echo json_encode(["count" => $count]);
    exit();
}
?>