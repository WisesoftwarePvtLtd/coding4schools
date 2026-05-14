<?php
session_start();
include 'header.php';
include 'config.php';

$lesson_id = isset($_GET['lesson_id']) ? intval($_GET['lesson_id']) : 0;
$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$course_name = isset($_GET['course_name']) ? htmlspecialchars($_GET['course_name']) : '';

if (!$lesson_id)
    die("Invalid Lesson");

// Fetch lesson data
$q = $conn->query("SELECT * FROM lessons WHERE lesson_id=$lesson_id");
$data = $q->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Lesson </title>
    <style>
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
            /* 🔥 FIX */
            max-width: 600px;
            /* optional */
        }

        #existingAudioList,
        #audioPreview {
            display: flex;
            /* 🔥 FIX */
            flex-direction: column;
            gap: 10px;
        }

        .delete-icon {
            width: 28px;
            height: 28px;
            font-size: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            cursor: pointer;
            flex-shrink: 0;
            /* 🔥 FIX */
        }
    </style>
</head>

<body>
    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="main-area text-dark">
                <!-- GLOBAL MESSAGE BOX -->
                <div id="globalMsg" class="global-msg"></div>
              

                <div class="card shadow">

                    <div class="card-header bg-primary text-white">
                        <h4 class="m-0">Edit Syllabus</h4>
                    </div>

                    <div class="card-body">

                        <form id="editSyllabusForm" method="POST" action="syllabus_update.php"
                            enctype="multipart/form-data">
                            <input type="hidden" name="lesson_type" value="syllabus">
                            <input type="hidden" name="lesson_id" value="<?php echo $lesson_id; ?>">
                            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                            <input type="hidden" name="course_name" value="<?php echo $course_name; ?>">

                            <!-- Lesson Name -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Syllabus Name</label>
                                <input type="text" name="syllabus_name" class="form-control"
                                    value="<?php echo $data['lesson_title']; ?>" >
                            </div>

                            <!-- Lesson PDF -->
                            <!-- Syllabus PDF -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Syllabus (PDF)</label>

                                <?php if (!empty($data['lesson_file_path'])) { ?>
                                    <!-- Existing File Box -->
                                    <div class="file-item-box" id="syllabusFileBox">
                                        <span><i class="fas fa-book text-primary"></i>
                                            <?php echo basename($data['lesson_file_path']); ?></span>
                                        <span class="delete-icon" onclick="deleteSyllabus()">✖</span>
                                    </div>
                                <?php } ?>

                                <!-- Hidden delete flag -->
                                <input type="hidden" name="syllabus_deleted" id="syllabus_deleted" value="0">

                                <!-- File Input -->
                                <input type="file" name="syllabus" id="syllabusInput" class="form-control" accept=".pdf"
                                    style="<?php echo $data['lesson_file_path'] ? 'display:none;' : ''; ?>">
                            </div>





                            <!-- Buttons -->
                            <div class="text-end mt-4">
                                <a href="manage-lesson.php?course_id=<?php echo $course_id; ?>&course_name=<?php echo $course_name; ?>"
                                    class="btn btn-primary" style="width: 7%;"><i class="fas fa-arrow-left"></i>
                                    Back</a>
                                <button class="btn btn-primary" type="submit" onclick="return validateSyllabus()">
                                    Update Syllabus
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- <script>


function validateSyllabus() {
    let name = document.querySelector("input[name='syllabus_name']").value.trim();
    let fileInput = document.querySelector("input[name='syllabus']");
    let pdf = fileInput.files[0];

    // Name validation
    if (name === "") {
        showMessage("Please enter Syllabus Name", "error");
        return false;
    }

    // PDF validation (if uploaded)
    if (pdf) {
        let ext = pdf.name.split(".").pop().toLowerCase();
        if (ext !== "pdf") {
            showMessage("Only PDF files are allowed", "error");
            return false;
        }
    }

    return true; // Allow form submit
}
</script> -->
    <script>
        const MAX_FILE_SIZE = 16 * 1024 * 1024; // 16 MB
        function deleteSyllabus() {
            // if (!confirm("Delete existing syllabus PDF?")) return;

            document.getElementById("syllabus_deleted").value = "1";
            document.getElementById("syllabusFileBox").style.display = "none";
            document.getElementById("syllabusInput").style.display = "block";
        }

        function validateSyllabus() {
            let name = document.querySelector("input[name='syllabus_name']").value.trim();
            let pdfInput = document.getElementById("syllabusInput");
            let deleted = document.getElementById("syllabus_deleted").value;
            let pdf = pdfInput.files[0];

            // if (name === "") {
            //     showMessage("Please enter Syllabus Name", "error");
            //     return false;
            // }

            // // ❗ Only validate PDF if user deleted old file
            // if (deleted === "1" && !pdf) {
            //     showMessage("Please upload Syllabus PDF", "error");
            //     return false;
            // }

            if (pdf) {
                let ext = pdf.name.split(".").pop().toLowerCase();
                // if (ext !== "pdf") {
                //     showMessage("Only PDF files are allowed", "error");
                //     return false;
                // }
                // 🔥 16MB SIZE CHECK
                if (pdf.size > MAX_FILE_SIZE) {
                    showMessage("Syllabus PDF must be less than 16 MB", "error");
                    pdfInput.value = ""; // reset file
                    return false;
                }
            }

            return true;
        }
    </script>

</body>

</html>