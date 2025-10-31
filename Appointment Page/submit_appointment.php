<?php
// submit_appointment.php (RESTORED SECURE CODE)
include('config.php'); // Your existing connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // The data comes from the 'name' attributes in the HTML form:
    $service = $_POST['service'] ?? ''; 
    $date    = $_POST['date'] ?? '';    
    $time    = $_POST['time'] ?? '';    

    // 1. Prepare the SQL INSERT statement with '?' placeholders
    $sql = "INSERT INTO appointments (service_name, appointment_date, appointment_time) VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // 2. Bind parameters (s = string, s = string, s = string)
        mysqli_stmt_bind_param($stmt, "sss", $service, $date, $time);

        // 3. Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Success! Redirect the user.
            echo "<script>alert('Appointment booked successfully!'); window.location.href='/confirmation_page.html';</script>";
        } else {
            // ERROR HANDLING: Print the specific MySQL error
            echo "<script>alert('Database Error: " . mysqli_error($conn) . "'); window.history.back();</script>";
            // If the alert doesn't show, uncomment the direct echo below for more visibility:
            // echo "Error executing statement: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        // ERROR HANDLING: If the statement could not be prepared
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}
?>