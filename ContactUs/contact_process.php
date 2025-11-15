<?php
// contact_process.php - Returns JSON response

header('Content-Type: application/json'); // Tell JavaScript we are sending JSON

include('configg.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Collect form data
    $name = $_POST['name'] ?? ''; 
    $email = $_POST['email'] ?? ''; 
    $message = $_POST['message'] ?? ''; 

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }
    
    // ... (Your existing prepared statement logic) ...

    $sql = "INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Error handling (omitted for brevity, but crucial in real code)

    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        // SUCCESS: Send a JSON response back to JavaScript
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you! Your message has been sent successfully.'
        ]);
        $stmt->close();
        $conn->close();
        exit();
    } else {
        // FAILURE: Send a JSON error response
        echo json_encode([
            'success' => false, 
            'message' => 'Database error: Could not save your message.'
        ]);
        $stmt->close();
        $conn->close();
        exit();
    }
}
?>