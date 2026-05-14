<?php
session_start();
// include 'header.php';
$COURSE_NAME = "Python";
?>
<?php
include '../config.php';
include '../standard_constants.php';


$exercise_id = intval($_GET['exercise_id'] ?? 0);


$editorCode = "";
$userRoles = $_SESSION['LoggedInUserRoles'] ?? [];
$studentRoleId = $userRoles['student'] ?? null;

if ($studentRoleId != STUDENT ) {
    if ($exercise_id > 0) {

        $stmt = $conn->prepare("SELECT code FROM course_code WHERE exercise_id=?");
        $stmt->bind_param("i", $exercise_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {

            // because DB me JSON save ho raha hai
            $data = json_decode($row['code'], true);
            $editorCode = $data['code'] ?? '';
            
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
    <script src="https://cdn.jsdelivr.net/pyodide/v0.25.0/full/pyodide.js"></script>
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

        /* iframe{
    width:40%;
    border:1px solid #ccc;
    background:#fff
} */

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
        <!-- TOP BAR -->
        <div class="top">
            <div></div>
            <div class="title"><?= $COURSE_NAME ?></div>
            <div>
                <button id="runBtn" class="btn-primary btn" onclick="runPython()">Run</button>
            </div>
        </div>

        <!-- EDITOR + OUTPUT SIDE BY SIDE -->
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
                value: <?= json_encode($editorCode) ?>,
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
    <script>
        function getEditorCode() {
            return {
                code: editor ? editor.getValue() : ""
            };
        }

        function setEditorCode(data) {
            if (!data) return;

            if (data.code !== undefined && editor) {
                editor.setValue(data.code);
            }
        }
    </script>

</body>

</html>