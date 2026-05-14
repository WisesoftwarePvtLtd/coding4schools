<?php include 'header.php';
$userType = $_SESSION['LoggedInUserType'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Courses </title>
    <!-- <style>
        .course-card {
            background: #fff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .12);
            overflow: hidden;
            
        }

    

    
.course-image {
    height: 200px;
    width: 100%;
    overflow: hidden;
}

.course-image img {
    width: 100%;
    height: 100%;
    /* object-fit: cover;    */
}



        .card-actions {
            position: absolute;
            top: 0px;
            right: 19px;
            display: flex;
            gap: 4px;
        }


        .course-body {
            padding: 16px;
            
        }

        .course-body h5 {
            font-weight: 700;
            font-size: 16px;
        }

        .course-body p {
            font-size: 13px;
            color: #555;
            min-height: 60px;
        }

        .course-body .btn {
            background: #1da1f2;
            border: none;
            font-weight: 600;
        }
    </style> -->

<style>
    .course-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    overflow: hidden;
    position: relative;
    transition: 0.3s;
}

.course-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 30px rgba(0,0,0,0.15);
}

/* IMAGE */
.course-image {
    height: 180px;
    position: relative;
}

.course-image img {
    width: 100%;
    height: 100%;
    /* object-fit: cover; */
}

/* BADGE */
.badge-label {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #6c5ce7;
    color: #fff;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 20px;
}

/* 3 DOT MENU */
.card-actions {
    position: absolute;
    top: 10px;
    right: 10px;
}

.card-actions i {
    /* background: rgba(0,0,0,0.6); */
    color: #fff;
    padding: 6px;
    border-radius: 50%;
    cursor: pointer;
}

/* FLOAT ICON */
.course-icon {
    position: absolute;
    bottom: 193px;
    left: 20px;
    background: #f1f3f6;
    padding: 10px;
    border-radius: 10px;
    font-size: 16px;
}

/* BODY */
.course-body {
    padding: 30px 16px 16px;
}

.course-body h5 {
    font-weight: 700;
    margin-bottom: 4px;
}

.course-category {
    font-size: 13px;
    color: #6c5ce7;
    margin-bottom: 8px;
}

.course-body p {
    font-size: 13px;
    color: #666;
    min-height: 60px;
}

/* BUTTON */
.course-body .btn {
    background: #eef2ff;
    color: #6c5ce7;
    border-radius: 20px;
    font-weight: 600;
    width: 100%;
    text-align: left;
    padding: 8px 16px;
}

.course-body .btn:hover {
    background: #6c5ce7;
    color: #fff;
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

                
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h3 class="fw-bolder ">
                            <?php if ($userType == STUDENT || $userType == TEACHER) {
                                echo "My Courses";
                            } else {
                                echo "Manage Courses";
                            } ?>
                        </h3>
                        <?php if (userHasPermission(COURSE_ADD)) { ?>
                            <a href="add_course.php" class="btn btn-primary">
                                <i class="fas fa-plus-circle"></i> Add Course
                            </a>
                        <?php } ?>
                    </div>
                    <!-- Filter Box -->
                    <?php if (userHasPermission(COURSE_SEARCH)) { ?>
                        <div class="filter-box mb-4 ">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group" style="gap:25px;">
                                        <span class="fw-semibold">Search Course</span>
                                        <input type="text" id="searchCourse" class="form-control"
                                            placeholder="Enter course title...">
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                    <button class="btn btn-primary" id="searchBtn"><i class="fas fa-search"></i>
                                        Search</button>
                                    <button class="btn btn-secondary" id="clearBtn"><i class="fas fa-times-circle"></i>
                                        Clear</button>

                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (userHasPermission(COURSE_VIEW)) { ?>
                        <div class="row" id="courseList">
                        <?php } ?>
                    </div>

                
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(function () {

                /* ===============================
                   MODAL INIT (SAFE)
                ================================ */
                const modalEl = document.getElementById("courseModal");
                const modalcourse = modalEl ? new bootstrap.Modal(modalEl) : null;

                /* ===============================
                   LOAD COURSES
                ================================ */
                function loadcourses(search = "") {
                    $("#courseList").load(
                        "course_fetch.php?search=" + encodeURIComponent(search)
                    );
                }
                loadcourses();

                /* ===============================
                   SEARCH / CLEAR
                ================================ */
                $("#searchBtn").on("click", function () {
                    loadcourses($("#searchCourse").val().trim());
                });

                $("#clearBtn").on("click", function () {
                    $("#searchCourse").val("");
                    loadcourses();
                });

                /* ===============================
                   FILE NAME PREVIEW
                ================================ */
                const courseCover = document.getElementById("courseCover");
                if (courseCover) {
                    $("#courseCover").change(function () {
                        if (this.files.length > 0) {
                            $("#coverPathInput").val(this.files[0].name);
                        }
                    });
                }

                /* ===============================
                   SAVE / UPDATE COURSE
                ================================ */
                const saveBtn = document.getElementById("savecourseBtn");
                if (saveBtn) {
                    $("#savecourseBtn").on("click", function () {

                        const mode = this.getAttribute("data-mode") || "add";
                        const id = this.getAttribute("data-id") || "";

                        const title = $("#courseTitle").val().trim();
                        const details = $("#courseDetails").val().trim();

                        const coverFile = $("#courseCover")[0]?.files[0] || null;

                        if (!title || !details) {
                            showMessage("Course title and details are required", "error");
                            return;
                        }

                        if (mode === "add") {
                            if (!coverFile) {
                                showMessage("Please upload a course cover", "error");
                                return;
                            }
                        }

                        const formData = new FormData();
                        formData.append("title", title);
                        formData.append("details", details);
                        if (coverFile) formData.append("cover", coverFile);
                        if (mode === "update") formData.append("id", id);

                        const api =
                            mode === "add" ? "course_add.php" : "course_update.php";

                        fetch(api, {
                            method: "POST",
                            body: formData
                        })
                            .then(res => res.text())
                            .then(res => {
                                res = res.trim();
                                if (res === "success") {
                                    showMessage(
                                        mode === "add" ?
                                            "Course added successfully!" :
                                            "Course updated successfully!",
                                        "success"
                                    );
                                    modalcourse?.hide();
                                    loadcourses();
                                } else if (res === "duplicate") {
                                    showMessage("Course already exists!", "error");
                                } else {
                                    showMessage("Error occurred!", "error");
                                }
                            });
                    });
                }

                /* ===============================
                   ADD COURSE
                ================================ */
                const addBtn = document.getElementById("addcourseBtn");
                if (addBtn) {
                    $("#addcourseBtn").on("click", function () {
                        $(".course-modal-title").text("Add Course");
                        $("#courseModal input, #courseModal textarea, #courseModal select").val("");
                        $("#coverPreview").hide();
                        $("#coverPathInput").val("No file chosen");

                        $("#savecourseBtn")
                            .attr("data-mode", "add")
                            .removeAttr("data-id")
                            .text("Save");

                        modalcourse?.show();
                    });
                }
            });

            /* ===============================
               EDIT COURSE (GLOBAL)
            =============================== */
            function editcourse(
                id,
                title,
                details,
                cover

            ) {
                $("#courseTitle").val(title);
                $("#courseDetails").val(details);

                $(".course-modal-title").text("Edit Course");
                $("#savecourseBtn")
                    .attr({
                        "data-mode": "update",
                        "data-id": id
                    })
                    .text("Update");

                if (cover) {
                    const imagePath = "uploads/courses/course-" + id + "/" + cover;
                    $("#coverPathInput").val(imagePath);
                    $("#coverPreview").attr("src", imagePath).show();
                } else {
                    $("#coverPathInput").val("No file chosen");
                    $("#coverPreview").hide();
                }

                bootstrap.Modal.getOrCreateInstance(
                    document.getElementById("courseModal")
                ).show();
            }

            /* ===============================
               AJAX POST (DELETE)
            =============================== */
            function ajaxPost(url, data, callback) {
                fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: new URLSearchParams(data)
                })
                    .then(res => res.text())
                    .then(res => callback(res));
            }

            /* ===============================
               DELETE COURSE
            =============================== */
            let deleteID = null;

            function deleteCourse(id) {
                deleteID = id;
                const modalEl = document.getElementById("deleteConfirmModal");
                if (modalEl) {
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                }
            }

            function confirmDelete() {
                ajaxPost("course_delete.php", {
                    id: deleteID
                }, function (res) {
                    if (res.trim() === "success") {
                        showMessage("Course deleted successfully!", "success");
                        $("#courseList").load("course_fetch.php");
                    } else {
                        showMessage("Error deleting course", "error");
                    }
                });

                const modalEl = document.getElementById("deleteConfirmModal");
                if (modalEl) {
                    bootstrap.Modal.getInstance(modalEl)?.hide();
                }
            }
        </script>



</body>

</html>