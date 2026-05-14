<?php
session_start();
include 'header.php';
include 'config.php';

$course_id = intval($_GET['course_id'] ?? 0);
$course_name = htmlspecialchars($_GET['course_name'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Lesson</title>
    <style>
        #enableEditor {
            transform: scale(1.5);
            /* size bada */
            margin-right: 8px;
            margin-left: 5px;
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

            <div class="sidebar"><?php include 'menus.php'; ?></div>

            <div class="main-area text-dark">
                <div id="globalMsg" class="global-msg"></div>




                <div class="card shadow">
                    <div class="card-header" style="background:#1da1f2;color:white;">
                        <h4 class="m-0">Add Lesson</h4>
                    </div>

                    <div class="card-body">
                        <form id="lessonForm" enctype="multipart/form-data">

                            <input type="hidden" name="lesson_type" value="lesson">
                            <input type="hidden" name="course_id" value="<?= $course_id ?>">
                            <input type="hidden" name="course_name" value="<?= $course_name ?>">

                            <div class="mb-3">
                                <label class="fw-bold">Lesson Name </label>
                                <input type="text" name="lesson_name" id="lesson_name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Lesson Guideline </label>
                                <textarea name="lesson_guideline" class="form-control" required></textarea>
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

                                <!-- REMOVE FLAG -->
                                <input type="hidden" name="remove_lesson_guideline_image" id="removelessonImage"
                                    value="0">

                                <div class="image-wrapper">
                                    <img id="lessonPreview" src="<?= !empty($existing_lesson_guideline_image)
                                        ? 'uploads/' . htmlspecialchars($existing_lesson_guideline_image)
                                        : 'images/systemimages/placeholder-image.png'; ?>" alt="lesson Image"
                                        onclick="document.getElementById('lessonImage').click();">

                                    <!-- ❌ CROSS ICON -->
                                    <span class="remove-image" onclick="removelessonImage()">
                                        <i class="fas fa-times"></i>
                                    </span>

                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Lesson (PDF)</label>
                                <input type="file" name="lesson" class="form-control" accept=".pdf">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Lesson (Video) <small>(Maximum Size: 100MB)</small></label>
                                <input type="file" name="lesson_video" class="form-control" accept="video/*">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Lesson Plan (PDF)</label>
                                <input type="file" name="lesson_plan_pdf" class="form-control" accept=".pdf">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Lesson Summary (PDF)</label>
                                <input type="file" name="lesson_summary" class="form-control" accept=".pdf">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Teacher Game File</label>
                                <input type="file" name="teacher_game_file" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Student Game File</label>
                                <input type="file" name="student_game_file" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Lesson Sort Order <span class="text-danger">*</span></label>
                                <select name="lesson_sort_order" id="lesson_sort_order" class="form-control" required>
                                    <option value="">Select Lesson Order</option>
                                    <?php for ($i = 1; $i <= 50; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <input type="checkbox" id="enableEditor" onchange="toggleEditor()">
                                    Add Button To Open Editor
                                </label>
                            </div>

                            <div class="mb-3" id="editorBox" style="display:none;">
                                <label class="form-label fw-semibold">Select Editor</label>

                                <select name="lesson_editor" id="editorSelect" class="form-control">

                                    <option value="">Select Editor</option>

                                    <option value="codey.php">Codey Rockey</option>
                                    <option value="ai_for_juniors.php">AI for Juniors</option>
                                    <option value="3d_drawing.php">3D Drawing</option>
                                    <option value="python_editor.php">Python</option>
                                    <option value="vr_programming.php">Virtual Reality Programming</option>
                                    <option value="introduction_to_ai.php">Introduction to Artificial Intelligence
                                    </option>
                                    <option value="introduction_to_javascript_gr8.php">Introduction to Javascript
                                        gr8</option>
                                    <option value="introduction_to_data_analytics.php">Introduction to Data
                                        Analytics</option>
                                    <option value="html_css.php">HTML & CSS</option>
                                    <option value="game_dev_js.php">Game Development JS</option>
                                    <option value="data_visualisation.php">Data Visualisation</option>

                                    <option value="coding_logic.php">Introduction to Coding Logic</option>
                                    <option value="javaP5.php">Java Processing p5 js</option>
                                    <option value="makeymakey.php">Makey Makey</option>
                                    <option value="microbitprog.php">Microbit Programming</option>
                                    <option value="microbitJs.php">Microbit JavaScript</option>
                                    <option value="cyberpi.php">CyberPi</option>
                                    <option value="mbotneo.php">mBot Neo</option>
                                    <option value="tinybit_ai.php">Tinybit AI Vision</option>
                                    <option value="scratchJr.php">Scratch Jr</option>
                                    <option value="scratch.php">Scratch</option>
                                    <option value="spherobolt.php">Sphero Bolt</option>
                                    <option value="mbot.php">mBot Robot Coding</option>
                                    <option value="arduino.php">Arduino</option>
                                    <option value="https://app.arduino.cc/">ARDUINO GR12</option>




                                </select>
                            </div>

                            <div class="text-end mt-4">
                                <a href="manage-lesson.php?course_id=<?= $course_id ?>&course_name=<?= $course_name ?>"
                                    class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>

                                <button type="button" class="btn btn-primary" onclick="submitLesson()">
                                    <i class="fas fa-save"></i> Save Lesson
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
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

        function removelessonImage() {
            // preview ko placeholder pe set karo
            document.getElementById("lessonPreview").src =
                "images/systemimages/placeholder-image.png";

            // file input clear
            document.getElementById("lessonImage").value = "";

            // server ko batao image remove karni hai
            document.getElementById("removelessonImage").value = "1";
        }
    </script>

    <script>


        const MAX_PDF_SIZE = 30 * 1024 * 1024;   // 30MB
        const MAX_VIDEO_SIZE = 200 * 1024 * 1024;  // 200MB


        function checkFileSize(file, maxSize, label) {

            if (!file) return true; // optional file

            console.log(label, "size:", (file.size / (1024 * 1024)).toFixed(2), "MB");

            if (file.size > maxSize) {
                showMessage(`❌ ${label} must be under ${Math.round(maxSize / 1024 / 1024)}MB`, "error");
                console.log(label, "size:", (file.size / (1024 * 1024)).toFixed(2), "MB");
                return false;
            }

            return true;
        }
        function submitLesson() {

            const form = document.getElementById("lessonForm");

            const lesson = form.lesson.files[0];
            const plan = form.lesson_plan_pdf.files[0];
            const lesson_summary = form.lesson_summary.files[0];
            const lesson_video = form.lesson_video.files[0];

            const teacherGame = form.teacher_game_file.files[0];
            const studentGame = form.student_game_file.files[0];



            const sortOrder = document.getElementById("lesson_sort_order").value;

            if (!sortOrder) {
                showMessage("Please select Lesson Sort Order", "error");
                return;
            }
            showLoader();
            if (!checkFileSize(lesson, MAX_PDF_SIZE, "Lesson PDF")) return;
            if (!checkFileSize(plan, MAX_PDF_SIZE, "Lesson Plan PDF")) return;
            if (!checkFileSize(lesson_summary, MAX_PDF_SIZE, "Lesson Summary PDF")) return;
            if (!checkFileSize(lesson_video, MAX_VIDEO_SIZE, "Lesson Video")) return;


            // ✅ ONLY HERE fetch() is allowed
            const fd = new FormData(form);

            fetch("lesson_insert.php", {
                method: "POST",
                body: fd
            })
                .then(r => r.text())
                .then(res => {
                    res = res.trim();

                    if (res === "success") {
                        hideLoader();
                        showMessage("✅ Lesson Added Successfully!", "success");
                        setTimeout(() => {
                            window.location.href =
                                "manage-lesson.php?course_id=<?= $course_id ?>&course_name=<?= urlencode($course_name) ?>";
                        }, 1000);
                    } else {
                        hideLoader();
                        showMessage("❌ " + res, "error");
                    }
                });
        }


        // function submitLesson() {

        //     const form = document.getElementById("lessonForm");
        //     const lesson = form.lesson.files[0];
        //     const plan = form.lesson_plan_pdf.files[0];
        //     const lesson_summary = form.lesson_summary.files[0];
        //     const lesson_video = form.lesson_video.files[0];

        //     const sortOrder = document.getElementById("lesson_sort_order").value;

        //     if (!sortOrder) {
        //         showMessage("Please select Lesson Sort Order", "error");
        //         return;
        //     }

        //     if (!checkFileSize(lesson, "Lesson PDF")) return false;
        //     if (!checkFileSize(plan, "Lesson Plan PDF")) return false;
        //     if (!checkFileSize(lesson_summary, "Lesson Summary")) return false;
        //     if (!checkFileSize(lesson_video, "Lesson Video")) return false;


        //     const fd = new FormData(form);

        //     fetch("lesson_insert.php", {
        //         method: "POST",
        //         body: fd
        //     })
        //         .then(r => r.text())
        //         .then(res => {
        //             res = res.trim();

        //             if (res === "success") {
        //                 showMessage("✅ Lesson Added Successfully!", "success");
        //                 setTimeout(() => {
        //                     window.location.href =
        //                         "manage-lesson.php?course_id=<?= $course_id ?>&course_name=<?= urlencode($course_name) ?>";
        //                 }, 1000);
        //             } else if (res === "duplicate") {
        //                 showMessage("⚠️ Lesson already exists", "warning");
        //             } else if (res === "missing_sort_order") {
        //                 showMessage("❌ Please select Lesson Sort Order", "error");
        //             } else {
        //                 showMessage("❌ " + res, "error");
        //                 // console.log(res);
        //             }
        //         });
        // }


    </script>

</body>

</html>