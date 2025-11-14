<?php
require_once 'db_connect.php';

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    $sql = "DELETE FROM users WHERE id = $user_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: /Project in IS104/Admin/admin.php?view=user-accounts&msg=deleted");
        exit;
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
}
?>
