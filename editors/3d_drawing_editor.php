<?php
// include 'header.php';
$COURSE_NAME = "3D Drawing";
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
    <div class="main">

        <!-- TOP BAR -->
        <div class="top">
            <div></div>
            <div class="title"><?= $COURSE_NAME ?></div>
            
        </div>



        <div class="editor-box">

            <!-- <div id="editor" class="editor"></div> -->
            <!-- <iframe id="output"></iframe> -->


            <button class="btn btn-primary" onclick="openTinkercad()">🚀 Open 3D Editor</button>




        </div>
    </div>

    </div>
    <script>
        function openTinkercad() {
            window.open(" https://www.tinkercad.com/dashboard/designs/3d", "_blank");
        }   
    </script>

<!-- <script>
let editor;

require.config({
    paths:{vs:"https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs"}
});

require(["vs/editor/editor.main"], function () {

    editor = monaco.editor.create(
        document.getElementById("editor"), {
        value:"// 3D Drawing (JavaScript)\n",
        language:"javascript",
        theme:"vs-dark",
        automaticLayout:true
    });

    loadCode();
});

function runCode(){

    const js = editor.getValue();

    const output =
        "<!DOCTYPE html>" +
        "<html>" +
        "<head>" +
        "<meta charset='UTF-8'>" +

        // Three.js CDN (students can use it)
        "<script src='https://unpkg.com/three@0.155.0/build/three.min.js'><\/script>" +

        "<style>body{margin:0;overflow:hidden;}</style>" +
        "</head>" +
        "<body>" +
        "<canvas id='canvas'></canvas>" +
        "<script>" + js + "<\/script>" +
        "</body>" +
        "</html>";

    document.getElementById("output").srcdoc = output;
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

// function saveCode(){
//     fetch("save_code.php",{
//         method:"POST",
//         headers:{"Content-Type":"application/x-www-form-urlencoded"},
//         body:
//         "course=<?= urlencode($COURSE_NAME) ?>&type=monaco&code=" +
//         encodeURIComponent(editor.getValue())
//     })
//     .then(r=>r.text())
//     .then(alert);
// }

// function loadCode(){
//     fetch("load_code.php?course=<?= urlencode($COURSE_NAME) ?>")
//     .then(r=>r.json())
//     .then(d=>{
//         if(d && d.code){
//             editor.setValue(d.code);
//         }
//     });
// }
</script> -->

</body>
</html>
