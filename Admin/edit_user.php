<?php
require_once 'db_connect.php';

if (!isset($_GET['id'])) {
    die("User ID missing.");
}

$user_id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");

if (mysqli_num_rows($result) == 0) {
    die("User not found.");
}

$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>

<h2>Edit User</h2>

<form method="POST" action="update_user.php">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

    Name: <br>
    <input type="text" name="name" value="<?php echo $user['name']; ?>"><br><br>

    Number: <br>
    <input type="text" name="number" value="<?php echo $user['number']; ?>"><br><br>

    Email: <br>
    <input type="email" name="email" value="<?php echo $user['email']; ?>"><br><br>

    Emergency Number: <br>
    <input type="text" name="emergency_contact" value="<?php echo $user['emergency_contact']; ?>"><br><br>

    Gender: <br>
    <input type="text" name="gender" value="<?php echo $user['gender']; ?>"><br><br>

    <button type="submit">Save Changes</button>
</form>

</body>
</html>
