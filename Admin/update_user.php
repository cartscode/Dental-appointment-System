<?php
require_once 'db_connect.php';

$id = intval($_POST['id']);
$name = $_POST['name'];
$number = $_POST['number'];
$email = $_POST['email'];
$emergency = $_POST['emergency_contact'];
$gender = $_POST['gender'];

$sql = "UPDATE users SET 
            name='$name',
            number='$number',
            email='$email',
            emergency_contact='$emergency',
            gender='$gender'
        WHERE id=$id";

if (mysqli_query($conn, $sql)) {
    header("Location: /Project in IS104/Admin/admin.php?view=user-accounts&msg=updated");
    exit;
} else {
    echo "Error updating user: " . mysqli_error($conn);
}
?>
