<?php
include "config.php";

$id = $_POST['id'];

// Get audio file & lesson details
$q = $conn->query("
    SELECT la.audio_file_path, la.lesson_id, l.book_id
    FROM lesson_audio la
    JOIN lessons l ON la.lesson_id = l.lesson_id
    WHERE la.lesson_audio_id = $id
");

$data = $q->fetch_assoc();

$file = $data['audio_file_path'];
$lesson_id = $data['lesson_id'];
$book_id = $data['book_id'];

// Correct folder path
$folder = "uploads/books/Book-$book_id/lesson-$lesson_id/";

// Delete file from folder
if (file_exists($folder . $file)) {
    unlink($folder . $file);
}

// Delete from DB
$conn->query("DELETE FROM lesson_audio WHERE lesson_audio_id=$id");

echo "deleted";
?>
