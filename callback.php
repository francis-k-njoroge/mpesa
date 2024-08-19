<?php
header("Content-Type: application/json");

$response = [
    "ResultCode" => 0,
    "ResultDesc" => "Confirmation Received Successfully"
];

// Get the incoming data
$mpesaResponse = file_get_contents('php://input');

// Decode the JSON to check if it's valid
$decodedResponse = json_decode($mpesaResponse, true);

if ($decodedResponse === null && json_last_error() !== JSON_ERROR_NONE) {
    // Invalid JSON, log the raw input
    $logContent = $mpesaResponse;
} else {
    // Valid JSON, re-encode it to ensure proper formatting
    $logContent = json_encode($decodedResponse, JSON_PRETTY_PRINT);
}

$logFile = "M_PESAConfirmationResponse.json";

// Read the entire file
$currentContent = file_get_contents($logFile);

// If the file is empty or doesn't exist, start with an opening bracket
if (empty($currentContent)) {
    $newContent = "[\n" . $logContent;
} else {
    // Remove the closing bracket if it exists
    $currentContent = rtrim($currentContent, "\n]");
    
    // Add a comma if the file is not empty (i.e., not just "[")
    $newContent = (strlen($currentContent) > 1 ? $currentContent . ",\n" : $currentContent) . $logContent;
}

// Add the closing bracket
$newContent .= "\n]";

// Write the updated content back to the file
file_put_contents($logFile, $newContent);

// Send the response
echo json_encode($response);