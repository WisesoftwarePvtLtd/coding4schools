<?php
session_start();
include 'header.php';

$lesson_id = intval($_GET['lesson_id'] ?? 1);
$exercise_id = intval($_GET['exercise_id'] ?? 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Arduino Lesson</title>

  <!-- Monaco Loader -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>

  <style>
    body {
      font-family: Arial;
      margin: 0;
      background: #f5f7fb
    }

    h2,
    h3 {
      margin-left: 10px
    }

    #editor {
      height: 60vh;
      border: 1px solid #333;
      margin: 10px
    }

    .actions {
      margin: 10px
    }

    button {
      padding: 8px 14px;
      margin-right: 6px;
      cursor: pointer
    }

    iframe {
      margin-top: 20px
    }
  </style>
</head>

<body style="margin-top: 85px;">

  <h2 style="text-align:center;">Arduino – Practice & Submit</h2>

  <!-- PRACTICE MODE -->
  <iframe src="https://ide.mblock.cc/" width="100%" height="650" style="border:none">
  </iframe>


</body>

</html>