<?php
session_start();
include 'header.php';
include 'config.php';
$lesson_id = intval($_GET['lesson_id'] ?? 0);

$exercise_id = intval($_GET['exercise_id'] ?? 0);


// fetch exercise
$exercisequery = mysqli_query($conn, "
    SELECT e.exercise_name, e.lesson_id, c.course_name, c.editor_type, c.code, e.editor_id,e.exercise_sort_order,e.exercise_discription,e.sprite_image,e.instruction_guideline,e.user_id
    FROM exercises e
    JOIN course_code c ON c.exercise_id = e.exercise_id
    WHERE e.exercise_id = $exercise_id
");
$exercisedata = mysqli_fetch_assoc($exercisequery);
//If exercise has a link to the code, then the link is stored as a json
$exerciselink = json_decode($exercisedata['code'], true);

$instrquery = mysqli_query(
    $conn,
    "SELECT * FROM exercise_instruction_hints
     WHERE exercise_id = $exercise_id 
     ORDER BY instruction_sort_order ASC"
);


function renderInstruction($code)
{
    $links = [];

    // Step 1: Extract allowed <a> tags
    $code = preg_replace_callback('/<a\s+href="([^"]*)"[^>]*>(.*?)<\/a>/i', function ($matches) use (&$links) {

        $text = trim($matches[2]);

        if (strtolower($text) == 'click here') {
            $placeholder = "###LINK" . count($links) . "###";

            $links[$placeholder] = [
                'href' => $matches[1],
                'text' => $text
            ];

            return $placeholder;
        } else {
            return htmlspecialchars($matches[0]);
        }

    }, $code);

    // Step 2: Escape everything
    $code = htmlspecialchars($code);

    // Step 3: Restore only allowed links
    foreach ($links as $ph => $link) {
        $realLink = '<a href="' . htmlspecialchars($link['href']) . '" target="_blank" >'
            . htmlspecialchars($link['text']) . '</a>';

        $code = str_replace($ph, $realLink, $code);
    }

    return $code;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Exercise</title>
    <style>

    </style>
    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                showMessage("Copied: " + text, "success");
            });
        }
    </script>
    <script>
        function loadAssets(type) {
            fetch("asset_list.php?type=" + type)
                .then(res => res.text())
                .then(html => {
                    document.getElementById(type + "Tab").innerHTML = html;
                });
        }
    </script>
    <script>
        function insertAssetLink(url) {
            const textarea = document.getElementById("instruction_text");

            let text = textarea.value.trim();
            if (text.length > 0 && !text.endsWith(" ")) {
                text += " ";
            }

            // ✅ anchor link insert karo
            text += '<a href="' + url + '" target="_blank">click here</a>';

            textarea.value = text;

            // close asset modal
            const modalEl = document.getElementById("assetModal");
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            // 🔥 FIX scroll
            document.body.classList.remove("modal-open");
            document.body.style.removeProperty("padding-right");
            document.querySelectorAll(".modal-backdrop").forEach(el => el.remove());
        }
    </script>


</head>

<body>

    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">

            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <div class="main-area">
                <!-- GLOBAL MESSAGE -->
                <div id="globalMsg" class="global-msg"></div>

                <div class="card shadow">
                    <div class="card-header" style="background:#1da1f2; color:white;">
                        <h4 class="m-0">Edit Exercise</h4>
                    </div>

                    <div class="card-body">

                        <form id="exerciseForm" onsubmit="return updateExercise();">

                            <input type="hidden" name="exercise_id" id="exercise_id" value="<?= $exercise_id ?>">
                            <input type="hidden" name="user_id" id="user_id" value="<?= $exercisedata['user_id'] ?>">

                            <input type="hidden" name="lesson_id" value="<?= $exercisedata['lesson_id'] ?>">
                            <input type="hidden" name="course" id="courseName"
                                value="<?= $exercisedata['course_name'] ?>">
                            <input type="hidden" name="type" id="editorType"
                                value="<?= $exercisedata['editor_type'] ?>">

                            <div class="mb-3">
                                <label>Exercise Title </label>
                                <input type="text" name="title" class="form-control"
                                    value="<?= htmlspecialchars($exercisedata['exercise_name']) ?>">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Exercise Sort Order <span class="text-danger">*</span></label>
                                <select name="exercise_sort_order" class="form-control">
                                    <?php for ($i = 1; $i <= 50; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($i == $exercisedata['exercise_sort_order']) ? "selected" : "" ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Exercise Description </label>
                                <textarea name="exercise_discription" rows="5"
                                    class="form-control"><?= htmlspecialchars($exercisedata['exercise_discription']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Instruction Guideline</label>
                                <textarea name="instruction_guideline" class="form-control"
                                    rows="3"><?= htmlspecialchars($exercisedata['instruction_guideline']) ?></textarea>
                            </div>

                            <!-- Sprite Image -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sprite Image</label>

                                <input type="file" name="sprite_image" id="sprite_image" hidden
                                    onchange="previewImage(this,'spritePreview')">

                                <div class="image-upload-box"
                                    onclick="document.getElementById('sprite_image').click();">
                                    <img id="spritePreview" src="<?= !empty($exercisedata['sprite_image'])
                                        ? 'uploads/' . $exercisedata['sprite_image']
                                        : 'images/systemimages/placeholder-image.png' ?>">

                                    <span class="remove-image"
                                        onclick="event.stopPropagation(); removeSpriteImage(); deleteSpriteImage();">
                                        <i class="fas fa-times"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Select Editor </label>
                                <select name="editor_id" id="editorSelect" class="form-control"
                                    onchange="setEditorName(this); loadEditor(this);">
                                    <?php
                                    $res = mysqli_query($conn, "SELECT * FROM editors");
                                    while ($ed = mysqli_fetch_assoc($res)) {
                                        $sel = $ed['editor_id'] == $exercisedata['editor_id'] ? "selected" : "";
                                        echo "<option value='{$ed['editor_id']}' data-url='{$ed['editor_url']}?editor_id={$ed['editor_id']}' data-type='{$ed['editor_type']}' data-save='{$ed['editor_save_code']}' $sel> {$ed['editor_name']} </option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="editor-wrapper mt-3">
                                <?php if ($exercisedata['editor_type'] === "Scratch" || $exercisedata['editor_type'] === "Makey Makey") {
                                    ?>
                                    <?php
                                    $projectUrl = BASE_URL . "" . $exercisedata['code'];
                                    // echo $encodedUrl = rawurlencode($projectUrl); ?>
                                    <iframe id="scratchFrame"
                                        src="<?= SCRATCHWITHMAKEYMAKEYBUILDPATH ?>?project_url=<?= $projectUrl ?>"
                                        width="100%" height="800" style="border:none;"></iframe>
                                    <!-- <iframe id="scratchFrame"
                                        src="scratch-editor/packages/scratch-gui/build/index.html?project_url=<?= $encodedUrl ?>" width="100%"
                                        height="800" style="border:none;"></iframe> -->
                                    <!-- <iframe id="scratchFrame"
                                        src="makeymakey/build/index.html?project_url=<?= $encodedUrl ?>" width="100%"
                                        height="800" style="border:none;"></iframe> -->
                                <?php } else { ?>
                                    <iframe id="editorFrame"
                                        style="width:100%; height:1200px; border:1px solid #ccc;overflow:hidden;"
                                        scrolling="no"></iframe>
                                <?php } ?>
                            </div>

                            <div id="trinketBox" style="display:none; margin-top:10px;">

                                <div class="mb-2 p-2"
                                    style="background:#f8f9fa; border:1px solid #ddd; border-radius:6px;">
                                    <strong>Instructions:</strong><br>
                                    1. Open Trinket editor<br>
                                    2. Write your Python code<br>
                                    3. Click on SHARE button<br>
                                    4. Copy the link<br>
                                    5. Paste the link below
                                </div>
                    <?php
                            
                            if (isset($exerciselink['arduino']) && !empty($exerciselink['arduino'])) {
                                $url = $exerciselink['arduino'];
                            }
                    ?>
                                <input type="text" id="trinketLink" placeholder="Paste your Trinket link here"
                                    class="form-control" value="<?php echo htmlspecialchars($url) ?>">
                            </div>

                            <div id="arduinoBox" style="display:none; margin-top:10px;">

                                <div class="mb-2 p-2"
                                    style="background:#f8f9fa; border:1px solid #ddd; border-radius:6px;">
                                    <strong>Instructions:</strong><br>
                                    1. Open Arduino editor<br>
                                    2. Login and click <strong>Create Project</strong>, then open
                                    <strong>Sketch</strong><br>
                                    3. Write your code<br>
                                    4. Click on SHARE<br>
                                    5. Copy the project link<br>
                                    6. Paste the link below<br>
                                    7. Remove <code>?view-mode=preview</code> from the copied link

                                </div>

                                <input type="text" id="arduinoLink" placeholder="Paste Arduino project link here"
                                    class="form-control">
                            </div>

                            <textarea name="answer" id="editorAnswer"
                                hidden><?= htmlspecialchars($exercisedata['code']) ?></textarea>

                            <br>

                            <button type="button"
                                onclick="window.location.href='manage_exercise.php?lesson_id=<?= $lesson_id ?>';"
                                class="btn btn-primary px-4">
                                Back To Exercise
                            </button>
                            <button type="submit" class="btn btn-primary">Update Exercise</button>
                            <button type="button" id="addInstructionBtn" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#instructionModal">
                                Add Instruction
                            </button>
                            <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal"
                                data-bs-target="#assetModal" onclick="loadAssets('image')">
                                Import Asset
                            </button>

                        </form>


                        <h4 id="instructionHeading" class="mt-4 mb-3"
                            style="<?= mysqli_num_rows($instrquery) ? '' : 'display:none;' ?>">
                            Instructions List
                        </h4>

                        <div id="instructionList" class="mb-3">

                            <?php while ($instr = mysqli_fetch_assoc($instrquery)) { ?>
                                <div class="mb-3" id="instr-<?= $instr['id'] ?>">
                                    <div class="grade-box">
                                        <div class="grade-row">
                                            <div style="max-width: 92%;">

                                                <strong><?= renderInstruction($instr['instruction_text']) ?><strong>
                                            </div>
                                            <div class="action-icons">
                                                <i class="fas fa-edit text-primary" title="Edit Instruction"
                                                    onclick="editInstruction(<?= $instr['id'] ?>)">
                                                </i>

                                                <i class="fas fa-trash text-danger" title="Delete Instruction"
                                                    onclick="openDeleteModal('instruction_delete.php?id=<?= $instr['id']; ?>&exercise_id=<?= $exercise_id ?>&lesson_id=<?= $lesson_id ?>', 'Are you sure you want to delete this instruction?')"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>

                        <div class="modal fade text-dark" id="instructionModal" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header bg-primary">
                                        <h3 class="grade-modal-title text-white">Add Instruction</h3>
                                        <button type="button" data-bs-dismiss="modal" class="closeicon"
                                            onclick="resetInstructionModal()">
                                            <i class="fas fa-times fs-4"></i>
                                        </button>
                                    </div>

                                    <div class="modal-body">

                                        <form onsubmit="return updateInstruction();">
                                            <input type="hidden" id="instruction_id">
                                            <input type="hidden" id="modal_exercise_id" value="<?= $exercise_id ?>">



                                            <!-- Instruction Text -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Instruction Text <span
                                                        onclick="openAssetFromInstruction()"
                                                        style=" position:absolute; left: 177px; cursor:pointer; color:#0d6efd;"
                                                        title="Add Asset"><i
                                                            class="fas fa-paperclip"></i></span></label>
                                                <textarea id="instruction_text" class="form-control"
                                                    rows="3"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="fw-bold">Instruction Sort Order <span
                                                        class="text-danger">*</span></label>
                                                <select name="instruction_sort_order" id="instruction_sort_order"
                                                    class="form-control" required>

                                                    <?php for ($i = 1; $i <= 50; $i++): ?>
                                                        <option value="<?= $i ?>">
                                                            <?= $i ?>
                                                        </option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                            <!-- Instruction Image -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Instruction Image</label>

                                                <input type="file" id="instructionImage" hidden
                                                    onchange="previewImage(this,'instructionPreview')">

                                                <div class="image-upload-box"
                                                    onclick="document.getElementById('instructionImage').click();">
                                                    <img id="instructionPreview"
                                                        src="images/systemimages/placeholder-image.png">

                                                    <span class="remove-image"
                                                        onclick="removePreviewImage('instructionPreview','instructionImage', event); deleteInstructionImage('instruction')">
                                                        <i class="fas fa-times"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Hint Text -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Hint Text </label>
                                                <textarea name="hint_text" class="form-control" rows="3"></textarea>
                                            </div>

                                            <!-- Hint Image -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Hint Image</label>

                                                <input type="file" id="hintImage" hidden
                                                    onchange="previewImage(this,'hintPreview')">

                                                <div class="image-upload-box"
                                                    onclick="document.getElementById('hintImage').click();">
                                                    <img id="hintPreview"
                                                        src="images/systemimages/placeholder-image.png">
                                                    <span class="remove-image"
                                                        onclick="removePreviewImage('hintPreview','hintImage', event); deleteInstructionImage('hint')">
                                                        <i class="fas fa-times"></i>
                                                    </span>
                                                </div>
                                            </div>



                                            <div class="text-end">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                                    onclick="resetInstructionModal()">
                                                    Cancel
                                                </button>

                                                <button type="submit" class="btn btn-primary">
                                                    Save Instruction
                                                </button>
                                            </div>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

        function removeSpriteImage() {
            // Reset preview and input
            const preview = document.getElementById("spritePreview");
            preview.src = "images/systemimages/placeholder-image.png";

            const input = document.getElementById("sprite_image");
            input.value = "";
        }

        function deleteSpriteImage() {
            const lessonId = <?= $lesson_id ?>;

            fetch(`sprite_image_delete.php?lesson_id=${lessonId}`)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        showMessage("Sprite Image deleted successfully!", "success");
                    } else {
                        showMessage("Failed to delete sprite image", "danger");
                    }
                })
                .catch(err => {
                    console.error(err);
                    showMessage("Error deleting sprite image", "danger");
                });
        }



        function removePreviewImage(previewId, inputId, e) {
            e.stopPropagation();
            document.getElementById(previewId).src = "images/systemimages/placeholder-image.png";
            document.getElementById(inputId).value = "";
        }

        function deleteInstructionImage(type) {
            const id = document.getElementById("instruction_id").value;
            if (!id) return;

            fetch(`instruction_delete_image.php?id=${id}&type=${type}`)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        if (type === 'instruction') document.getElementById("instructionPreview").src = "images/systemimages/placeholder-image.png";
                        if (type === 'hint') document.getElementById("hintPreview").src = "images/systemimages/placeholder-image.png";
                        showMessage("Image deleted successfully!", "success");
                    } else {
                        showMessage("Failed to delete image", "danger");
                    }
                });
        }


        function openAssetFromInstruction() {
            loadAssets('image');
            const assetModal = new bootstrap.Modal(
                document.getElementById("assetModal"),
                { backdrop: true, focus: true }
            );
            assetModal.show();
        }
    </script>
    <style>
        .modal-dialog-scrollable .modal-body {
            overflow-y: auto !important;
            max-height: calc(100vh - 200px);
        }
    </style>

    <script>

        loadEditor(document.getElementById("editorSelect"));

        let isFirstLoad = true;   // 👈 YEH ADD KARO

        // function setEditorName(sel) {
        //     const text = sel.options[sel.selectedIndex].text;
        //     document.getElementById("courseName").value = text;
        //     document.getElementById("editorType").value = text;
        // }

        function setEditorName(sel) {

            const selectedOption = sel.options[sel.selectedIndex];
            const type = selectedOption.getAttribute("data-type");

            const text = selectedOption.text;

            document.getElementById("courseName").value = text;
            document.getElementById("editorType").value = type;

            // ✅ TRINKET SHOW / HIDE
            if (type === "trinket") {
                document.getElementById("trinketBox").style.display = "block";
            } else {
                document.getElementById("trinketBox").style.display = "none";
            }
            // ✅ ARDUINO
            document.getElementById("arduinoBox").style.display =
                (type === "arduinogr12") ? "block" : "none";
        }

        var currentSaveCode = "0";

        function loadEditor(sel) {

            let selectedOption = sel.options[sel.selectedIndex];
            if (!selectedOption || !sel.value) return;

            let url = selectedOption.getAttribute("data-url");
            let type = selectedOption.getAttribute("data-type");
            let save = selectedOption.getAttribute("data-save");

            currentSaveCode = save;


            // const iframe = document.getElementById("editorFrame");

            let iframe;

            if (type === "Scratch" || type === "Makey Makey") {
                iframe = document.getElementById("scratchFrame");
            } else {
                iframe = document.getElementById("editorFrame");
            }

            const box = document.querySelector(".editor-wrapper");

            // ✅ TRINKET FIX
            // 🔥 RESET OLD EDITOR
            // RESET
            iframe.src = "";

            // ✅ TRINKET
            if (type === "trinket") {

                box.style.display = "block";
                document.getElementById("trinketBox").style.display = "block";

                const answerEl = document.getElementById("editorAnswer");
                const inputLink = document.getElementById("trinketLink");

                let loaded = false;

                // 🔹 Case 1: Already saved trinket link
                if (answerEl && answerEl.value) {
                    try {
                        let decoded = decodeURIComponent(answerEl.value);
                        let parsed = JSON.parse(decoded);

                        if (parsed.trinket) {
                            let embedLink = parsed.trinket;

                            if (!embedLink.includes("/embed/")) {
                                embedLink = embedLink.replace("/python3/", "/embed/python3/");
                            }

                            iframe.src = embedLink;
                            loaded = true;
                        }

                    } catch (e) {
                        console.log("No trinket data");
                    }
                }

                // 🔹 Case 2: No saved link → wait for user input
                if (!loaded) {
                    iframe.src = "https://trinket.io/embed/python3"; // blank
                    console.log("⚠️ No trinket link found, waiting for user input");
                }

                return;
            }

            document.getElementById("trinketBox").style.display = "none";

            // if (type === "arduinogr12") {

            //     box.style.display = "block"; // ✅ show iframe
            //     document.getElementById("arduinoBox").style.display = "block";

            //     const answerEl = document.getElementById("editorAnswer");
            //     const inputLink = document.getElementById("arduinoLink");
            //     const iframe = document.getElementById("editorFrame");

            //     if (answerEl && answerEl.value) {
            //         try {
            //             let decoded = decodeURIComponent(answerEl.value);
            //             let parsed = JSON.parse(decoded);

            //             if (parsed.arduino) {
            //                 inputLink.value = parsed.arduino;

            //                 // ✅ iframe preview
            //                 iframe.src = parsed.arduino;
            //             }

            //         } catch (e) {
            //             console.log("No Arduino data");
            //         }
            //     }

            //     return;
            // }


            if (type === "arduinogr12") {

                box.style.display = "block";
                document.getElementById("arduinoBox").style.display = "block";

                const answerEl = document.getElementById("editorAnswer");
                const inputLink = document.getElementById("arduinoLink");
                const iframe = document.getElementById("editorFrame");

                let hasArduinoData = false;

                if (answerEl && answerEl.value) {
                    try {
                        let decoded = decodeURIComponent(answerEl.value);
                        let parsed = JSON.parse(decoded);

                        if (parsed.arduino) {
                            hasArduinoData = true;

                            inputLink.value = parsed.arduino;
                            iframe.src = parsed.arduino;
                        }

                    } catch (e) {
                        console.log("No Arduino data");
                    }
                }

                // ✅ अगर data nahi hai → ADD wala popup flow chalao
                if (!hasArduinoData) {

                    document.querySelector(".editor-wrapper").style.display = "none";

                    alert(
                        "Steps:\n\n" +
                        "1. The Arduino editor will open\n" +
                        "2. Log in to your account\n" +
                        "3. Close the window after finishing\n\n"
                    );

                    const width = 1100;
                    const height = 700;

                    const left = (window.screen.width / 2) - (width / 2);
                    const top = (window.screen.height / 2) - (height / 2);

                    const arduinoPopup = window.open(
                        "https://app.arduino.cc/",
                        "ArduinoLogin",
                        `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`
                    );

                    const timer = setInterval(() => {

                        if (arduinoPopup.closed) {
                            clearInterval(timer);

                            document.querySelector(".editor-wrapper").style.display = "block";
                            let sel = document.getElementById("editorSelect");
                            let selectedOption = sel.options[sel.selectedIndex];

                            let url = selectedOption.getAttribute("data-url");

                            document.getElementById("editorFrame").src = url;
                            arduinoBox.style.display = "block";

                        }

                    }, 1000);
                }

                return;
            }

            // ❌ If save_code = 0 → hide editor
            if (currentSaveCode === "0") {
                iframe.src = "";
                box.style.display = "none";
                return;
            }

            // ✅ Show editor
            box.style.display = "block";

            iframe.onload = function () {

                if (!isFirstLoad) return;
                isFirstLoad = false;

                const answerEl = document.getElementById("editorAnswer");
                if (!answerEl || !answerEl.value) return;

                let value = answerEl.value.trim();

                console.log("VALUE:", value); // 🔍 debug

                // 🔥 AUTO DETECT SCRATCH (file path)
                if (value.endsWith(".sb3")) {

                    const wait = setInterval(() => {

                        if (iframe.contentWindow &&
                            typeof iframe.contentWindow.setEditorCode === "function") {

                            iframe.contentWindow.setEditorCode({
                                code: value
                            });

                            clearInterval(wait);
                            console.log("Scratch file loaded");
                        }

                    }, 200);

                    return;
                }

                // 🔥 NORMAL EDITOR (JSON)
                let parsed;

                try {
                    parsed = JSON.parse(value);
                } catch (e) {
                    console.error("Invalid JSON", e);
                    showMessage("Editor answer is invalid", "danger");
                    return;
                }

                const wait = setInterval(() => {

                    if (iframe.contentWindow &&
                        typeof iframe.contentWindow.setEditorCode === "function") {

                        iframe.contentWindow.setEditorCode(parsed);
                        clearInterval(wait);
                    }

                }, 200);
            };


            // ✅ Monaco case
            if (type === "monaco") {
                iframe.src = "editors/editor.php?editor_id=" + sel.value;
            }
            // ✅ Normal URL
            else if (url) {
                iframe.src = url;
            }
        }

        function updateExercise() {

            // ✅ ARDUINO
            let arduinoBox = document.getElementById("arduinoBox");
            let arduinoLink = document.getElementById("arduinoLink")?.value.trim();

            if (arduinoBox && arduinoBox.style.display === "block") {

                if (!arduinoLink) {
                    showMessage("Please paste Arduino link", "error");
                    return false;
                }

                document.getElementById("editorAnswer").value =
                    encodeURIComponent(JSON.stringify({ arduino: arduinoLink }));

                submitExerciseForm();
                return false;
            }

            let link = document.getElementById("trinketLink")?.value.trim();

            if (link) {
                let embedLink = link.replace("/python3/", "/embed/python3/");

                document.getElementById("editorAnswer").value =
                    encodeURIComponent(JSON.stringify({ trinket: embedLink }));

                submitExerciseForm();
                return false;
            }

            try {

                let sel = document.getElementById("editorSelect");
                let selectedOption = sel.options[sel.selectedIndex];

                if (!selectedOption || !sel.value) return false;

                currentSaveCode = selectedOption.getAttribute("data-save");
                let type = document.getElementById("editorType").value;

                // const iframe = document.getElementById("editorFrame");
                let iframe;

                if (type === "Scratch" || type === "Makey Makey") {
                    iframe = document.getElementById("scratchFrame");
                } else {
                    iframe = document.getElementById("editorFrame");
                }

                if (currentSaveCode === "1") {
                    if (type != "Scratch" && type != "Makey Makey") {
                        if (!iframe.contentWindow || !iframe.contentWindow.getEditorCode) {
                            showMessage("Editor not loaded properly", "danger");
                            return false;
                        }
                    }


                    // if (type === "Scratch" || type === "Makey Makey") {

                    //     const trySave = () => {

                    //         const win = iframe.contentWindow;

                    //         if (!win || !win.vm) {
                    //             console.log("⏳ Waiting VM...");
                    //             setTimeout(trySave, 500);
                    //             return;
                    //         }

                    //         win.vm.saveProjectSb3().then(function (blob) {

                    //             const reader = new FileReader();

                    //             reader.onload = function () {

                    //                 const base64data = reader.result;

                    //                 if (!base64data) {
                    //                     showMessage("Scratch project not ready", "danger");
                    //                     return;
                    //                 }

                    //                 // ✅ ONLY THIS (final data)
                    //                 document.getElementById("editorAnswer").value =
                    //                     encodeURIComponent(JSON.stringify({
                    //                         code: base64data
                    //                     }));

                    //                 submitExerciseForm();
                    //             };

                    //             reader.readAsDataURL(blob);

                    //         });

                    //     };

                    //     trySave();
                    //     return false;
                    // }
                    // // 🔥 NORMAL EDITORS
                    // else {

                    //     const updatecode = iframe.contentWindow.getEditorCode();

                    //     document.getElementById("editorAnswer").value =
                    //         JSON.stringify(updatecode);
                    // }

                    if (type === "Scratch" || type === "Makey Makey") {

                        const iframeWin = iframe.contentWindow;

                        // STEP 1: parent asks iframe to save project
                        const requestSave = () => {

                            console.log("📩 Requesting Scratch project...");

                            iframeWin.postMessage({
                                type: "GET_SCRATCH_CODE"
                            }, "*");
                        };

                        // STEP 2: receive response from iframe
                        const messageHandler = function (event) {

                            if (event.data && event.data.type === "SCRATCH_CODE") {

                                console.log("📦 Scratch code received");

                                window.removeEventListener("message", messageHandler);

                                const base64data = event.data.code;

                                if (!base64data) {
                                    showMessage("Scratch project not ready", "danger");
                                    return;
                                }

                                document.getElementById("editorAnswer").value =
                                    encodeURIComponent(JSON.stringify({
                                        code: base64data
                                    }));

                                submitExerciseForm();
                            }
                        };

                        window.addEventListener("message", messageHandler);

                        // trigger save request
                        requestSave();

                        return false;
                    }

                    // 🔥 NORMAL EDITORS
                    else {

                        const updatecode = iframe.contentWindow.getEditorCode();

                        document.getElementById("editorAnswer").value =
                            JSON.stringify(updatecode);
                    }
                }

            } catch (e) {
                console.error(e);
                showMessage("Editor error occurred", "danger");
                return false;
            }

            // ✅ normal submit (non-scratch)
            submitExerciseForm();

            return false;
        }

        function submitExerciseForm() {

            const data = new FormData(document.getElementById("exerciseForm"));

            fetch("save_exercise.php", {
                method: "POST",
                body: data
            })
                .then(r => r.json())
                .then(res => {

                    if (res.status === "success") {
                        showMessage("Exercise Updated Successfully!", "success");
                    }
                    else if (res.status === "duplicate") {
                        showMessage(res.message, "error");
                    }
                    else if (res.status === "duplicate_sort_order") {
                        showMessage(res.message, "error");
                    }
                    else {
                        showMessage(res.message || "Error occurred", "error");
                    }

                })
                .catch(err => {
                    console.error(err);
                    showMessage("Server error", "error");
                });
        }


        function openAddInstruction() {
            resetInstructionModal(); // 🔥 clear edit state
        }


        function updateInstruction() {

            const exerciseId = document.getElementById("modal_exercise_id").value;
            const instructionId = document.getElementById("instruction_id").value;
            const instructionText = document.getElementById("instruction_text").value.trim();
            const hintTextEl = document.querySelector("textarea[name='hint_text']");
            const hintText = hintTextEl ? hintTextEl.value.trim() : "";
            const sortOrder = document.getElementById("instruction_sort_order").value;


            if (!instructionText) {
                showMessage("Instruction text is required", "danger");
                return false;
            }

            let data = new FormData();
            data.append("exercise_id", exerciseId);
            data.append("instruction_id", instructionId); // 👈 KEY
            data.append("instruction_text", instructionText);
            data.append("hint_text", hintText);
            data.append("instruction_sort_order", sortOrder);

            const instructionImage = document.getElementById("instructionImage")?.files[0];
            if (instructionImage) data.append("instruction_image", instructionImage);

            const hintImage = document.getElementById("hintImage")?.files[0];
            if (hintImage) data.append("hint_image", hintImage);



            fetch("instruction_insert.php", {
                method: "POST",
                body: data
            })
                .then(r => r.json())
                .then(res => {

                    if (res.status !== "success") {
                        showMessage(res.message || "Failed", "error");
                        return;
                    }

                    if (res.status === "duplicate_sort_order") {
                        showMessage(res.message, "error");
                        return;
                    }

                    const instr = res.instruction;
                    const list = document.getElementById("instructionList");

                    const html = `
                            <div class="mb-3" id="instr-${instr.id}">
                                <div class="grade-box">
                                    <div class="grade-row">
                                        <div style="max-width: 92%;"><strong>${instr.instruction_text}</strong></div>
                                        <div class="action-icons">
                                            <i class="fas fa-edit text-primary"
                                            onclick="editInstruction(${instr.id})"></i>
                                        
                                            <i class="fas fa-trash text-danger" title="Delete Instruction" onclick="openDeleteModal('instruction_delete.php?id=${instr.id}&exercise_id=<?= $exercise_id ?>&lesson_id=<?= $lesson_id ?>', 'Are you sure you want to delete this instruction?')"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                    // UPDATE existing
                    if (instructionId) {
                        document.getElementById("instr-" + instr.id).outerHTML = html;
                        showMessage("Instruction Updated Successfully!", "success");
                    }
                    // INSERT new
                    else {
                        list.insertAdjacentHTML("beforeend", html);
                        document.getElementById("instructionHeading").style.display = "block";
                        showMessage("Instruction Added Successfully!", "success");
                    }

                    // reset modal
                    document.getElementById("instruction_id").value = "";
                    document.getElementById("instruction_text").value = "";
                    if (hintTextEl) hintTextEl.value = "";

                    document.getElementById("instructionPreview").src =
                        "images/systemimages/placeholder-image.png";
                    document.getElementById("hintPreview").src =
                        "images/systemimages/placeholder-image.png";


                    bootstrap.Modal.getInstance(
                        document.getElementById("instructionModal")
                    ).hide();
                });

            return false;
        }

        function editInstruction(id) {

            fetch("instruction_fetch.php?id=" + id)
                .then(res => res.json())
                .then(data => {

                    if (data.status !== "success") {
                        showMessage("Failed to load instruction", "danger");
                        return;
                    }

                    const instr = data.instruction;

                    // fill modal fields
                    document.getElementById("instruction_id").value = instr.id;
                    document.getElementById("instruction_text").value = instr.instruction_text;
                    document.getElementById("instruction_sort_order").value = instr.instruction_sort_order ?? "";

                    const hintEl = document.querySelector("textarea[name='hint_text']");
                    if (hintEl) hintEl.value = instr.hint_text ?? "";

                    // preview images
                    document.getElementById("instructionPreview").src =
                        instr.instruction_image
                            ? "uploads/" + instr.instruction_image
                            : "images/systemimages/placeholder-image.png";

                    document.getElementById("hintPreview").src =
                        instr.hint_image
                            ? "uploads/" + instr.hint_image
                            : "images/systemimages/placeholder-image.png";



                    // change modal title
                    document.querySelector(".grade-modal-title").innerText = "Edit Instruction";

                    // open modal
                    new bootstrap.Modal(
                        document.getElementById("instructionModal")
                    ).show();
                });
        }

        function resetInstructionModal() {

            document.getElementById("instruction_id").value = "";
            document.getElementById("instruction_text").value = "";
            document.getElementById("instruction_sort_order").value = "";

            const hintEl = document.querySelector("textarea[name='hint_text']");
            if (hintEl) hintEl.value = "";

            document.getElementById("instructionPreview").src =
                "images/systemimages/placeholder-image.png";

            document.getElementById("hintPreview").src =
                "images/systemimages/placeholder-image.png";

            // ✅ IMPORTANT FIX
            document.getElementById("instructionImage").value = "";
            document.getElementById("hintImage").value = "";



            document.querySelector(".grade-modal-title").innerText = "Add Instruction";
        }




    </script>
    <script>
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById(previewId).src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        const msg = <?= json_encode($_SESSION['msg'] ?? '') ?>;
        const type = <?= json_encode($_SESSION['transaction_status'] ?? 'success') ?>;

        if (msg && msg.trim() !== '') {
            showMessage(msg, type);
        }
    </script>
    <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>

    <div class="modal fade" id="assetModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Import Asset</h5>
                    <button type="button" data-bs-dismiss="modal" class="closeicon">
                        <i class="fas fa-times fs-4"></i>
                    </button>
                </div>

                <div class="modal-body">

                    <!-- TABS -->
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#imageTab"
                                onclick="loadAssets('image')">
                                Images
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#videoTab"
                                onclick="loadAssets('video')">
                                Videos
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#codeTab"
                                onclick="loadAssets('code')">
                                File
                            </button>
                        </li>
                    </ul>


                    <!-- TAB CONTENT -->
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="imageTab"></div>
                        <div class="tab-pane fade" id="videoTab"></div>
                        <div class="tab-pane fade" id="codeTab"></div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</body>

</html>
