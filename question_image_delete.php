<?php
include "config.php";

if(!isset($_POST['id'])){
    echo "error";
    exit;
}

$id = intval($_POST['id']);

/* GET IMAGE */
$res = $conn->query("SELECT main_image FROM questions WHERE question_id=$id");

if($res->num_rows == 0){
    echo "error";
    exit;
}

$row = $res->fetch_assoc();
$image = $row['main_image'];

/* DELETE FILE */
if(!empty($image) && file_exists($image)){
    unlink($image);
}

/* UPDATE DB */
$conn->query("UPDATE questions SET main_image=NULL WHERE question_id=$id");

echo "success";