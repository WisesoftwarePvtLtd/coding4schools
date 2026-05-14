<?php
// Get file name from query string
$file = basename($_GET['file']);  // sanitize input
$path = __DIR__ . "/projects/" . $file;  // path to projects folder

// Check if file exists
if (!file_exists($path)) {
    http_response_code(404);
    exit("File not found");
}

// Set headers so browser/Scratch VM can fetch it
header('Content-Type: application/octet-stream');
header('Access-Control-Allow-Origin: *');  // ✅ Allow cross-origin requests

// Send the file content
readfile($path);