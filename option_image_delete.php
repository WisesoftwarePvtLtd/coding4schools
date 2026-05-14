<?php
include "config.php";

if(!isset($_POST['id'])) {
    echo "error";
    exit;
}

$id = intval($_POST['id']);

/* GET IMAGE PATH */
$res = $conn->query("SELECT image FROM options WHERE id=$id");

if($res->num_rows == 0){
    echo "error";
    exit;
}

$row = $res->fetch_assoc();
$image = $row['image'];

/* DELETE FILE */
if(!empty($image) && file_exists($image)){
    unlink($image);
}

/* UPDATE DB */
$conn->query("UPDATE options SET image=NULL WHERE id=$id");

echo "success";