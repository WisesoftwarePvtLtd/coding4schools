<?php

include '../config.php';


$editor_id = $_GET['editor_id'] ?? null;


// ✅ mysqli prepared statement
$stmt = $conn->prepare(
  "SELECT * FROM editors WHERE editor_id = ?"
);
$stmt->bind_param("i", $editor_id);
$stmt->execute();

$result = $stmt->get_result();
$editor = $result->fetch_assoc();


?>
<!DOCTYPE html>
<html>

<head>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"></script>
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

    <!-- TOP BAR -->
    <div class="top">
      <div></div>
      <div class="title"></div>
      <div>
        <button onclick="runCode()" class="btn btn-primary">Run</button>
      
      </div>
    </div>

    <!-- EDITOR + OUTPUT -->
    <div class="editor-box">
      <div id="editor" class="editor"></div>

    </div>

  </div>

  <script>

showLoader();
    const editorData = <?php echo json_encode($editor); ?>;

    const editor = new EditorEngine({
      container: "editor",
      editorType: editorData.editor_type,
      language: editorData.language,

      exerciseId: editorData.exerciseId,
      role: "student" // or teacher
    });
        hideLoader();


    function runCode() {

      const code = editor.getCode();
      const mode = editorData.editor_language;;

      // ✅ JavaScript directly browser me run
      if (mode === "javascript") {

        const output =
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

        const newTab = window.open("", "_blank");
        newTab.document.write(output);
        newTab.document.close();
        return;
      }

      // ✅ Python backend run
      fetch("../run.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body:
          "code=" + encodeURIComponent(code) +
          "&mode=python"
      })
        .then(r => r.text())
        .then(out => {
          const win = window.open("", "_blank");
          win.document.write("<pre>" + out + "</pre>");
          win.document.close();
        });
    }

    function getEditorCode() {
      return {
        code: editor ? editor.getCode() : ""
      };
    }

    function setEditorCode(data) {
      if (!data) return;

      if (data.code !== undefined) {
        editor.setCode(data.code);
      }
    }


  </script>

</body>

</html>