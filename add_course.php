<?php
session_start();
include 'header.php';
$userType = $_SESSION['LoggedInUserType'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Course</title>
</head>

<body>

    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="main-area">
                <div id="globalMsg" class="global-msg"></div>

                <div class="card shadow-sm" style="border-radius:12px; max-width:2000px; top:18px;">

                    <!-- Header -->
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background:#1da1f2;color:#fff;border-radius:12px 12px 0 0;">
                        <h4 class="m-0 fw-bold">
                            <i class="fas fa-book"></i> Add Course
                        </h4>

                    </div>

                    <!-- Body -->
                    <div class="card-body p-4">

                        <form id="addCourseForm" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Course Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="course_title" >
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Course Sort Order <span class="text-danger">*</span></label>
                                <select name="course_sort_order" id="course_sort_order" class="form-control" required>
                                    <option value="">Select Course Order</option>
                                    <?php for ($i = 1; $i <= 50; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Course Cover 
                                </label>

                                <!-- Hidden File Input -->
                                <input type="file" name="course_cover_page" id="courseCoverInput" accept="image/*"
                                    hidden onchange="previewImage(this, 'courseCoverPreview')">

                                <!-- Clickable Image Box -->
                                <div class="image-upload-box"
                                    onclick="document.getElementById('courseCoverInput').click();">

                                    <img id="courseCoverPreview" src="images/systemimages/placeholder-image.png"
                                        alt="Course Cover">
                                    <span class="remove-image" onclick="removeProblemImage(event)">
                                        <i class="fas fa-times"></i>
                                    </span>

                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Course Description </label>
                                <textarea class="form-control" id="course_details" rows="3"></textarea>
                            </div>


                            <!-- Buttons -->
                            <div class="text-end">
                                <a href="manage_course.php" class="btn btn-secondary px-4">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary px-4 btn-space">
                                    <i class="fas fa-save"></i> Save Course
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

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

        function removeProblemImage(e) {
            e.stopPropagation(); // ✅ upload click stop

            document.getElementById("courseCoverPreview").src = "images/systemimages/placeholder-image.png";
            document.getElementById("courseCoverInput").value = "";

            // ❌ icon hide again
            document
                .querySelector(".image-upload-box")
                .classList.remove("courseCoverPreview");
        }
    </script>


    <script>


        document.getElementById("addCourseForm").addEventListener("submit", function (e) {
            e.preventDefault();
            const coverInput = document.getElementById("courseCoverInput");
            const course = document.getElementById("course_title");
             const sortOrder = document.getElementById("course_sort_order");
            if (!course.value.trim()) {
                showMessage("Please enter a course title", "error");
                return;
            }
            // if (!coverInput.files[0]) {
            //     showMessage("Please select a course cover", "error");
            //     return;
            // }

            const formData = new FormData();
            formData.append("title", course_title.value.trim());
            formData.append("details", course_details.value.trim());
            formData.append("course_sort_order", sortOrder.value);
            formData.append("cover", courseCoverInput.files[0]);

            fetch("course_add.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.text())
                .then(res => {
                    res = res.trim();
                    if (res === "success") {
                        showMessage("Course added successfully", "success");
                        setTimeout(() => location.href = "manage_course.php", 1200);
                    } else if (res === "duplicate") {
                        showMessage("Course already exists", "error");
                    } else if (res === "duplicate_sort_order") {
                        showMessage("A course with the same sort order already exists", "error");
                    } else {
                        showMessage("Error occurred", "error");
                    }
                });
        });
    </script>




</body>

</html>