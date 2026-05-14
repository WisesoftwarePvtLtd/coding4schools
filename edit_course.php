<?php
session_start();
include 'config.php';
include 'header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_course.php");
    exit;
}

$course_id = (int) $_GET['id'];

/* FETCH COURSE */
$stmt = $conn->prepare("SELECT * FROM courses WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();
$stmt->close();

if (!$course) {
    header("Location: manage_course.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Course</title>


</head>

<body>
    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">

            <div class="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <div class="main-area">
                <!-- GLOBAL MESSAGE BOX -->
                <div id="globalMsg" class="global-msg"></div>
                <div class="card shadow-sm" style="max-width:2000px;border-radius:12px; top:18px;">

                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background:#1da1f2;color:#fff;">
                        <h4 class="m-0 fw-bold">
                            <i class="fas fa-edit"></i> Edit Course
                        </h4>

                    </div>

                    <div class="card-body p-4">
                        <form id="editCourseForm" enctype="multipart/form-data">



                            <input type="hidden" name="id" value="<?= $course['course_id'] ?>">

                            <!-- TITLE -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Course Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="title" id="course_title" class="form-control"
                                    value="<?= htmlspecialchars($course['course_title']) ?>">
                            </div>

                            <div class="mb-3">
                                <label class="fw-bold">Course Sort Order</label>
                                <select name="course_sort_order" id="course_sort_order" class="form-control">
                                    <?php for ($i = 1; $i <= 50; $i++): ?>
                                        <option value="<?= $i ?>" <?= ($i == $course['course_sort_order']) ? "selected" : "" ?>>
                                            <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- COVER -->
                            <?php
                            $existing_course_cover = $course['course_cover_page'] ?? '';
                            ?>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Course Cover <span class="text-danger">*</span>
                                </label>

                                <!-- Hidden File Input -->
                                <input type="file" name="cover" id="courseCoverInput" accept="image/*" hidden
                                    onchange="previewImage(this, 'courseCoverPreview')">

                                <!-- Clickable Image Box -->
                                <div class="image-upload-box"
                                    onclick="document.getElementById('courseCoverInput').click();">

                                    <img id="courseCoverPreview" src="<?= !empty($existing_course_cover)
                                        ? 'uploads/courses/course-' . $course_id . '/' . htmlspecialchars($existing_course_cover)
                                        : 'images/systemimages/placeholder-image.png'; ?>" alt="Course Cover">
                                    <?php if (!empty($existing_course_cover)) { ?>
                                        <span class="remove-image" onclick="removeCourseImage(event, <?= $course_id ?>)">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    <?php } ?>

                                </div>
                            </div>

                            <!-- DETAILS -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Course Description </label>
                                <textarea name="details" rows="3"
                                    class="form-control"><?= htmlspecialchars($course['course_details']) ?></textarea>
                            </div>

                            <!-- BUTTONS -->
                            <div class="text-end">
                                <a href="manage_course.php" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button class="btn btn-primary btn-space">
                                    <i class="fas fa-save"></i> Update Course
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function removeCourseImage(e, courseId) {
            e.stopPropagation();

            fetch("course_image_delete.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + courseId
            })
                .then(res => res.text())
                .then(res => {
                    res = res.trim();

                    if (res === "success") {
                        document.getElementById("courseCoverPreview").src =
                            "images/systemimages/placeholder-image.png";

                        showMessage("Image removed successfully", "success");
                    } else {
                        showMessage("Failed to remove image", "danger");
                    }
                });
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


        document.getElementById("editCourseForm").addEventListener("submit", function (e) {
            e.preventDefault(); // ⛔ STOP PAGE RELOAD
            const coverInput = document.getElementById("courseCoverInput");
            const courseTitle = document.getElementById("course_title");

            // Title validation
            if (!courseTitle.value.trim()) {
                showMessage("Please enter a course title", "error");
                return;
            }

            // Cover validation — only if there is no existing image
            const existingCoverSrc = document.getElementById("courseCoverPreview").src;
            const placeholderSrc = "images/systemimages/placeholder-image.png";

            // if (!coverInput.files[0] && existingCoverSrc.endsWith(placeholderSrc)) {
            //     showMessage("Please select a course cover", "error");
            //     return;
            // }
            const formData = new FormData(this);

            fetch("course_update.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.text())
                .then(res => {
                    res = res.trim();

                    if (res === "success") {
                        showMessage("Course updated successfully", "success");
                        setTimeout(() => {
                            window.location.href = "manage_course.php";
                        }, 1200);
                    }
                    else if (res === "duplicate") {
                        showMessage("Course title already exists", "warning");
                    } 
                    else if (res === "duplicate_sort_order") {
                        showMessage("A course with the same sort order already exists", "error");
                    }
                    else {
                        showMessage("Update failed", "danger");
                    }
                })
                .catch(() => {
                    showMessage("Network error", "danger");
                });
        });
    </script>

    <script>
        const msg = <?= json_encode($_SESSION['msg'] ?? '') ?>;
        const type = <?= json_encode($_SESSION['transaction_status'] ?? 'success') ?>;

        if (msg && msg.trim() !== '') {
            showMessage(msg, type);
        }
    </script>
    <?php unset($_SESSION['msg'], $_SESSION['transaction_status']); ?>


</body>

</html>