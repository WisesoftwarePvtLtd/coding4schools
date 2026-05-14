<?php
session_start();
// include 'header.php';
$COURSE_NAME = "Virtual Reality Programming";
?>
<?php
include '../config.php';
include '../standard_constants.php';


$exercise_id = intval($_GET['exercise_id'] ?? 0);


$editorCode = "";
$userRoles = $_SESSION['LoggedInUserRoles'] ?? [];
$studentRoleId = $userRoles['student'] ?? null;

$editorData = [
    'html' => '',
    'css' => '',
    'js' => ''
];

if ($studentRoleId != STUDENT && $exercise_id > 0) {

    $stmt = $conn->prepare("SELECT code FROM course_code WHERE exercise_id=?");
    $stmt->bind_param("i", $exercise_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {

        $data = json_decode($row['code'], true);

        $editorData['html'] = $data['html'] ?? '';
        $editorData['css'] = $data['css'] ?? '';
        $editorData['js'] = $data['js'] ?? '';
    }

    $stmt->close();
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
            padding: 10px
        }

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
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            height: 60vh;
            gap: 4px;
        }

        .editor {
            border: 1px solid #333
        }

        iframe {
            width: 100%;
            height: 30vh;
            border: 1px solid #ccc;
            background: #fff;
        }

        button {
            background: #2196f3;
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

        <!-- EDITORS -->
        <div class="editor-box">
            <div id="jsEditor" class="editor"></div>
            <div id="htmlEditor" class="editor"></div>
            <div id="cssEditor" class="editor"></div>
        </div>

        <!-- OUTPUT -->
        <!-- <iframe id="output"></iframe> -->

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
                value: dbData.js || "// JS here",
                language: "javascript",
                theme: "vs-dark",
                automaticLayout: true, fontSize: 20
            });

            htmlEditor = monaco.editor.create(
                document.getElementById("htmlEditor"), {
                value: dbData.html || "<!-- HTML here -->",
                language: "html",
                theme: "vs-dark",
                automaticLayout: true, fontSize: 20
            });

            cssEditor = monaco.editor.create(
                document.getElementById("cssEditor"), {
                value: dbData.css || "/* CSS here */",
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

            // 👉 open in new tab
            var newTab = window.open("", "_blank");
            newTab.document.open();
            newTab.document.write(output);
            newTab.document.close();
        }

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