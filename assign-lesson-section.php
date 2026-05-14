<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Assign Lesson to Section </title>
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
                        <h3 class="fw-bolder ">Assign Lesson to Section</h3>
                        <button class="btn btn-primary" id="assignBtn"><i class="fas fa-plus-circle"></i> Assign</button>
                    </div>

                    <!-- Filter Box -->
                    <div class="filter-box mb-4 ">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="input-group" style="gap:25px;">
                                    <span class="fw-semibold">Search Lesson:</span>
                                    <input type="text" class="form-control" placeholder="Enter lesson name...">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end" style="gap:10px;">
                                <button class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                                <button class="btn btn-secondary"><i class="fas fa-times-circle"></i> Clear</button>
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Rows -->
                    <div class="grade-box mb-3 ">
                        <div class="grade-row d-flex justify-content-between">
                            <div>
                                <strong>Lesson 1 - Introduction</strong><br>
                                Assigned to: Grade 2 - Section A
                            </div>
                            <div class="action-icons">
                                <i class="fas fa-edit text-primary"></i>
                                <i class="fas fa-eye"></i>
                                <i class="fas fa-trash text-danger"></i>
                            </div>
                        </div>
                    </div>

                    <div class="grade-box mb-3 ">
                        <div class="grade-row d-flex justify-content-between">
                            <div>
                                <strong>Lesson 2 - Basics</strong><br>
                                Assigned to: Grade 1 - Section B
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
                <div class="modal fade text-dark" id="assignModal" tabindex="-1" >
                    <div class="modal-dialog ">
                        <div class="modal-content">
                            
                            <div class="modal-header" style="background-color:#fd5f00; color:white;">
                                <h5 class="modal-title">Assign Lesson to Section</h5>
                                <button type="button" data-bs-dismiss="modal" class="closeicon">
                                    <i class="fas fa-times fs-4"></i>
                                </button>
                            </div>

                            <div class="modal-body">
                                <label class="form-label">Select Lesson:</label>
                                <select class="form-control mb-3">
                                    <option value="">Select</option>
                                    <option>Lesson 1 - Introduction</option>
                                    <option>Lesson 2 - Basics</option>
                                </select>

                                <label class="form-label">Select Section:</label>
                                <select class="form-control">
                                    <option value="">Select</option>
                                    <option>A</option>
                                    <option>B</option>
                                    <option>C</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary">Save</button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const modalAssign = new bootstrap.Modal(document.getElementById('assignModal'));
        document.getElementById('assignBtn').addEventListener('click', () => modalAssign.show());
    </script>

</body>

</html>
