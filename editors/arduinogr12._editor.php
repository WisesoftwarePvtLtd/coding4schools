<?php
session_start();

$COURSE_NAME = "ARDUINO GR12";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $COURSE_NAME ?></title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/pyodide/v0.25.0/full/pyodide.js"></script>
    <script src="../js/common.js"></script>
    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f5f7fb
        }

        .main {
            padding: 10px;
            margin-top: 80px;
        }

        /* TOP BAR */
        .top {
            display: flex;
            align-items: center;
            margin-bottom: 8px
        }

        .title {
            flex: 1;
            text-align: center;
            font-size: 20px;
            font-weight: bold
        }



        .editor-box {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 80vh;
        }



        /* BUTTON */
        button {
            background: linear-gradient(135deg, #1da1f2, #007bff);
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        button:hover {
            background: linear-gradient(135deg, #007bff, #0056b3);
            transform: scale(1.05);
        }
    </style>
</head>

<body>

    <body style="
    margin-top: 87px;
">
        <h2 style="text-align: center;"><?= $COURSE_NAME ?></h2>

        <div class="editor-box">


            <button class="btn btn-primary" onclick="openArduino()">🚀 <?= $COURSE_NAME ?></button>


        </div>

        <script>
            function openArduino() {
                window.open(" https://app.arduino.cc/", "_blank");
            }   
        </script>

    </body>

    \

</html>