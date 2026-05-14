<?php
include 'config.php';
$id = intval($_GET['id']);
$type = $_GET['type'] ?? '';

if($id && in_array($type,['instruction','hint'])){
    $row = mysqli_fetch_assoc(mysqli_query($conn,"SELECT instruction_image,hint_image FROM exercise_instruction_hints WHERE id=$id"));
    if($type=='instruction' && $row['instruction_image'] && file_exists('uploads/'.$row['instruction_image'])){
        unlink('uploads/'.$row['instruction_image']);
        mysqli_query($conn,"UPDATE exercise_instruction_hints SET instruction_image='' WHERE id=$id");
    }
    if($type=='hint' && $row['hint_image'] && file_exists('uploads/'.$row['hint_image'])){
        unlink('uploads/'.$row['hint_image']);
        mysqli_query($conn,"UPDATE exercise_instruction_hints SET hint_image='' WHERE id=$id");
    }
    echo json_encode(['status'=>'success']);
    exit;
}

echo json_encode(['status'=>'error']);
