<?php
// Set the correct headers for JSON and CORS
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// 1. Get the message from the POST request body
$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data["message"] ?? "";

// Check if a message was actually sent
if (empty($userMessage)) {
    http_response_code(400); // Bad Request
    echo json_encode(["error" => "No message provided."]);
    exit;
}

// 2. Define your API Key and the CORRECTED API Endpoint
$apiKey = "AIzaSyDuOEoXEJ-3xzYQt8Y6-Soctpmc9WHe7LM"; // 🔑 Replace with your actual Gemini API key
$modelName = "gemini-2.5-flash"; // A current and stable model

// *** CRITICAL FIX: The correct endpoint URL structure ***
$url = "https://generativelanguage.googleapis.com/v1beta/models/" . $modelName . ":generateContent?key=" . $apiKey;

// 3. Construct the request payload (body)
$payload = json_encode([
    "contents" => [
        ["role" => "user", "parts" => [["text" => $userMessage]]]
    ]
]);

// 4. Set up the stream context options for the POST request
$options = [
    "http" => [
        "header" => "Content-Type: application/json\r\n",
        "method" => "POST",
        "content" => $payload,
        // Set a timeout to prevent script hang
        "timeout" => 60,
    ],
];

// 5. Make the API call and handle potential errors
$context = stream_context_create($options);
// Use @ to suppress PHP warnings and check return value for failure
$response = @file_get_contents($url, false, $context);

// Check if the API call failed (e.g., network error, invalid API key)
if ($response === FALSE) {
    // Get the last error details for better debugging
    $error = error_get_last();
    http_response_code(500); // Internal Server Error
    echo json_encode([
        "error" => "API Request Failed (Check API Key/Network).", 
        "details" => $error['message'] ?? "Unknown file_get_contents error."
    ]);
} else {
    // 6. Output the successful response from the Gemini API
    echo $response;
}
?>