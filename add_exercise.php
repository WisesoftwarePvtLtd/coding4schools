<?php
session_start();
include 'header.php';
include 'config.php';
$user_id = $_SESSION['LoggedInUserId'] ?? 0;
$lesson_id = intval($_GET['lesson_id'] ?? 0);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Exercise</title>

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
                        <h4 class="m-0">Add Exercise</h4>
                    </div>

                    <div class="card-body">

                        <!-- ================= EXERCISE FORM ================= -->
                        <form id="exerciseForm" onsubmit="return saveExercise();">

                            <input type="hidden" name="user_id" value="<?= $user_id ?>">
                            <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">

                            <input type="hidden" name="course" id="courseName">
                            <input type="hidden" name="type" id="editorType">

                            <div class="mb-3">
                                <label>Exercise Title </label>
                                <input type="text" name="title" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Exercise Sort Order <span class="text-danger">*</span></label>
                                <select name="exercise_sort_order" id="exercise_sort_order" class="form-control"
                                    required>

                                    <?php for ($i = 1; $i <= 50; $i++): ?>
                                        <option value="<?= $i ?>">
                                            <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Exercise Description </label>
                                <textarea name="exercise_discription" rows="5" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Instruction Guideline</label>
                                <textarea name="instruction_guideline" class="form-control" rows="3"></textarea>
                            </div>

                            <!-- Sprite Image -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Sprite Image</label>

                                <input type="file" name="sprite_image" id="sprite_image" hidden
                                    onchange="previewImage(this,'spritePreview')">

                                <div class="image-upload-box  sprite-image-box"
                                    onclick="document.getElementById('sprite_image').click();">
                                    <img id="spritePreview" src="images/systemimages/placeholder-image.png">

                                    <span class="remove-image"
                                        onclick="event.stopPropagation(); removeSpriteImage(); deleteSpriteImage();">
                                        <i class="fas fa-times"></i>
                                    </span>
                                </div>
                            </div>


                            <div class="mb-3">
                                <label>Select Editor <span class="text-danger">*</span></label>
                                <select name="editor_id" id="editorSelect" class="form-control"
                                    onchange="setEditorName(this); loadEditor(this);">
                                    <!-- <option value="">Select Editor</option> -->
                                    <?php
                                    $res = mysqli_query($conn, "SELECT editor_id, editor_name, editor_url, editor_type, editor_save_code  FROM editors");
                                    while ($ed = mysqli_fetch_assoc($res)) {

                                        echo "<option  value='{$ed['editor_id']}' data-url='{$ed['editor_url']}?editor_id={$ed['editor_id']}' data-type='{$ed['editor_type']}' data-save='{$ed['editor_save_code']}'> {$ed['editor_name']}</option>";
                                    }

                                    ?>
                                </select>
                            </div>

                            <div class="editor-wrapper mt-3" style="display:none;">
                                <iframe id="editorFrame"
                                    style="width:100%; height:1200px; border:1px solid #ccc;overflow:hidden;"
                                    scrolling="no"></iframe>
                            </div>

                            <div id="trinketBox" style="display:none; margin-top:10px;">

                                <!-- ✅ GUIDELINE ABOVE -->
                                <div class="mb-2 p-2"
                                    style="background:#f8f9fa; border:1px solid #ddd; border-radius:6px;">
                                    <strong>Instructions:</strong><br>
                                    1. Open Trinket editor<br>
                                    2. Write your Python code<br>
                                    3. Click on SHARE button<br>
                                    4. Copy the link<br>
                                    5. Paste the link below
                                </div>

                                <!-- ✅ INPUT BOX -->
                                <input type="text" id="trinketLink" placeholder="Paste your Trinket link here"
                                    class="form-control">

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

                                <input type="text" id="arduinoLink" placeholder="Paste Arduino link here"
                                    class="form-control">
                            </div>

                            <textarea name="answer" id="editorAnswer" hidden></textarea>

                            <br>
                            <button type="button"
                                onclick="window.location.href='manage_exercise.php?lesson_id=<?= $lesson_id ?>';"
                                class="btn btn-primary px-4">
                                Back To Exercise
                            </button>
                            <button type="submit" class="btn btn-primary">Save Exercise</button>

                            <button type="button" id="addInstructionBtn" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#instructionModal" style="display:none;" onclick="openAddInstruction()">
                                Add Instruction
                            </button>
                            <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal"
                                data-bs-target="#assetModal">
                                Import Asset
                            </button>


                        </form>

                        <div id="exerciseMsg" class="mt-3"></div>

                        <!-- ================= INSTRUCTION SECTION ================= -->
                        <h4 id="instructionHeading" class="mt-4 mb-3" style="display:none;">
                            Instructions List
                        </h4>
                        <div id="instructionList" class="mb-3"></div>


                        <!-- Instruction Modal -->
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

                                        <form onsubmit="return saveInstruction();">
                                            <input type="hidden" id="exercise_id">
                                            <input type="hidden" id="instruction_id" value="">


                                            <!-- Instruction Text -->
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Instruction Text <span
                                                        onclick="openAssetFromInstruction()"
                                                        style=" position:absolute; left: 177px; cursor:pointer; color:#0d6efd;"
                                                        title="Add Asset"><i class="fas fa-paperclip"></i></span>
                                                </label>
                                                <textarea id="instruction_text" class="form-control"
                                                    rows="3"></textarea>

                                            </div>

                                            <div class="mb-3">
                                                <label class="fw-bold">Instruction Sort Order <span
                                                        class="text-danger">*</span></label>
                                                <select name="instruction_sort_order" id="instruction_sort_order"
                                                    class="form-control" required>
                                                    <!-- <option value="">Select Instruction Order</option> -->
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

                                                <button type="submit" class="btn btn-primary">Save Instruction
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

        <script>
            function disableExerciseForm() {

                // form ke saare inputs disable
                const form = document.getElementById("exerciseForm");

                form.querySelectorAll("input, textarea, select").forEach(el => {

                    // ❌ in buttons ko disable mat karo
                    if (
                        el.id === "addInstructionBtn" ||
                        el.innerText === "Add Instruction"
                    ) {
                        return;
                    }

                    el.disabled = true;
                });

                // 🔥 Special: file upload box click bhi disable karo
                document.querySelectorAll(".sprite-image-box").forEach(box => {
                    box.style.pointerEvents = "none";
                    box.style.opacity = "0.6";
                });

            }
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
            function setEditorName(sel) {
                let text = sel.options[sel.selectedIndex].text;
                document.getElementById("courseName").value = text;
                document.getElementById("editorType").value = text;
            }
            let currentSaveCode = "0";

            function loadEditor(sel) {

                let selectedOption = sel.options[sel.selectedIndex];

                let url = selectedOption.getAttribute("data-url");
                let type = selectedOption.getAttribute("data-type");

                let iframe = document.getElementById("editorFrame");
                let box = document.querySelector(".editor-wrapper");

                // 🔥 ADD THIS RESET BLOCK
                document.getElementById("arduinoBox").style.display = "none";
                document.getElementById("trinketBox").style.display = "none";

                console.log("Editor Type:", type);
                let save = selectedOption.getAttribute("data-save");

                currentSaveCode = save;   // ✅ store globally
                console.log("Save Code:", save);

                if (type === "arduinogr12") {

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

                            // ✅ NOW OPEN EDITOR AFTER LOGIN
                            document.querySelector(".editor-wrapper").style.display = "block";

                            let sel = document.getElementById("editorSelect");
                            let selectedOption = sel.options[sel.selectedIndex];

                            let url = selectedOption.getAttribute("data-url");

                            document.getElementById("editorFrame").src = url;
                            arduinoBox.style.display = "block";

                        }

                    }, 1000);

                    return;
                }


                // ❌ If save_code = 0 → Do NOT open iframe
                if (save === "0") {
                    iframe.src = "";
                    box.style.display = "none";
                    return;
                }

                if (type === "trinket") {
                    console.log("Trinket Editor detected");
                    box.style.display = "block";
                    iframe.src = url;
                    trinketBox.style.display = "block"; // 👈 SHOW INPUT
                    return;
                }

                // ✅ If Monaco → open fixed monaco page
                if (type === "monaco") {

                    iframe.src = "editors/editor.php?editor_id=" + sel.value;
                    box.style.display = "block";
                    return;
                }

                // ✅ Otherwise open from URL
                if (url) {
                    iframe.src = url;
                    box.style.display = "block";
                }
            }


            function openAddInstruction() {
                resetInstructionModal(); // 🔥 clear edit state
            }

            // function saveExercise() {

            //     try {
            //         if (currentSaveCode === "1") {
            //             const iframe = document.getElementById("editorFrame");

            //             if (!iframe.contentWindow || !iframe.contentWindow.getEditorCode) {
            //                 showMessage("Editor not loaded properly", "danger");
            //                 return false;
            //             }

            //             const code = iframe.contentWindow.getEditorCode();

            //             document.getElementById("editorAnswer").value =
            //                 encodeURIComponent(JSON.stringify(code));
            //         }

            //     } catch (e) {
            //         console.error(e);
            //         showMessage("Editor error occurred", "danger");
            //         return false;
            //     }

            //     const data = new FormData(document.getElementById("exerciseForm"));

            //     fetch("save_exercise.php", {
            //         method: "POST",
            //         body: data
            //     })
            //         .then(r => r.json())
            //         .then(res => {

            //             if (res.status === "success") {
            //                 showMessage("Exercise Added successfully!", "success");
            //                 document.getElementById("exercise_id").value = res.exercise_id;
            //                 document.getElementById("addInstructionBtn").style.display = "inline-block";
            //             } else if (res.status === "duplicate") {
            //                 showMessage(res.message, "error");
            //             }
            //             else {
            //                 showMessage(res.message || "Error occurred", "error");
            //             }

            //         });

            //     return false; // VERY IMPORTANT
            // }

            function saveExercise() {

                event.preventDefault(); // 🔥 VERY IMPORTANT

                let link = document.getElementById("trinketLink").value.trim();

                if (document.getElementById("trinketBox").style.display === "block") {

                    if (!link) {
                        showMessage("Please paste Trinket link", "error");
                        return false;
                    }

                    let embedLink = link.replace("/python3/", "/embed/python3/");

                    document.getElementById("editorAnswer").value =
                        encodeURIComponent(JSON.stringify({ trinket: embedLink }));

                    submitExerciseForm();
                    return false;
                }

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

                if (currentSaveCode === "1") {

                    const iframe = document.getElementById("editorFrame");

                    if (!iframe.contentWindow || !iframe.contentWindow.getEditorCode) {
                        showMessage("Editor not loaded properly", "danger");
                        return false;
                    }

                    try {

                        const result = iframe.contentWindow.getEditorCode();

                        // 🔹 If direct result (Python / HTML / Monaco)
                        if (result !== undefined) {

                            document.getElementById("editorAnswer").value =
                                encodeURIComponent(JSON.stringify(result));

                            submitExerciseForm();
                            return false;
                        }

                    } catch (e) {
                        console.log("Callback editor detected");
                    }

                    // 🔹 If callback editor (Scratch)
                    iframe.contentWindow.getEditorCode(function (result) {

                        document.getElementById("editorAnswer").value =
                            encodeURIComponent(JSON.stringify(result));

                        submitExerciseForm();

                    });

                    return false;
                }

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
                            showMessage("Exercise Added successfully!", "success");
                            document.getElementById("exercise_id").value = res.exercise_id;
                            document.getElementById("addInstructionBtn").style.display = "inline-block";
                            // ✅ ADD THIS LINE
                            disableExerciseForm();
                        } else if (res.status === "duplicate") {
                            showMessage(res.message, "error");
                        }
                        else if (res.status === "duplicate_sort_order") {
                            showMessage(res.message, "error");
                        }
                        else {
                            showMessage("❌ Something went wrong!", "error");
                        }

                    });

            }



            function saveInstruction() {


                const exerciseId = document.getElementById("exercise_id").value;
                console.log("Exercise ID:", exerciseId);
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
                data.append("instruction_id", instructionId); // 🔥 key
                data.append("instruction_text", instructionText);
                data.append("hint_text", hintText);
                data.append("instruction_sort_order", sortOrder);

                const instructionImage = document.getElementById("instructionImage")?.files[0];
                if (instructionImage) data.append("instruction_image", instructionImage);

                const hintImage = document.getElementById("hintImage")?.files[0];
                if (hintImage) data.append("hint_image", hintImage);

                // const spriteImage = document.getElementById("spriteImage")?.files[0];
                // if (spriteImage) data.append("sprite_image", spriteImage);

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
                                            <i class="fas fa-trash text-danger"
                                             onclick="openDeleteModal('instruction_delete.php?id=${instr.id}&exercise_id=${exerciseId}&lesson_id=<?= $lesson_id ?>', 'Are you sure you want to delete this instruction?')"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                        // UPDATE
                        if (instructionId) {
                            document.getElementById("instr-" + instr.id).outerHTML = html;
                            showMessage("Instruction Updated Successfully!", "success");
                        }
                        // INSERT
                        else {
                            list.insertAdjacentHTML("beforeend", html);
                            document.getElementById("instructionHeading").style.display = "block";
                            showMessage("Instruction Added Successfully!", "success");
                        }

                        resetInstructionModal();
                        // ✅ Close modal
                        const modalEl = document.getElementById("instructionModal");
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) {
                            modal.hide();
                        }

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

        <script>
            window.onload = function () {
                loadAssets('image');
            };

            let arduinoPopup = null;

            function openArduinoLogin() {
                window.open("https://app.arduino.cc/");
            }

            function confirmArduinoLogin() {
                // modal close
                const modal = bootstrap.Modal.getInstance(document.getElementById('arduinoLoginModal'));
                modal.hide();

                alert("Login confirmed!");
            }




        </script>





</body>

</html>