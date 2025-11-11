<?php
include('config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $db_password = $row['password'];

        // Use password_verify for hashed password
        if (password_verify($password, $db_password)) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            header("Location: /Project in IS104/Admin/admin.html");
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.location.href='/Project in IS104/admin/adminlogin.html';</script>";
        }
    } else {
        echo "<script>alert('Username not found!'); window.location.href='/Project in IS104/admin/adminlogin.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
