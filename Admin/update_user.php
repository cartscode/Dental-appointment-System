<?php
// 1. Require the database connection file
require_once 'db_connect.php';

// 2. Safely retrieve and validate the ID
// Check if 'id' is set and is a non-empty numeric value
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    // Crucial: Handle the missing/invalid ID error explicitly
    echo "Error: Invalid ID provided.";
    exit;
}

// 3. Cast the ID as an integer (for extra measure)
$id = (int)$_POST['id'];

// 4. Retrieve other POST data
$name = $_POST['name'] ?? ''; // Use null coalescing for safety
$number = $_POST['number'] ?? '';
$email = $_POST['email'] ?? '';
$emergency = $_POST['emergency_contact'] ?? '';
$gender = $_POST['gender'] ?? '';

// 5. Use a Prepared Statement for Security and Reliability
$sql = "UPDATE users SET 
            name = ?,
            number = ?,
            email = ?,
            emergency_contact = ?,
            gender = ?
        WHERE id = ?";

// Prepare the statement
$stmt = mysqli_prepare($conn, $sql);

// Check if the preparation was successful
if ($stmt === false) {
    die("Error preparing statement: " . mysqli_error($conn));
}

// Bind parameters: 'sssssi' means 5 strings and 1 integer (for the ID)
mysqli_stmt_bind_param($stmt, 'sssssi', $name, $number, $email, $emergency, $gender, $id);

// Execute the statement
if (mysqli_stmt_execute($stmt)) {
    // Success: Redirect the user
    header("Location: /Project in IS104/Admin/admin.php?view=user-accounts&msg=updated");
    exit;
} else {
    // Failure: Display the specific error
    echo "Error executing update: " . mysqli_stmt_error($stmt);
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

?>