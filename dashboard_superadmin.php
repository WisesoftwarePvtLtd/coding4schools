<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard </title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>

<?php include 'header.php'; ?>

<body>
    <div class="container-fluid p-3" style="padding: 30px;">
        <div class="layout-row">

            <!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <?php include 'menus.php';?>
            </div>

            <!-- Main Area -->
            <div class="main-area text-dark">
                <!-- Dashboard -->
                <div id="dashboardWrapper">
                    <div class="flex-row">
                        <div class="main-content" style="flex: 3;">
                            <div class="row g-4 mb-3">
                                <div class="col-md-4">
                                    <div class="info-card bg-blue">
                                        <div>
                                            <h5>Number of Students</h5>
                                            <h2>0</h2>
                                        </div>
                                        <i class="bi bi-people fs-2"></i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-card bg-purple">
                                        <div>
                                            <h5>Number of Sections</h5>
                                            <h2>8</h2>
                                        </div>
                                        <i class="bi bi-diagram-3 fs-2"></i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="info-card bg-orange">
                                        <div>
                                            <h5>Number of Quizzes</h5>
                                            <h2>5</h2>
                                        </div>
                                        <i class="bi bi-question-circle fs-2"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="data-card">
                                        <h5>Student Success Rate</h5>
                                        <p>Percentage of students who got more than 70%</p>
                                        <div class="chart-legend">
                                            <span class="color-box" style="background:#10b981"></span> Grade 1
                                            <span class="color-box" style="background:#8b5cf6"></span> Grade 2
                                            <span class="color-box" style="background:#f97316"></span> Grade 3
                                            <span class="color-box" style="background:#ef4444"></span> Grade 4
                                        </div>
                                        <canvas id="successChart"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="data-card">
                                        <h5>Teacher's Grade Average</h5>
                                        <p class="text-center"><span class="color-box"
                                                style="background:#06b6d4"></span> Grades Averages</p>
                                        <canvas id="gradeChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="data-card">
                                <h5>Grade Average Per Course</h5>
                                <div class="chart-legend text-center px-4 py-2">
                                    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
                                        <div><span class="color-box"
                                                style="background:#8b5cf6; margin-left: 8px;"></span>Easy Steps to
                                            Chinese for Kids 1a</div>
                                        <div><span class="color-box"
                                                style="background:#f97316; margin-left: 8px;"></span>Easy Steps to
                                            Chinese for Kids 1b</div>
                                        <div><span class="color-box"
                                                style="background:#ef4444; margin-left: 8px;"></span>Easy Steps to
                                            Chinese for Kids 2a</div>
                                        <div><span class="color-box"
                                                style="background:#10b981; margin-left: 8px;"></span>Easy Steps to
                                            Chinese for Kids 2b</div>
                                        <div><span class="color-box"
                                                style="background:#06b6d4; margin-left: 8px;"></span>Easy Steps to
                                            Chinese for Kids 3a</div>
                                    </div>
                                </div>
                                <canvas id="courseChart"></canvas>
                            </div>

                            <div class="data-card">
                                <h5>School's Grade Average Per Gender</h5>
                                <div class="gender-legend text-center">
                                    <span class="color-box" style="background-color: #2f1ee6ff;"></span> Boy
                                    <span class="color-box" style="background-color: #e8ade4ff;"></span> Girl
                                </div>
                                <canvas id="genderChart"></canvas>
                            </div>

                        </div>

                        <div class="right-content" style="flex: 1;">
                            <h5>Additional Info</h5>
                            <ul>
                                <li>📊 Total Teachers: 12</li>
                                <li>🎯 Active Courses: 18</li>
                                <li>⏰ Upcoming Quizzes: 4</li>
                                <li>📝 New Assignments: 3</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Chart 1
        new Chart(document.getElementById('successChart'), {
            type: 'bar',
            data: {
                labels: ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4'],
                datasets: [{
                    data: [80, 65, 72, 90],
                    backgroundColor: ['#10b981', '#8b5cf6', '#f97316', '#ef4444'],
                    borderRadius: 6
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Chart 2
        new Chart(document.getElementById('gradeChart'), {
            type: 'line',
            data: {
                labels: ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4'],
                datasets: [{
                    data: [2.5, 3.1, 3.5, 4.0],
                    borderColor: '#06b6d4',
                    backgroundColor: '#06b6d4',
                    fill: false,
                    tension: 0.3
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Chart 3
        new Chart(document.getElementById('courseChart'), {
            type: 'bar',
            data: {
                labels: ['Kids 1a', 'Kids 1b', 'Kids 2a', 'Kids 2b', 'Kids 3a'],
                datasets: [{
                    label: 'Average Score',
                    data: [70, 80, 75, 85, 90],
                    backgroundColor: ['#8b5cf6', '#f97316', '#ef4444', '#10b981', '#06b6d4'],
                    borderRadius: 8
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Chart 4
        new Chart(document.getElementById('genderChart'), {
            type: 'bar',
            data: {
                labels: ['boy', 'girl'],
                datasets: [{
                    label: 'Average Score',
                    data: [70, 80],
                    backgroundColor: ['#2f1ee6ff', '#e8ade4ff'],
                    borderRadius: 8
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
     <script>
        function toggleCoursesDropdown() {
            var dropdown = document.getElementById('courses-dropdown');
            dropdown.classList.toggle('visible');
            // Close all submenus
            document.querySelectorAll('.sub-courses').forEach(function(el) {
                el.classList.remove('visible');
            });
            document.querySelectorAll('.level-item').forEach(function(el) {
                el.classList.remove('active');
            });
        }

        function toggleSubmenu(type) {
            // Close all submenus first
            document.querySelectorAll('.sub-courses').forEach(function(el) {
                el.classList.remove('visible');
            });
            document.querySelectorAll('.level-item').forEach(function(el) {
                el.classList.remove('active');
            });

            // Toggle specific submenu
            var submenu = document.getElementById(type + '-courses');
            var trigger = event.target;
            submenu.classList.toggle('visible');
            trigger.classList.toggle('active');
            event.stopPropagation();
        }


        function toggleMobileMenu() {
            var menu = document.getElementById('mobile-menu');
            var icon = event.target.closest('.main-nav-mobile-link').querySelector('i');
            menu.classList.toggle('expanded');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
            // Close courses container
            document.getElementById('mobile-courses-container').classList.remove('expanded');
        }

        function toggleMobileCourses() {
            document.getElementById('mobile-courses-container').classList.toggle('expanded');
            // Close all mobile submenus
            document.querySelectorAll('.sub-courses').forEach(function(el) {
                el.classList.remove('visible');
            });
            document.querySelectorAll('.level-item').forEach(function(el) {
                el.classList.remove('active');
            });
        }

        function toggleMobileSubmenu(type) {
            document.querySelectorAll('#mobile-courses-container .sub-courses')
                .forEach(function(el) {
                    el.classList.remove('visible');
                });


            document.querySelectorAll('.level-item').forEach(function(el) {
                el.classList.remove('active');
            });

            // Toggle specific submenu
            var submenu = document.getElementById('mobile-' + type);
            var trigger = event.target;
            submenu.classList.toggle('visible');
            trigger.classList.toggle('active');
        }

        // Close everything when clicking outside
        document.onclick = function(event) {
            if (!event.target.closest('.menu-desktop') && !event.target.closest('.menu-mobile')) {
                document.getElementById('courses-dropdown').classList.remove('visible');
                document.getElementById('mobile-menu').classList.remove('expanded');
                document.getElementById('mobile-courses-container').classList.remove('expanded');
                document.querySelectorAll('.sub-courses').forEach(function(el) {
                    el.classList.remove('visible');
                });
                document.querySelectorAll('.level-item').forEach(function(el) {
                    el.classList.remove('active');
                });
                var icon = document.querySelector('.main-nav-mobile-link i');
                if (icon) {
                    icon.classList.add('fa-bars');
                    icon.classList.remove('fa-times');
                }
            }
        }
    </script>


</body>

</html>