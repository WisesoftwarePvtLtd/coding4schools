<?php
session_start();
include 'config.php';

if(!isset($_POST['id'])){
    exit("error");
}

$course_id = (int)$_POST['id'];

/* FETCH IMAGE NAME */
$stmt = $conn->prepare("SELECT course_cover_page FROM courses WHERE course_id=?");
$stmt->bind_param("i",$course_id);
$stmt->execute();
$stmt->bind_result($image);
$stmt->fetch();
$stmt->close();

if(!$image){
    exit("error");
}

/* FILE PATH */
$path = "uploads/courses/course-$course_id/$image";

/* DELETE FILE */
if(file_exists($path)){
    unlink($path);
}

/* UPDATE DB */
$stmt = $conn->prepare(
    "UPDATE courses SET course_cover_page=NULL WHERE course_id=?"
);
$stmt->bind_param("i",$course_id);
$stmt->execute();
$stmt->close();

echo "success";
