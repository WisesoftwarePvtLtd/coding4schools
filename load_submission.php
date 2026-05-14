<?php
session_start();
include "config.php";

header('Content-Type: application/json');

$user_id     = $_SESSION['LoggedInUserId'] ?? 1;
$exercise_id = intval($_GET['exercise_id'] ?? 0);

$q = $conn->prepare(
  "SELECT code, is_submitted
   FROM submissions
   WHERE user_id=? AND exercise_id=?"
);
$q->bind_param("ii", $user_id, $exercise_id);
$q->execute();

$res = $q->get_result()->fetch_assoc();

echo json_encode($res ?: []);
exit;
