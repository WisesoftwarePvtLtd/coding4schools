<?php
session_start();
$COURSE_NAME = "Game Development in JavaScript";
?>
<?php
include '../config.php';
include '../standard_constants.php';


$exercise_id = intval($_GET['exercise_id'] ?? 0);


$editorCode = "";
$userRoles = $_SESSION['LoggedInUserRoles'] ?? [];
$studentRoleId = $userRoles['student'] ?? null;

$editorData = [
    'js' => '',
    'html' => '',
    'css' => ''
];

if ($studentRoleId != STUDENT) {
    if ($exercise_id > 0) {

        $stmt = $conn->prepare("SELECT code FROM course_code WHERE exercise_id=?");
        $stmt->bind_param("i", $exercise_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {

            // because DB me JSON save ho raha hai
            $data = json_decode($row['code'], true);

            $editorData['js'] = $data['js'] ?? '';

            $editorData['html'] = $data['html'] ?? '';
            $editorData['css'] = $data['css'] ?? '';

        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $COURSE_NAME ?></title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>
    <script src="../js/common.js"></script>


    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f5f7fb
        }

        .main {
            padding: 10px;
        }

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

        .editor-box {
            display: flex;
            height: 60vh;
            gap: 6px;
        }

        .editor {
            width: 100%;
            border: 1px solid #333;
        }

        iframe {
            width: 100%;
            height: 30vh;
            border: 1px solid #ccc;
            background: #fff;
        }

        button {
            background: #1da1f2;
            color: #fff;
            border: none;
            padding: 6px 12px;
            margin-left: 4px;
            cursor: pointer;
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
            <div id="jsEditor" class="editor"></div>
            <div id="htmlEditor" class="editor"></div>
            <div id="cssEditor" class="editor"></div>
        </div>

    </div>

    <script>
        showLoader();

        let jsEditor, htmlEditor, cssEditor;

        require.config({
            paths: { vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs" }
        });

        require(["vs/editor/editor.main"], function () {
            const dbData = <?= json_encode($editorData) ?>;
            jsEditor = monaco.editor.create(
                document.getElementById("jsEditor"), {
                value: dbData.js || "// JavaScript Editor (VR Logic)\n\n",
                language: "javascript",
                theme: "vs-dark",
                automaticLayout: true, fontSize: 20
            });

            htmlEditor = monaco.editor.create(
                document.getElementById("htmlEditor"), {
                value: dbData.html || "<!-- HTML Editor (VR Scene) -->\n\n",
                language: "html",
                theme: "vs-dark",
                automaticLayout: true, fontSize: 20
            });

            cssEditor = monaco.editor.create(
                document.getElementById("cssEditor"), {
                value: dbData.css || "/* CSS Editor (Styling) */\n\n",
                language: "css",
                theme: "vs-dark",
                automaticLayout: true, fontSize: 20
            });

            //loadCode();
        });
        hideLoader();


        function runCode() {

            var html = htmlEditor.getValue();
            var css = cssEditor.getValue();
            var js = jsEditor.getValue();

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

    <script>
        // let editor;

        // require.config({
        //     paths: { vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs" }
        // });

        // require(["vs/editor/editor.main"], function () {

        //     editor = monaco.editor.create(
        //         document.getElementById("editor"), {
        //         value: <?= json_encode($editorCode) ?>,
        //         language: "javascript",
        //         theme: "vs-dark",
        //         automaticLayout: true
        //     });

        //     loadCode();
        // });

        // function runCode() {

        //     const js = editor.getValue();

        //     const output =
        //         "<!DOCTYPE html>" +
        //         "<html>" +
        //         "<head>" +
        //         "<meta charset='UTF-8'>" +
        //         "<style>body{margin:0;}canvas{background:#eee;}</style>" +
        //         "</head>" +
        //         "<body>" +
        //         "<canvas id='game' width='400' height='300'></canvas>" +
        //         "<script>" + js + "<\/script>" +
        //         "</body>" +
        //         "</html>";

        //     // document.getElementById("output").srcdoc = output;
        //     const newTab = window.open("", "_blank");

        //     if (!newTab) {
        //         alert("Popup blocked! Please allow popups.");
        //         return;
        //     }

        //     newTab.document.open();
        //     newTab.document.write(output);
        //     newTab.document.close();
        // }

        // function getEditorCode() {
        //     return {
        //         code: editor ? editor.getValue() : ""
        //     };
        // }

        // function setEditorCode(data) {
        //     if (!data) return;

        //     if (data.code !== undefined && editor) {
        //         editor.setValue(data.code);
        //     }
        // }

        function getEditorCode() {
            return {
                js: jsEditor.getValue(),
                html: htmlEditor.getValue(),
                css: cssEditor.getValue()
            };
        }

        function setEditorCode(data) {
            if (!data) return;
            if (data.js !== undefined) {
                cssEditor.setValue(data.js);
            }
            if (data.html !== undefined) {
                jsEditor.setValue(data.html);
            }
            if (data.css !== undefined) {
                htmlEditor.setValue(data.css);
            }

        }
    </script>

</body>

</html>