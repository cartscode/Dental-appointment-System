<?php
// === DEBUG VERSION OF update_status.php ===
// THIS WILL TELL YOU EXACTLY WHAT IS HAPPENING

// Required files
require_once 'db_connect.php'; 

// Set header to allow AJAX response and logging
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Initialization error.'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check received data
    if (isset($_POST['appointment_id']) && isset($_POST['new_status'])) {
        $appointment_id = (int)$_POST['appointment_id'];
        $new_status = $_POST['new_status']; 

        // Log received data (You can check your browser's console for this)
        error_log("Received ID: " . $appointment_id . ", Status: " . $new_status);

        // Validation
        if (!in_array($new_status, ['Done', 'Missed', 'Pending'])) {
            $response['message'] = 'Invalid status provided: ' . $new_status;
        } else {
            // Prepared statement
            $sql = "UPDATE appointments SET status = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $new_status, $appointment_id); 
                
                if (mysqli_stmt_execute($stmt)) {
                    // Success!
                    $response['success'] = true;
                    $response['message'] = 'Status updated to ' . $new_status;
                } else {
                    // Error executing query
                    $response['message'] = 'DB Execute Error: ' . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            } else {
                // Error preparing query (usually means bad SQL syntax)
                $response['message'] = 'DB Prepare Error: ' . mysqli_error($conn);
            }
        }
    } else {
        $response['message'] = 'Missing required POST data (ID or Status).';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

mysqli_close($conn);

echo json_encode($response);
?>