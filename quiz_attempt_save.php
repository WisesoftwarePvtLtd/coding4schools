<?php
session_start();
header('Content-Type: application/json');
require_once "config.php";

// $user_id    = $_SESSION['LoggedInUserId'] ?? 1;
// $quiz_id    = intval($_POST['quiz_id'] ?? 0);
// $started_at = $_POST['started_at'] ?? date('Y-m-d H:i:s');

// if ($quiz_id <= 0) {
//     echo json_encode([
//         "status" => "error",
//         "msg" => "Invalid quiz id"
//     ]);
//     exit;
// }

// /* ✅ USE PREPARED STATEMENT */
// $stmt = $conn->prepare("
//     INSERT INTO quiz_attempt (quiz_id, user_id, started_at)
//     VALUES (?, ?, ?)
// ");

// $stmt->bind_param("iis", $quiz_id, $user_id, $started_at);

// if (!$stmt->execute()) {
//     echo json_encode([
//         "status" => "error",
//         "msg" => "Insert failed",
//         "error" => $stmt->error
//     ]);
//     exit;
// }

// echo json_encode([
//     "status"     => "started",
//     "attempt_id" => $stmt->insert_id,
//     "quiz_id"    => $quiz_id
// ]);
// exit;


$user_id    = $_SESSION['LoggedInUserId'] ?? 0;
$quiz_id    = intval($_POST['quiz_id'] ?? 0);
$started_at = $_POST['started_at'] ?? date('Y-m-d H:i:s');

if ($user_id <= 0 || $quiz_id <= 0) {
    echo json_encode([
        "status" => "error",
        "msg" => "Invalid user or quiz id"
    ]);
    exit;
}

/* 1️⃣ CHECK EXISTING QUIZ ATTEMPT */
$check = $conn->prepare("
    SELECT quiz_attempt_id
    FROM quiz_attempt
    WHERE quiz_id = ? AND user_id = ?
    LIMIT 1
");
$check->bind_param("ii", $quiz_id, $user_id);
$check->execute();
$check->store_result();

/* 2️⃣ IF EXISTS → UPDATE */
if ($check->num_rows > 0) {

    $check->bind_result($attempt_id);
    $check->fetch();

    $update = $conn->prepare("
        UPDATE quiz_attempt
        SET started_at = ?
        WHERE quiz_attempt_id = ?
    ");
    $update->bind_param("si", $started_at, $attempt_id);
    $update->execute();

    echo json_encode([
        "status"     => "updated",
        "attempt_id" => $attempt_id,
        "quiz_id"    => $quiz_id
    ]);
    exit;
}

/* 3️⃣ NOT EXISTS → INSERT */
$insert = $conn->prepare("
    INSERT INTO quiz_attempt (quiz_id, user_id, started_at)
    VALUES (?, ?, ?)
");
$insert->bind_param("iis", $quiz_id, $user_id, $started_at);

if (!$insert->execute()) {
    echo json_encode([
        "status" => "error",
        "msg" => "Insert failed",
        "error" => $insert->error
    ]);
    exit;
}

echo json_encode([
    "status"     => "started",
    "attempt_id" => $insert->insert_id,
    "quiz_id"    => $quiz_id
]);
exit;
