<?php
include 'header.php';
$COURSE_NAME = "Python";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $COURSE_NAME ?></title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>
    <script src="https://cdn.jsdelivr.net/pyodide/v0.25.0/full/pyodide.js"></script>

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

        /* LAYOUT */
        .editor-box {
            display: flex;
            height: 80vh;
            gap: 6px
        }

        .editor {
            width: 100%;
            border: 1px solid #333
        }

        iframe {
            width: 40%;
            border: 1px solid #ccc;
            background: #fff
        }

        button {
            background: #1da1f2;
            color: #fff;
            border: none;
            padding: 6px 12px;
            margin-left: 4px;
            cursor: pointer
        }
    </style>
</head>

<body>
    <div class="main">
        <!-- DUSTY HOURGLASS LOADER -->
        <div id="page-loader" class="loader-overlay">
            <div class="hourglass"></div>
        </div>
        <div class="top">
            <div></div>
            <div class="title"><?= $COURSE_NAME ?></div>
            <div>
                <button id="runBtn" class="btn-primary btn" onclick="runPython()">Run</button>
                <!-- <button class="btn-primary btn" onclick="startRun()">Run</button> -->
            </div>
        </div>

        <div class="editor-box">
            <div id="editor" class="editor" style="font-size: 17px;"></div>
            <div id="output" class="editor" style="font-size: 17px;background: #000; color: #fff;"></div>
        </div>


    </div>

    <script>
         showLoader();
        let editor;
        let pyodide;
        let pyodideReady = false;

        // ---------------- MONACO ----------------
        require.config({
            paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' }
        });

        require(['vs/editor/editor.main'], function () {
            editor = monaco.editor.create(document.getElementById('editor'), {

                language: "python",
                theme: "vs-dark",
                automaticLayout: true
            });
        });

        // ---------------- PYODIDE ----------------


        loadPy();
        hideLoader();

        // ---------------- RUN & OPEN NEW TAB ----------------


    </script>

</body>

</html>