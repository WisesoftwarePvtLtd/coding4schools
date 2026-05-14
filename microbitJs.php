<?php
session_start();
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
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

<body style="
    margin-top: 87px;
">
    <h2 style="text-align: center;">Microbit Js</h2>

     <div class="editor-box">


        <button class="btn btn-primary" onclick="openMicrobitJs()">🚀 Open Microbit Js</button>


    </div>

    <script>
        function openMicrobitJs() {
            window.open(" https://makecode.microbit.org/", "_blank");
        }   
    </script>

</body>

</html>