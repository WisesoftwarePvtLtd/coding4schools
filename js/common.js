
function restrictSpace(e) {
    // Block space key
    if (e.type === 'keydown' && e.key === ' ') {
        e.preventDefault();
        return false;
    }

    // Block paste if it contains spaces
    if (e.type === 'paste') {
        const text = (e.clipboardData || window.clipboardData).getData('text');
        if (/\s/.test(text)) {
            e.preventDefault();
            return false;
        }
    }
}

function removeSpecialChars(input) {
    // Allow only A-Z a-z 0-9 and space
    input.value = input.value.replace(/[^a-zA-Z0-9 ]/g, '');

    // Multiple spaces ko single space me convert kare
    input.value = input.value.replace(/\s+/g, ' ');

    // Starting space remove kare
    input.value = input.value.replace(/^\s/, '');
}

function restrictSpecialChars(e) {
    e.preventDefault();
}


function showLoader() {
    document.getElementById("page-loader").style.display = "flex";
}

function hideLoader() {
    document.getElementById("page-loader").style.display = "none";
}


class EditorEngine {

    constructor(config) {

        this.container = config.container;
        this.editorType = config.editorType;
        this.language = config.language;
        this.starterCode = config.starterCode;
        this.exerciseId = config.exerciseId;
        this.role = config.role;

        this.editor = null;

        this.init();
    }

    async init() {

        if (this.editorType === "monaco") {
            await this.loadMonaco();
        }

        if (this.editorType === "p5") {
            await this.loadMonaco();
        }

        if (this.editorType === "codemirror") {
            await this.loadCodeMirror();
        }
    }

    async loadMonaco() {

        await new Promise((resolve) => {

            require.config({
                paths: {
                    vs: "https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs"
                }
            });

            require(["vs/editor/editor.main"], resolve);
        });

        this.editor = monaco.editor.create(
            document.getElementById(this.container),
            {
                value: this.starterCode,
                language: this.language,
                theme: "vs-dark",
                automaticLayout: true
            }
        );
    }

    async loadCodeMirror() {

        // Future extension
    }

    getCode() {
        return this.editor.getValue();
    }

    setCode(code) {
        if (this.editor) {
            this.editor.setValue(code);
        }
    }

    async saveSubmission() {

        await fetch("/api/save_submission.php", {
            method: "POST",
            body: JSON.stringify({
                exerciseId: this.exerciseId,
                code: this.getCode()
            })
        });
    }

    async saveSolution() {

        await fetch("/api/save_solution.php", {
            method: "POST",
            body: JSON.stringify({
                exerciseId: this.exerciseId,
                code: this.getCode()
            })
        });
    }
}

async function loadPy() {

    const btn = document.getElementById("runBtn");
    btn.disabled = true;   // extra safety

    pyodide = await loadPyodide({
        indexURL: "https://cdn.jsdelivr.net/pyodide/v0.25.0/full/"
    });

    await pyodide.loadPackage(["numpy"]);

    pyodideReady = true;

    btn.disabled = false;
    btn.innerText = "Run";
}

// async function runPython() {

//     if (!pyodideReady) {
//         alert("Python loading...");
//         return;
//     }

//     const code = editor.getValue();
//     const outputDiv = document.getElementById("output");

//     // Reset output area
//     outputDiv.innerHTML = "";
//     outputDiv.style.whiteSpace = "pre-wrap";
//     outputDiv.style.color = "lime";
//     outputDiv.style.background = "black";
//     outputDiv.style.padding = "10px";
//     outputDiv.style.height = "100%";
//     outputDiv.style.overflowY = "auto";

//     let outputBuffer = "";

//     try {

//         // STDOUT
//         pyodide.setStdout({
//             batched: (s) => {
//                 outputBuffer += s;
//                 outputDiv.innerHTML = outputBuffer.replace(/\n/g, "<br>");
//                 outputDiv.scrollTop = outputDiv.scrollHeight;
//             }
//         });

//         // STDERR
//         pyodide.setStderr({
//             batched: (s) => {
//                 outputBuffer += s;
//                 outputDiv.innerHTML = outputBuffer.replace(/\n/g, "<br>");
//                 outputDiv.scrollTop = outputDiv.scrollHeight;
//             }
//         });

//         // 🔥 Override Python input to use browser prompt
//         await pyodide.runPythonAsync(`
// import builtins
// from js import prompt

// def custom_input(text=""):
//     value = prompt(text)
//     if value is None:
//         return ""
//     return value

// builtins.input = custom_input
//         `);

//         // Dummy stdin (required but not used)
//         pyodide.setStdin({
//             stdin: () => ""
//         });

//         // Run user code
//         await pyodide.runPythonAsync(code);

//     } catch (err) {
//         outputDiv.style.color = "red";
//         outputDiv.textContent = err;
//     }
// }

async function runPython() {

    if (!pyodideReady) {
        alert("Python loading...");
        return;
    }

    const code = editor.getValue();
    const outputDiv = document.getElementById("output");

    outputDiv.innerHTML = "";
    outputDiv.style.whiteSpace = "pre-wrap";
    outputDiv.style.color = "lime";
    outputDiv.style.background = "black";
    outputDiv.style.padding = "10px";

    try {

        // Python wrapper that captures output
        const wrappedCode = `
import sys, io
from js import prompt

# capture output
_output = io.StringIO()
sys.stdout = _output
sys.stderr = _output

# override input
def input(text=""):
    val = prompt(text)
    if val is None:
        return ""
    return val

# user code
${code}

# return output
_output.getvalue()
        `;

        const result = await pyodide.runPythonAsync(wrappedCode);

        outputDiv.innerHTML = result.replace(/\\n/g, "<br>");

    } catch (err) {

        outputDiv.style.color = "red";
        outputDiv.innerHTML = err.toString().replace(/\\n/g, "<br>");
    }
}


