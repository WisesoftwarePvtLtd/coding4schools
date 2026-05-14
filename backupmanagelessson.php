<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Lesson </title>
</head>

<body>
    <div class="container-fluid" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php'; ?>
            </div>

            <!-- Main Content -->
            <div class="main-area text-dark">
                <div class="container-fluid p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="fw-bolder ">Manage Lesson</h3>
                        <button class="btn btn-primary" id="addLessonBtn"><i class="fas fa-plus-circle"></i> Add
                            Lesson</button>
                    </div>

                    <!-- Filter Box -->
                    <div class="filter-box mb-4 ">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="input-group" style="gap:25px;">
                                    <span class="fw-semibold">Search Lesson</span>
                                    <input type="text" class="form-control" placeholder="Enter lesson name...">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                <button class="btn btn-secondary"><i class="fas fa-times-circle"></i> Clear</button>
                            </div>
                        </div>
                    </div>

                    <!-- Lesson Row -->
                    <div class="grade-box mb-3 ">
                        <div class="grade-row mb-3">
                            <div>
                                <strong>Lesson 1: Introduction</strong><br>
                            </div>
                            <div class="action-icons">
                                <i class="fas fa-edit text-primary"></i>
                                <i class="fas fa-eye"></i>
                                <i class="fas fa-trash text-danger"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grade-box mb-3 ">
                        <div class="grade-row">
                            <div>
                                <strong>Lesson 2: Basics</strong><br>
                            </div>
                            <div class="action-icons">
                                <i class="fas fa-edit text-primary"></i>
                                <i class="fas fa-eye"></i>
                                <i class="fas fa-trash text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade text-dark" id="lessonModal" tabindex="-1" >
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color:#fd5f00; color:white;">
                                <h5 class="modal-title">Add Lesson</h5>
                                <button type="button" data-bs-dismiss="modal"
                                    class="closeicon">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <a href="lesson_add.php" class="btn btn-primary">
                                    <i class="fas fa-plus-circle"></i> Add Lesson
                                </a>


                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const modalLesson = new bootstrap.Modal(document.getElementById('lessonModal'));
        document.getElementById('addLessonBtn').addEventListener('click', () => modalLesson.show());
    </script>
    <script>
        document.getElementById('addLessonBtn').addEventListener('click', () => {
            window.location.href = "lesson_add.php";
        });
    </script>


</body>

</html>