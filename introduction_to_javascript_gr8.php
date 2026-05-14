<?php
include 'header.php';
$COURSE_NAME = "Introduction to JavaScript gr8";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $COURSE_NAME ?></title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f5f7fb;
        }

        .main {
            padding: 10px;
            margin-top: 85px;
        }

        /* TOP BAR */
        .top {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .title {
            flex: 1;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        button {
            background: #1da1f2;
            color: #fff;
            border: none;
            padding: 6px 12px;
            margin-left: 4px;
            cursor: pointer;
        }

        /* LAYOUT */
        .editor-box {
            display: flex;
            height: 80vh;
            gap: 6px;
        }

        .editor {
            width: 100%;
            border: 1px solid #333;
        }

        iframe {
            width: 40%;
            border: 1px solid #ccc;
            background: #fff;
        }
    </style>
</head>

<body>
    <div class="main">
 <!-- DUSTY HOURGLASS LOADER -->
        <div id="page-loader" class="loader-overlay">
            <div class="hourglass"></div>
        </div>

        <!-- TOP BAR -->
        <div class="top">
            <div></div>
            <div class="title"><?= $COURSE_NAME ?></div>
            <div>
                <button onclick="runCode()" class="btn btn-primary">Run</button>

            </div>
        </div>

        <!-- EDITOR + OUTPUT -->
        <div class="editor-box">

            <div id="htmlgr8Editor" class="editor"></div>
            <div id="cssgr8Editor" class="editor"></div>
            <div id="jsgr8Editor" class="editor"></div>
        </div>

    </div>

    <script>
        showLoader();

        let htmlgr8Editor, cssgr8Editor, jsgr8Editor;

        require.config({
            paths: { vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs" }
        });

        require(["vs/editor/editor.main"], function () {



            htmlgr8Editor = monaco.editor.create(
                document.getElementById("htmlgr8Editor"), {
                value: "<!-- HTML Editor (VR Scene) -->\n\n",
                language: "html",
                theme: "vs-dark",
                automaticLayout: true, fontSize:20 
            });

            cssgr8Editor = monaco.editor.create(
                document.getElementById("cssgr8Editor"), {
                value: "/* CSS Editor (Styling) */\n\n",
                language: "css",
                theme: "vs-dark",
                automaticLayout: true, fontSize:20 
            });

            jsgr8Editor = monaco.editor.create(
                document.getElementById("jsgr8Editor"), {
                value: "// JavaScript Editor (VR Logic)\n\n",
                language: "javascript",
                theme: "vs-dark",
                automaticLayout: true, fontSize:20 
            });


        });
        hideLoader();



        /* RUN CODE */
        function runCode() {

            var html = htmlgr8Editor.getValue();
            var css = cssgr8Editor.getValue();
            var js = jsgr8Editor.getValue();

            var output =
                "<!DOCTYPE html>" +
                "<html>" +
                "<head>" +
                "<meta charset='UTF-8'>" +
                "<script src='https://aframe.io/releases/1.4.2/aframe.min.js'><\/script>" +
                "<style>" + css + "</style>" +
                "</head>" +
                "<body>" +
                html +
                "<script>" + js + "<\/script>" +
                "</body>" +
                "</html>";

            // document.getElementById("output").srcdoc = output;
            const newTab = window.open("", "_blank");

            if (!newTab) {
                alert("Popup blocked! Please allow popups.");
                return;
            }

            newTab.document.open();
            newTab.document.write(output);
            newTab.document.close();
        }


    </script>

</body>

</html>