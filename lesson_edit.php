<?php
session_start();
include 'header.php';
include 'config.php';


$lesson_id = intval($_GET['lesson_id'] ?? 0);
$course_id = intval($_GET['course_id'] ?? 0);
$course_name = htmlspecialchars($_GET['course_name'] ?? '');

if (!$lesson_id) {
    die("Invalid Lesson");
}

/* ===== SAFE FETCH ===== */
$stmt = $conn->prepare("SELECT * FROM lessons WHERE lesson_id = ?");
$stmt->bind_param("i", $lesson_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$data) {
    die("Lesson Not Found");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta charset="UTF-8">
    <title>Edit Lesson</title>

    <style>
        #enableEditor {
            transform: scale(1.5);
            /* size bada */
            margin-right: 8px;
            margin-left: 5px;
        }

        .file-item-box,
        .audio-item {
            background: #ffffff;
            border: 2px solid #ffb3b3;
            border-radius: 30px;
            padding: 10px 18px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.15);
            position: relative;
            width: 100%;
            font-size: 16px;
        }

        #existingAudioList,
        #audioPreview {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .delete-icon {
            width: 28px;
            height: 28px;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            cursor: pointer;
            flex-shrink: 0;
            color: red;
        }

        /* ===============================
   FIX BUTTON HOVER BORDER
================================ */
        .btn,
        .btn:focus,
        .btn:hover,
        .btn:active {
            outline: none !important;
            box-shadow: none !important;
            border-color: #1da1f2 !important;
            /* keep blue border */
            background-color: #1da1f2 !important;
            color: #fff !important;
        }

        /* Remove orange outline in Firefox */
        button::-moz-focus-inner {
            border: 0;
        }

        /* ===============================
   FIX CARD / EDIT BOX MOVEMENT
================================ */
        .card,
        .file-item-box,
        .audio-item {
            border: 2px solid transparent;
            /* reserve space */
            transition: none !important;
        }

        .card:hover,
        .file-item-box:hover,
        .audio-item:hover {
            border-color: transparent !important;
            box-shadow: inherit !important;
            transform: none !important;
        }

        /* ===============================
   FIX INPUT & SELECT HOVER / FOCUS
================================ */
        input:focus,
        select:focus,
        textarea:focus {
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(29, 161, 242, 0.25) !important;
            border-color: #1da1f2 !important;
        }

        .image-wrapper {
            position: relative;
            width: 200px;
            height: 200px;
            cursor: pointer;
        }

        .image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        /* ❌ CROSS */
        .remove-image {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 28px;
            height: 28px;
            color: #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            font-size: 26px;
            display: flex;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-3" style="padding:30px;">
        <div class="layout-row">
            <div id="page-loader" class="loader-overlay" style="display:none;">
                <div class="hourglass"></div>
            </div>

            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <div class="main-area text-dark">

                <div id="globalMsg" class="global-msg"></div>

                <div class="card shadow">
                    <div class="card-header" style="background:#1da1f2; color:white;">
                        <h4 class="m-0">Edit Lesson</h4>
                    </div>

                    <div class="card-body">

                        <form id="editLessonForm" enctype="multipart/form-data">

                            <input type="hidden" name="lesson_id" value="<?= $lesson_id ?>">
                            <input type="hidden" name="course_id" value="<?= $course_id ?>">

                            <!-- Lesson Name -->
                            <div class="mb-3">
                                <label class="fw-bold">Lesson Name </label>
                                <input type="text" name="lesson_name" class="form-control"
                                    value="<?= htmlspecialchars($data['lesson_title']) ?>">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Lesson Guideline</label>
                                <textarea name="lesson_guideline" class="form-control"
                                    required><?= htmlspecialchars($data['lesson_guideline']) ?></textarea>
                            </div>

                            <?php
                            $existing_lesson_guideline_image = $data['lesson_guideline_image'] ?? '';
                            ?>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    lesson Guideline Image
                                </label>

                                <input type="file" name="lesson_guideline_image" id="lessonImage" accept="image/*"
                                    hidden onchange="previewImage(this, 'lessonPreview')">

                                <input type="hidden" name="remove_lesson_guideline_image"
                                    id="remove_lesson_guideline_image" value="0">

                                <div class="image-wrapper">
                                    <img id="lessonPreview" src="<?= !empty($existing_lesson_guideline_image)
                                        ? htmlspecialchars($existing_lesson_guideline_image)
                                        : 'images/systemimages/placeholder-image.png'; ?>" alt="lesson image"
                                        onclick="document.getElementById('lessonImage').click();">

                                    <?php if (!empty($existing_lesson_guideline_image)) { ?>
                                        <span class="remove-image" onclick="deletelessonImage(<?= $data['lesson_id'] ?>)">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    <?php } ?>
                                </div>
                            </div>


                            <?php
                            /* ===== FILE BOX FUNCTION (UI UNCHANGED) ===== */
                            // function fileBox($label, $field, $currentPath)
                            // {
                            //     $html = "<div class='mb-3'><label class='fw-bold'>{$label}</label>";
                            
                            //     if (!empty($currentPath)) {
                            //         $html .= "
                            //             <div class='file-item-box' id='{$field}FileBox'>
                            //                 <span class='file-text'>
                            //                     <i class='fas fa-book text-primary'></i> " . basename($currentPath) . "
                            //                 </span>
                            //                 <span class='delete-icon' onclick=\"deleteFile('{$field}')\">✖</span>
                            //             </div>";
                            //     }
                            
                            //     $style = empty($currentPath) ? "" : "style='display:none;'";
                            //     $html .= "
                            //         <input type='hidden' id='{$field}_deleted' name='{$field}_deleted' value='0'>
                            //         <input type='file' name='{$field}' id='{$field}_input' class='form-control'  accept='.pdf' {$style}>
                            //     </div>";
                            
                            //     return $html;
                            // }
                            function fileBox($label, $field, $currentPath)
                            {
                                // lesson_video ke liye alag rules
                                $isVideo = ($field === 'lesson_video');

                                $isGameFile = in_array($field, ['teacher_game_file', 'student_game_file']);

                                if ($isGameFile) {
                                    $accept = ""; // allow all files
                                    $icon = "fa-file";
                                } elseif ($isVideo) {
                                    $accept = "accept='video/*'";
                                    $icon = "fa-video";
                                } else {
                                    $accept = "accept='.pdf'";
                                    $icon = "fa-book";
                                }

                                $html = "<div class='mb-3'><label class='fw-bold'>{$label}</label>";

                                if (!empty($currentPath)) {
                                    $html .= "
                                        <div class='file-item-box' id='{$field}FileBox'>
                                            <span class='file-text'>
                                                <i class='fas {$icon} text-primary'></i> " . basename($currentPath) . "
                                            </span>
                                            <span class='delete-icon' onclick=\"deleteFile('{$field}')\">✖</span>
                                        </div>";
                                }

                                $style = empty($currentPath) ? "" : "style='display:none;'";
                                $html .= "
                                        <input type='hidden' id='{$field}_deleted' name='{$field}_deleted' value='0'>
                                        <input type='file'
                                            name='{$field}'
                                            id='{$field}_input'
                                            class='form-control'
                                            {$accept}
                                            {$style}>
                                    </div>";

                                return $html;
                            }
                            ?>

                            <?= fileBox("Lesson PDF", "lesson", $data['lesson_file_path']); ?>

                            <?= fileBox("Lesson Video <small>(Maximum Size: 100MB)</small>", "lesson_video", $data['lesson_video_path']); ?>

                            <?= fileBox("Lesson Plan PDF", "lesson_plan_pdf", $data['lesson_plan_path']); ?>
                            <?= fileBox("Lesson Summary", "lesson_summary_pdf", $data['lesson_summary_path']); ?>

                            <?= fileBox("Teacher Game File", "teacher_game_file", $data['teacher_game_file']); ?>

                            <?= fileBox("Student Game File", "student_game_file", $data['student_game_file']); ?>



                            <!-- Sort Order -->
                            <div class="mb-3">
                                <label class="fw-bold">Lesson Sort Order <span class="text-danger">*</span></label>
                                <select name="lesson_sort_order" id="lesson_sort_order" class="form-control" required>
                                    <?php for ($i = 1; $i <= 50; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($i == $data['lesson_sort_order']) ? "selected" : "" ?>>
                                            <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <input type="checkbox" id="enableEditor" onchange="toggleEditor()"
                                        <?= !empty($data['editor_url']) ? 'checked' : '' ?>>
                                    Add Button To Open Editor
                                </label>
                            </div>

                            <div class="mb-3" id="editorBox"
                                style="display: <?= !empty($data['editor_url']) ? 'block' : 'none' ?>;">
                                <label class="form-label fw-semibold">Select Editor</label>

                                <select name="lesson_editor" id="editorSelect" class="form-control">

                                    <option value="">Select Editor</option>

                                    <option value="codey.php?course=Codey Rockey"
                                        <?= $data['editor_url'] == 'codey.php?course=Codey Rockey' ? 'selected' : '' ?>>
                                        Codey Rockey
                                    </option>

                                    <option value="ai_for_juniors.php" <?= $data['editor_url'] == 'ai_for_juniors.php' ? 'selected' : '' ?>>
                                        AI for Juniors
                                    </option>

                                    <option value="3d_drawing.php" <?= $data['editor_url'] == '3d_drawing.php' ? 'selected' : '' ?>>
                                        3D Drawing
                                    </option>

                                    <option value="python_editor.php" <?= $data['editor_url'] == 'python_editor.php' ? 'selected' : '' ?>>
                                        Python
                                    </option>

                                    <option value="vr_programming.php" <?= $data['editor_url'] == 'vr_programming.php' ? 'selected' : '' ?>>
                                        Virtual Reality Programming
                                    </option>

                                    <option value="introduction_to_ai.php"
                                        <?= $data['editor_url'] == 'introduction_to_ai.php' ? 'selected' : '' ?>>
                                        Introduction to Artificial Intelligence
                                    </option>

                                    <option value="introduction_to_javascript_gr8.php"
                                        <?= $data['editor_url'] == 'introduction_to_javascript_gr8.php' ? 'selected' : '' ?>>
                                        Introduction to Javascript gr8
                                    </option>

                                    <option value="introduction_to_data_analytics.php"
                                        <?= $data['editor_url'] == 'introduction_to_data_analytics.php' ? 'selected' : '' ?>>
                                        Introduction to Data Analytics
                                    </option>

                                    <option value="html_css.php" <?= $data['editor_url'] == 'html_css.php' ? 'selected' : '' ?>>
                                        HTML & CSS
                                    </option>

                                    <option value="game_dev_js.php" <?= $data['editor_url'] == 'game_dev_js.php' ? 'selected' : '' ?>>
                                        Game Development JS
                                    </option>

                                    <option value="data_visualisation.php"
                                        <?= $data['editor_url'] == 'data_visualisation.php' ? 'selected' : '' ?>>
                                        Data Visualisation
                                    </option>

                                    <option value="coding_logic.php" <?= $data['editor_url'] == 'coding_logic.php' ? 'selected' : '' ?>>
                                        Introduction to Coding Logic
                                    </option>

                                    <option value="javaP5.php" <?= $data['editor_url'] == 'javaP5.php' ? 'selected' : '' ?>>
                                        Java Processing p5 js
                                    </option>

                                    <option value="makeymakey.php" <?= $data['editor_url'] == 'makeymakey.php' ? 'selected' : '' ?>>
                                        Makey Makey
                                    </option>

                                    <option value="microbitprog.php" <?= $data['editor_url'] == 'microbitprog.php' ? 'selected' : '' ?>>
                                        Microbit Programming
                                    </option>

                                    <option value="microbitJs.php" <?= $data['editor_url'] == 'microbitJs.php' ? 'selected' : '' ?>>
                                        Microbit JavaScript
                                    </option>

                                    <option value="cyberpi.php" <?= $data['editor_url'] == 'cyberpi.php' ? 'selected' : '' ?>>
                                        CyberPi
                                    </option>

                                    <option value="mbotneo.php" <?= $data['editor_url'] == 'mbotneo.php' ? 'selected' : '' ?>>
                                        mBot Neo
                                    </option>

                                    <option value="tinybit_ai.php" <?= $data['editor_url'] == 'tinybit_ai.php' ? 'selected' : '' ?>>
                                        Tinybit AI Vision
                                    </option>

                                    <option value="scratchJr.php" <?= $data['editor_url'] == 'scratchJr.php' ? 'selected' : '' ?>>
                                        Scratch Jr
                                    </option>

                                    <option value="scratch.php" <?= $data['editor_url'] == 'scratch.php' ? 'selected' : '' ?>>
                                        Scratch
                                    </option>

                                    <option value="spherobolt.php" <?= $data['editor_url'] == 'spherobolt.php' ? 'selected' : '' ?>>
                                        Sphero Bolt
                                    </option>

                                    <option value="mbot.php" <?= $data['editor_url'] == 'mbot.php' ? 'selected' : '' ?>>
                                        mBot Robot Coding
                                    </option>

                                    <option value="arduino.php" <?= $data['editor_url'] == 'arduino.php' ? 'selected' : '' ?>>
                                        Arduino
                                    </option>

                                     <option value="https://app.arduino.cc/" <?= $data['editor_url'] == 'https://app.arduino.cc/' ? 'selected' : '' ?>>
                                        ARDUINO GR12
                                    </option>

                                </select>
                            </div>

                            <div class="mt-4 text-end">
                                <a href="manage-lesson.php?course_id=<?= $course_id ?>&course_name=<?= $course_name ?>"
                                    class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>

                                <button type="button" class="btn btn-primary" onclick="updateLesson()">
                                    Update Lesson
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            toggleEditor();
        });
    </script>
    <script>
        function toggleEditor() {
            const checkbox = document.getElementById("enableEditor");
            const editorBox = document.getElementById("editorBox");

            if (checkbox.checked) {
                editorBox.style.display = "block";
            } else {
                editorBox.style.display = "none";
                document.getElementById("editorSelect").value = ""; // reset
            }
        }

        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById(previewId).src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        function deletelessonImage() {

            document.getElementById("lessonPreview").src =
                "images/systemimages/placeholder-image.png";

            document.getElementById("lessonImage").value = "";

            document.getElementById("remove_lesson_guideline_image").value = "1";

            const cross = document.querySelector(".remove-image");
            if (cross) cross.remove();
        }

        // function deletelessonImage(lessonId) {

        //     fetch("lesson_image_delete.php", {
        //         method: "POST",
        //         headers: { "Content-Type": "application/x-www-form-urlencoded" },
        //         body: "lesson_id=" + lessonId
        //     })
        //         .then(res => res.text())
        //         .then(res => {
        //             if (res.trim() === "success") {
        //                 document.getElementById("lessonPreview").src =
        //                     "images/systemimages/placeholder-image.png";
        //                 showMessage("Image removed successfully", "success");
        //                 document.querySelector(".remove-image").remove();
        //             } else {
        //                 showMessage("Failed to remove image", "danger");
        //             }
        //         });
        // }
    </script>

    <script>

        const MAX_PDF_SIZE = 30 * 1024 * 1024;   // 30MB
        const MAX_VIDEO_SIZE = 200 * 1024 * 1024; // 200MB

        function checkFileSize(file, maxSize, label) {
            if (!file) return true; // optional in edit

            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);

            if (file.size > maxSize) {
                showMessage(`❌ ${label} must be under ${Math.round(maxSize / 1024 / 1024)}MB (Selected: ${sizeMB}MB)`, "error");
                return false;
            }
            return true;
        }

        /* ===== DELETE FILE ===== */
        function deleteFile(type) {
            document.getElementById(type + "_deleted").value = "1";
            document.getElementById(type + "FileBox").style.display = "none";
            document.getElementById(type + "_input").style.display = "block";
        }

        /* ===== UPDATE LESSON ===== */
        function updateLesson() {
            const form = document.getElementById("editLessonForm");

            const lessonPDF = form.querySelector("input[name='lesson']")?.files[0];
            const lessonVideo = form.querySelector("input[name='lesson_video']")?.files[0];
            const lessonPlan = form.querySelector("input[name='lesson_plan_pdf']")?.files[0];
            const lessonSum = form.querySelector("input[name='lesson_summary_pdf']")?.files[0];
            const teacherGame = form.querySelector("input[name='teacher_game_file']")?.files[0];
            const studentGame = form.querySelector("input[name='student_game_file']")?.files[0];

            const sortOrder = document.getElementById("lesson_sort_order").value;

            if (!sortOrder) {
                showMessage("Please select Lesson Sort Order", "error");
                return;
            }

            if (!checkFileSize(lessonPDF, MAX_PDF_SIZE, "Lesson PDF")) return;
            if (!checkFileSize(lessonPlan, MAX_PDF_SIZE, "Lesson Plan PDF")) return;
            if (!checkFileSize(lessonSum, MAX_PDF_SIZE, "Lesson Summary PDF")) return;
            if (!checkFileSize(lessonVideo, MAX_VIDEO_SIZE, "Lesson Video")) return;
            showLoader();
            const fd = new FormData(form);

            fetch("lesson_update.php", {
                method: "POST",
                body: fd
            })
                .then(r => r.text())
                .then(res => {
                    hideLoader();
                    if (res.trim() === "success") {
                        showMessage("Lesson Updated Successfully!", "success");
                        setTimeout(() => {
                            window.location.href =
                                "manage-lesson.php?course_id=<?= $course_id ?>&course_name=<?= urlencode($course_name) ?>";
                        }, 1000);
                    } else {
                        hideLoader();
                        showMessage(res, "error");
                    }
                });
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let msg = "<?php echo $_SESSION['msg'] ?? ''; ?>";
            let type = "<?php echo $_SESSION['transaction_status'] ?? 'success'; ?>";

            if (msg.trim() !== "") {
                showMessage(msg, type);
            }
        });
    </script>
    <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>

</body>

</html>