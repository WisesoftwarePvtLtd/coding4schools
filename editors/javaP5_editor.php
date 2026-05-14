<?php
session_start();
$COURSE_NAME = "Java Processing p5.js";
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

    <script>
        /* avoid require conflict */
        window.require = { paths: {} };
    </script>

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

        /* iframe {
            width: 40%;
            border: 1px solid #ccc;
            background: #fff
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
                <button onclick="runCode()">Run</button>

              
            </div>
        </div>

        <!-- EDITOR + OUTPUT -->
        <div class="editor-box">
            <div id="editor" class="editor"></div>
            <!-- <iframe id="output"></iframe> -->
        </div>

    </div>
    <script>
        showLoader();

        let editor;

        /* MONACO */
        require.config({
            paths: { vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs" }
        });

        require(["vs/editor/editor.main"], function () {

            editor = monaco.editor.create(
                document.getElementById("editor"), {
                value: <?= json_encode($editorCode) ?>,
                language: "javascript",
                theme: "vs-dark",
                automaticLayout: true
            });

            // loadCode();
        });
        hideLoader();


        /* RUN */
        function runCode() {
            if (!editor) return;

            const code = editor.getValue();

            const html =
                '<!DOCTYPE html>' +
                '<html>' +
                '<head>' +
                '<meta charset="UTF-8">' +
                '<script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.9.0/p5.min.js"><\/script>' +
                '</head>' +
                '<body>' +
                '<script>' +
                'window.onerror=function(e){document.body.innerHTML="<pre style=color:red>"+e+"</pre>";};' +
                code +
                '<\/script>' +
                '</body>' +
                '</html>';

            // document.getElementById("output").srcdoc = html;
            const newTab = window.open("", "_blank");

            if (!newTab) {
                alert("Popup blocked! Please allow popups.");
                return;
            }

            newTab.document.open();
            newTab.document.write(html);
            newTab.document.close();
        }

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