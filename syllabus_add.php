<?php
session_start();
include 'header.php';
include 'config.php';

$course_id = isset($_GET['course_id']) ? intval($_GET['course_id']) : 0;
$course_name = isset($_GET['course_name']) ? htmlspecialchars($_GET['course_name']) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Lesson </title>
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

                    <!-- Header Same as Popup -->
                    <div class="card-header"
                        style="background:#1da1f2;color:white;">
                        <h4 class="m-0">Add Syllabus</h4>
                    </div>

                    <div class="card-body">

                        <form id="syllabusForm" action="syllabus_insert.php" method="POST"
                            enctype="multipart/form-data">
                            <!-- syllabus Name -->
                            <div class="mb-3">
                                <input type="hidden" name="syllabus_type" value="syllabus">
                                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                                <input type="hidden" name="course_name" value="<?php echo $course_name; ?>">

                                <label class="form-label fw-bold">Syllabus Name </label>
                                <input type="text" name="syllabus_name" class="form-control">
                            </div>

                            <!-- syllabus -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Syllabus (PDF) </label>
                                <input type="file" name="syllabus" class="form-control" accept=".pdf">
                            </div>

                            <!-- Buttons -->
                            <div class="text-end mt-4">
                                <a href="manage-lesson.php?course_id=<?php echo $course_id; ?>&course_name=<?php echo $course_name; ?>"
                                    class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <button class="btn btn-primary" type="button" onclick="submitSyllabusForm()">
                                    <i class="fas fa-save"></i> Save Syllabus
                                </button>
                            </div>

                        </form>

                    </div>

                </div>




            </div>
        </div>
    </div>
    <script>
    const MAX_FILE_SIZE = 16 * 1024 * 1024; // 16 MB

    function checkFileSize(file, label) {
        if (file && file.size > MAX_FILE_SIZE) {
            showMessage(label + " must be less than 16 MB", "error");
            return false;
        }
        return true;
    }

    function submitSyllabusForm() {
        let syllabusName = document.querySelector("input[name='syllabus_name']").value.trim();
        let syllabusFile = document.querySelector("input[name='syllabus']").files[0];

        // if (syllabusName === "") {
        //     showMessage("Please enter Syllabus Name", "error");
        //     return;
        // }

        // if (!syllabusFile) {
        //     showMessage("Please upload Syllabus PDF", "error");
        //     return;
        // }

        // 16MB CHECK
        if (!checkFileSize(syllabusFile, "Syllabus PDF")) {
            document.querySelector("input[name='syllabus']").value = "";
            return;
        }

        // Submit form
        document.getElementById("syllabusForm").submit();
    }
</script>


    <!-- <script>
        const MAX_FILE_SIZE = 16 * 1024 * 1024; // 16 MB

        function checkFileSize(file, label) {
            if (file && file.size > MAX_FILE_SIZE) {
                showMessage(label + " must be less than 16 MB", "error");
                return false;
            }
            return true;
        }
        function submitSyllabusForm() {
            let syllabusname = document.querySelector("input[name='syllabus_name']").value.trim();
            let syllabus = document.querySelector("input[name='syllabus']").files[0];
            let syllabusFile = syllabusInput.files[0];

            // if (syllabusname === "") {
            //     showMessage("Please enter Syllabus Name", "error");
            //     return;
            // }

            // if (!syllabus) {
            //     showMessage("Please upload Syllabus PDF", "error");
            //     return;
            // }
            🔥 16MB CHECK
            if (!checkFileSize(syllabusFile, "Syllabus PDF")) {
                syllabusInput.value = ""; // reset file
                return;
            }

            // Submit form after validation
            document.getElementById("syllabusForm").submit();
        }
    </script> -->
</body>

</html>