<?php
//session_start();
// ❗ header.php is OK here (UI page)
include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Course Code Editor</title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>

<style>
body{margin:0;font-family:Arial;background:#f5f7fb}
.page{display:flex;height:calc(100vh - 70px)}

.main{flex:1;padding:10px}
.top{display:flex;justify-content:space-between;margin-bottom:8px}
.editor-box{display:flex;height:calc(100vh - 140px);gap:6px}
#editor{width:60%;border:1px solid #ccc}
iframe{width:40%;border:1px solid #ccc;background:#fff}
button{background:#2196f3;color:#fff;border:none;padding:6px 14px;cursor:pointer}
.btn-save{background:#4CAF50}
select{font-size:13px;padding:4px}
</style>
</head>

<body>
<div class="page">
    

    <div class="main">
        <div class="top">
            <h3>Code Editor</h3>
            <div>
                <select id="course"></select>
                <button id="runBtn" onclick="runCode()">▶ Run</button>
                <button id="saveBtn" class="btn-save" onclick="saveCode()" disabled>Save</button>
                <button onclick="loadSavedCode()">Load</button>
            </div>
        </div>

        <div class="editor-box">
            <div id="editor"></div>
            <iframe id="output"></iframe>
        </div>
    </div>
</div>

<script>
/* ===============================
   COURSE CONFIG
================================ */
const courses = [
 
 
 
 
 
 
 
 
 

 // External (open full page)
 {name:"Codey Rockey", external:true, url:"codey.php"},
 {name:"AI for Juniors", external:true, url:"ai_for_juniors"},
 {name:"Java Processing p5 js", external:true, url:"javaP5.php"},
 {name:"Data Visualisation", external:true, url:"data_visualisation.php"},
 {name:"Game Development in JavaScript", external:true, url:"game_dev_js.php"},
 {name:"Introduction to Data Analytics", external:true, url:"introduction_to_data_analytics.php"},
 {name:"Introduction to Artificial Intelligence", external:true, url:"introduction_to_ai.php"},
 {name:"Python", external: true, url:"python_editor.php"},
 {name:"3D Drawing", external: true, url:"3d_drawing.php"},
 {name:"Introduction to JavaScript gr8", external:true, url:"introduction_to_javascript_gr8.php"},
 {name:"Introduction To Coding Logic", external:true, url:"coding_logic.php"},
 {name:"HTML and CSS", external:"true", url:"html_css.php"},
 {name:"Virtual Reality Programming", external:"true", url:"vr_programming.php"},
 {name:"Makey Makey", external:true, url:"makeymakey.php"},
 {name:"Microbit Programming", external:true, url:"microbitprog.php"},
 {name:"Microbit JS", external:true, url:"microbitJs.php"},
 {name:"CyberPi", external:true, url:"cyberpi.php"},
 {name:"mBot Neo", external:true, url:"mbotneo.php"},
 {name:"Tinybit AI Vision", external:true, url:"tinybit_ai.php"},

 //Remaining Editors
 {name:"Scratch Jr", external:true, url:"scratchJr.php"},
 {name:"Scratch", external:true, url:"scratch.php"},
 {name:"Sphero Bolt", external:true, url:"spherobolt.php"},
 {name:"mBot Robot Coding", external:true, url:"mbot.php"},
 {name:"Arduino", external:true, url:"arduino.php"},

];

/* ===============================
   GLOBALS
================================ */
const select = document.getElementById("course");
const saveBtn = document.getElementById("saveBtn");
let editor = null;
let currentCourse = null;

/* ===============================
   POPULATE DROPDOWN
================================ */
courses.forEach((c, i) => {
    const opt = document.createElement("option");
    opt.value = i;
    opt.textContent = c.name;
    select.appendChild(opt);
});

// Force default course
select.value = 0;
currentCourse = courses[0];

/* ===============================
   MONACO INIT
================================ */
require.config({
    paths:{vs:"https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs"}
});

require(["vs/editor/editor.main"], function () {

    editor = monaco.editor.create(document.getElementById("editor"), {
        value: "# Write code here\n",
        language: currentCourse.lang,
        theme: "vs-dark",
        automaticLayout: true
    });

    // Enable save ONLY after editor ready
    saveBtn.disabled = false;

    loadSavedCode();

    select.onchange = function () {
        currentCourse = courses[this.value];

        // External → full page
        if (currentCourse.external) {
            window.location.href = currentCourse.url;
            return;
        }

        // Internal
        monaco.editor.setModelLanguage(editor.getModel(), currentCourse.lang);
        const comment = (currentCourse.lang === "python") ? "# " : "// ";
        editor.setValue(comment + currentCourse.name + "\n");
        document.getElementById("output").srcdoc = "";

        loadSavedCode();
    };
});

/* ===============================
   RUN
================================ */
function runCode() {
    if (!currentCourse || currentCourse.external) return;

    fetch("run.php", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:
            "code=" + encodeURIComponent(editor.getValue()) +
            "&mode=" + encodeURIComponent(currentCourse.mode)
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById("output").srcdoc = html;
    });
}

/* ===============================
   SAVE
================================ */
function saveCode() {

    if (!currentCourse || !currentCourse.name) {
        alert("Course not initialized. Reload page.");
        return;
    }

    // Monaco
    if (!currentCourse.external) {
        fetch("save_code.php", {
            method:"POST",
            headers:{"Content-Type":"application/x-www-form-urlencoded"},
            body:
                "course=" + encodeURIComponent(currentCourse.name) +
                "&type=monaco" +
                "&code=" + encodeURIComponent(editor.getValue())
        })
        .then(res => res.text())
        .then(msg => alert(msg));
        return;
    }

    // External
    const ref = prompt("Paste project URL / ID:");
    if (!ref) return;

    fetch("save_code.php", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:
            "course=" + encodeURIComponent(currentCourse.name) +
            "&type=external" +
            "&ref=" + encodeURIComponent(ref)
    })
    .then(res => res.text())
    .then(msg => alert(msg));
}

/* ===============================
   LOAD
================================ */
function loadSavedCode() {

    if (!currentCourse) return;

    fetch("load_code.php?course=" + encodeURIComponent(currentCourse.name))
    .then(res => res.json())
    .then(data => {
        if (!data) return;

        if (data.editor_type === "monaco" && data.code && editor) {
            editor.setValue(data.code);
        }

        if (data.editor_type === "external" && data.project_ref) {
            console.log("Saved external project:", data.project_ref);
        }
    });
}
</script>
</body>
</html>














