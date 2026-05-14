<?php
include 'config.php';

$problem_id = intval($_POST['problem_id'] ?? 0);
if ($problem_id <= 0) {
    exit("invalid");
}

/* fetch image */
$stmt = $conn->prepare("SELECT problem_image FROM problem WHERE problem_id=?");
$stmt->bind_param("i", $problem_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!empty($result['problem_image'])) {
    $filePath = "uploads/" . $result['problem_image'];
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

/* update DB */
$stmt = $conn->prepare("UPDATE problem SET problem_image=NULL WHERE problem_id=?");
$stmt->bind_param("i", $problem_id);
$stmt->execute();
$stmt->close();

echo "success";
