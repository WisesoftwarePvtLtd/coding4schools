<?php
$filename = "projects/project1.sb3";

if(file_exists($filename)) {
    // Serve the .sb3 file content
    header('Content-Type: application/json');
    echo file_get_contents($filename);
} else {
    echo json_encode(["error" => "Project not found"]);
}
?>