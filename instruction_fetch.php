<?php
include 'config.php';

$id = intval($_GET['id'] ?? 0);

$intructionfetch = $conn->prepare(
    "SELECT * FROM exercise_instruction_hints WHERE id = ?"
);
$intructionfetch->bind_param("i", $id);
$intructionfetch->execute();

$res = $intructionfetch->get_result();
$row = $res->fetch_assoc();

if (!$row) {
    echo json_encode(["status" => "error"]);
    exit;
}

echo json_encode([
    "status" => "success",
    "instruction" => $row
]);
