<?php
// delete_appointment.php

require_once 'db_connect.php';
session_start();

// ✅ Security check (optional but recommended)


// ✅ Check if the ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize input

    // ✅ Prepare and execute delete query
    $sql = "DELETE FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // ✅ Redirect back to admin page with success message
        echo "<script>
                alert('Appointment record deleted successfully!');
                window.location.href='/Project in IS104/Admin/admin.php?view=missed-appointments';
              </script>";
    } else {
        // ❌ Error message
        echo "<script>
                alert('Error deleting appointment: " . addslashes($stmt->error) . "');
                window.location.href='/Project in IS104/Admin/admin.php?view=missed-appointments';
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    // ❌ No ID provided
    echo "<script>
            alert('Invalid request: No appointment ID provided.');
            window.location.href='admin_dashboard.php?view=missed-appointments';
          </script>";
}
?>
