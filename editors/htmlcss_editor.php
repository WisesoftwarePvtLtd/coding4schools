<?php
session_start();
// include 'header.php';
$COURSE_NAME = "HTML & CSS";
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
            padding: 10px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px
        }

        .title {
            flex: 1;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .editor-box {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            height: 70vh;
            gap: 4px;
        }

        .editor {
            border: 1px solid #333
        }

        iframe {
            width: 100%;
            height: 30vh;
            border: 1px solid #ccc;
            background: #5ca5e9
        }

        button {
            background: #2196f3;
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
                <button onclick="runCode()" class="btn btn-primary">Run</button>
                 <?php if (userHasPermission(SAVE_CODE_LOCALLY)) { ?>
                
                <button onclick="saveCode()" class="btn btn-success">Save Locally</button>
                <?php } ?>
                 <?php if (userHasPermission(LOAD_SAVED_CODE)) { ?>
                <button onclick="document.getElementById('fileInput').click()">Load Saved Code</button>
                <input type="file" id="fileInput" accept=".html" style="display:none" onchange="loadFile(event)">
                <?php } ?>
            </div>
        </div>

        <div class="editor-box">
            <div id="e1" class="editor" style="font-size: 20px;"></div>
            <div id="e2" class="editor" style="font-size: 17px;"></div>
            <div id="e3" class="editor" style="font-size: 17px;"></div>
        </div>

        <!-- <iframe id="output"></iframe> -->

    </div>

    <script>
        showLoader();

        let e1, e2, e3;

        require.config({
            paths: { vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs" }
        });

        require(["vs/editor/editor.main"], function () {
            const dbData = <?= json_encode($editorData) ?>;
            e1 = monaco.editor.create(
                document.getElementById("e1"),
                { value: dbData.html || "<!-- HTML here -->", language: "html", theme: "vs-dark", automaticLayout: true, fontSize:20  }
            );

            e2 = monaco.editor.create(
                document.getElementById("e2"),
                { value: dbData.css || "/* CSS here */", language: "css", theme: "vs-dark", automaticLayout: true, fontSize:20  }
            );

            e3 = monaco.editor.create(
                document.getElementById("e3"),
                { value: dbData.js || "// JS here", language: "javascript", theme: "vs-dark", automaticLayout: true, fontSize:20  }
            );



        });
        hideLoader();



        function runCode() {
            const out =
                e1.getValue() +
                "<style>" + e2.getValue() + "</style>" +
                "<script>" + e3.getValue() + "<\/script>";

            const newTab = window.open();
            newTab.document.open();
            newTab.document.write(out);
            newTab.document.close();
        }



    </script>
    <script>
        function getEditorCode() {
            return {
                html: e1.getValue(),
                css: e2.getValue(),
                js: e3.getValue()
            };
        }
    </script>
    <script>
        function setEditorCode(data) {
            if (!data) return;

            if (data.html !== undefined) {
                e1.setValue(data.html);
            }
            if (data.css !== undefined) {
                e2.setValue(data.css);
            }
            if (data.js !== undefined) {
                e3.setValue(data.js);
            }
        }
    </script>

    <script>

function saveCode() {
    const codeData = {
        html: e1.getValue(),
        css: e2.getValue(),
        js: e3.getValue()
    };

    const formData =
        "exercise_id=<?= $exercise_id ?>" +
        "&code=" + encodeURIComponent(JSON.stringify(codeData));

    fetch("../save_codefile.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: formData
    })
    .then(res => res.blob())
    .then(blob => {
        const a = document.createElement("a");
        a.href = URL.createObjectURL(blob);
        a.download = "exercise_<?= $exercise_id ?>.html";
        a.click();
    });
}


function loadFile(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();

    reader.onload = function(e) {
        const content = e.target.result;

        try {
            // CSS extract
            const cssMatches = [...content.matchAll(/<style[^>]*>([\s\S]*?)<\/style>/gi)];
            const css = cssMatches.map(m => m[1]).join("\n");

            // JS extract
            const jsMatches = [...content.matchAll(/<script[^>]*>([\s\S]*?)<\/script>/gi)];
            const js = jsMatches.map(m => m[1]).join("\n");

            // FULL HTML (clean)
            let html = content;

            html = html.replace(/<style[\s\S]*?<\/style>/gi, "");
            html = html.replace(/<script[\s\S]*?<\/script>/gi, "");

            html = html.trim();

            // set editors
            e1.setValue(html);
            e2.setValue(css.trim());
            e3.setValue(js.trim());

            // alert("Clean HTML Loaded ✅");

        } catch (err) {
            console.log(err);
            alert("Invalid File ❌");
        }
    };

    reader.readAsText(file);
}

<?php
function userHasPermission($permissionId)
{

    if (!isset($_SESSION['UserPermissions'])) {
        return false;
    }

    return array_key_exists($permissionId, $_SESSION['UserPermissions']);
}

?>

</script>

</body>

</html>