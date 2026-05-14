<?php
session_start();
include 'config.php';

$lesson_id = intval($_GET['lesson_id'] ?? 0);

if ($lesson_id) {
    $res = mysqli_query($conn, "SELECT sprite_image FROM exercises WHERE lesson_id = $lesson_id");
    $row = mysqli_fetch_assoc($res);
    $sprite = $row['sprite_image'] ?? '';

    if ($sprite && file_exists('uploads/' . $sprite)) {
        unlink('uploads/' . $sprite); // delete file
    }

    mysqli_query($conn, "UPDATE exercises SET sprite_image='' WHERE lesson_id = $lesson_id");

    echo json_encode(['status' => 'success']);
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid lesson ID']);
