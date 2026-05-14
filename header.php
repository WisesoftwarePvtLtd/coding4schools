<!DOCTYPE html>
<html lang="en">
<?php
if (session_status() === PHP_SESSION_NONE) {
  $timeout_duration = 7200;
  ini_set('session.gc_maxlifetime', $timeout_duration);
  session_set_cookie_params($timeout_duration);
  session_start();
}

require_once "standard_constants.php"; ?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
  <meta name="google" content="notranslate" />
  <title>Coding4schools</title>

  <!-- Fonts & Icons -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/common.js"></script>

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/magnific-popup.css">
  <link rel="stylesheet" href="css/jquery-ui.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/owl.theme.default.min.css">
  <link rel="stylesheet" href="js/jquery.select2/select2.css">
  <link rel="stylesheet" href="css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="js/jquery.fancybox/jquery.fancybox.css">
  <link rel="stylesheet" href="js/chartjs/Chart.min.css">
  <link rel="stylesheet" href="css/aos.css">

  <link rel="stylesheet" href="css/style_en.css">
  <link rel="stylesheet" href="css/globalStyle.css">

  <link href="css/main.css" rel="stylesheet" media="all">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="images/logo.png">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      line-height: 1.6;
      color: #333;
    }

    /* Loader */
    .CommonLoader {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(255, 255, 255, 0.95);
      z-index: 98;
      background-image: url("images/spinner.svg");
      background-position: center;
      background-repeat: no-repeat;
      background-size: 150px;
      animation: FadeIn 0.3s linear;
    }

    .CommonLoader2 {
      background-color: rgba(255, 255, 255, 1) !important;
      z-index: 99999999 !important;
      animation: FadeIn 0s linear !important;
    }

    @keyframes FadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    /* Header */
    .menu-desktop,
    .menu-desktop.scrolled,
    .menu-desktop.fixed {
      height: 80px !important;
    }

    .img1 a.logo {
      height: 80px !important;
    }

    .img1 img {
      height: 50px !important;
      max-height: 50px !important;
      width: auto !important;
      transform: none !important;
      transition: none !important;
    }

    .name span,
    .links li a {
      line-height: 1;
    }

    .links {
      display: flex;
      list-style: none;
      align-items: center;
      gap: 0.5rem;
      margin-top: 0;
    }

    .links li a {
      text-decoration: none;
      color: #333;
      /* left: 30px; */
      font-weight: 500;
      font-size: 16px;
      position: relative;
      transition: all 0.3s ease;
      padding: 10px 0;
    }

    .links li a:hover,
    .links li a.active {
      color: #137ff0;
    }

    .links li a::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 0%;

      transition: all 0.3s ease;
      transform: translateX(-50%);
    }

    .links li a:hover::after,
    .links li a.active::after {
      width: 100%;
    }

    /* ======================================
   DESKTOP COURSES MEGA MENU FIX
====================================== */

    /* Show main dropdown on hover */
    .menu-desktop li.dropdown:hover .dropdown_list {
      display: block !important;
    }

    /* Dropdown container */
    .dropdown_list {
      position: absolute;
      top: 100%;
      left: 0;
      width: 280px;
      background: #ffffff;
      border: 1px solid #dcdcdc;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      z-index: 9999;
    }

    /* LEFT SIDE BLOCKS */
    .dropdown_list>div {
      position: relative;
    }

    /* Course level item */
    .course_level {
      display: flex;
      align-items: center;
      justify-content: space-between;

      height: 68px;
      padding: 0 22px;

      background: #ffffff;
      border-bottom: 1px solid #e6e6e6;

      font-size: 15px;
      font-weight: 500;
      color: #000;
      cursor: pointer;
      text-decoration: none;

      transition: background 0.2s ease;
    }

    /* Arrow */
    .course_level::after {
      content: "▶";
      font-size: 11px;
      opacity: 0.7;
    }

    /* Hover */
    .course_level:hover {
      background: #f5f7fa;
    }

    /* ======================================
   RIGHT PANEL (SUB COURSES)
====================================== */

    .dropdown_sub {
      position: absolute;
      top: 0;
      left: 100%;
      width: 420px;
      min-height: 100%;

      background: #ffffff;
      border-left: 4px solid #0a3a8f;
      border-top: 1px solid #dcdcdc;
      border-right: 1px solid #dcdcdc;
      border-bottom: 1px solid #dcdcdc;

      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
      display: none;
    }

    /* Show sub menu on hover */
    .dropdown_list>div:hover>.dropdown_sub {
      display: block !important;
    }

    /* Sub items */
    .dropdown_sub a {
      display: block;
      padding: 14px 30px;

      font-size: 14px;
      font-weight: 500;
      color: #000;
      text-decoration: none;

      border-bottom: 1px solid #ededed;
      transition: background 0.2s ease, color 0.2s ease;
    }

    .dropdown_sub a:hover {
      background: #f5f7fa;
      color: #0a3a8f;
    }



    /* Mobile */
    .menu-mobile {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      background: #fff;
      z-index: 1000;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }


    .MobileMenuItem {
      display: block;
      padding: 20px;
      border-bottom: 1px solid #eee;
      color: #333;
      text-decoration: none;
      font-weight: 500;
      font-size: 16px;
    }

    .MobileMenuItem:hover {
      background: #f8f9fa;
      color: #137ff0;
    }


    @media (max-width: 768px) {
      .menu-desktop {
        display: none !important;
      }

      .menu-mobile {
        display: block !important;
      }

      .menu-mobile-container {
        padding: 15px;
        position: relative;
      }

      .logo-wrap-mobile {
        text-align: center;
        padding: 10px 0;
      }

      .logo-wrap-mobile img {
        height: 45px;
        width: auto;
      }

      .name span {
        line-height: 30px;
        font-size: 22px;
      }

      .main-nav-mobile-link {
        position: absolute;
        right: 15px;
        top: 25px;
        font-size: 24px;
        color: #333;
        padding: 10px;
        background: #fff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      }

      .menu-mobile-ul {
        list-style: none;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease;
        background: #fff;
        margin: 0;
      }

      .menu-mobile-ul.expanded {
        max-height: 5000px;
      }

      .mobile-courses-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease;
        background: #f8f9fa;
        margin: 10px;
        border-radius: 8px;
      }

      .mobile-courses-container.expanded {
        max-height: 3000px;
      }
    }

    /* ================================
   MOBILE COURSES – FINAL FIX
================================ */

    /* Mobile only */
    @media (max-width: 768px) {

      /* Courses container */
      #mobile-courses-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease;
        background: #f8f9fa;
        margin: 10px;
        border-radius: 8px;
        display: block;
        /* IMPORTANT */
      }

      /* OPEN */
      #mobile-courses-container.expanded {
        max-height: 5000px;
      }

      /* Level headers */
      #mobile-courses-container .level-item {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 16px 20px;
        font-size: 15px;
        font-weight: 600;

        background: #fff;
        border-bottom: 1px solid #e6e6e6;
        cursor: pointer;
      }

      /* Arrow */
      #mobile-courses-container .level-item::after {
        content: "▼";
        font-size: 12px;
      }

      /* Sub courses */
      #mobile-courses-container .sub-courses {
        display: none;
        background: #f3f4f6;
        margin: 0;
        padding: 0;
      }

      /* OPEN SUBMENU */
      #mobile-courses-container .sub-courses.visible {
        display: block;
      }

      #mobile-courses-container .sub-courses li {
        list-style: none;
      }

      #mobile-courses-container .sub-courses li a {
        display: block;
        padding: 14px 20px 14px 36px;
        font-size: 14px;
        color: #333;
        text-decoration: none;
        border-bottom: 1px solid #ddd;
        background: #f3f4f6;
      }

      #mobile-courses-container .sub-courses li a:hover {
        background: #eaf1ff;
        color: #137ff0;
      }
    }

    .logo-wrap-mobile {
      display: flex;
      align-items: center;
      gap: 12px;
      /* space between logo and text */
    }

    .menu-desktop li.dropdown {
      position: relative;
    }

    .menu-desktop li.dropdown .dropdown-menu {
      display: none;

      top: 100%;
      left: 0;
      background: #fff;
      min-width: 300px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      z-index: 9999;
    }

    .menu-desktop li.dropdown:hover .dropdown-menu {
      display: block;
    }

    .menu-desktop .dropdown-menu {
      max-height: 400px;
      overflow-y: auto;
    }
  </style>
</head>

<body>
  <div class="menu-marker"></div>

  <?php
  $current_page = basename($_SERVER['REQUEST_URI']);
  $loggedIn = isset($_SESSION['LoggedInUserRoles']) && !empty($_SESSION['LoggedInUserRoles']);
  $dashboardUrl = "login.php";
  $dashboardPage = "login.php";
  $loggedInUserName = $_SESSION['LoggedInUsername'] ?? '';
  $studentId = $_SESSION['LoggedInStudentId'];
  include "config.php";
  $stmt = $conn->prepare("
    SELECT 
        g.grade_name,
        g.academic_year,
        s.section_name,
        s.gender
    FROM section_students ss
    JOIN grades g ON g.grade_id = ss.grade_id
    JOIN sections s ON s.section_id = ss.section_id
    WHERE ss.student_id = ?
    LIMIT 1
");

  $stmt->bind_param("i", $studentId);
  $stmt->execute();
  $result = $stmt->get_result();

  $studentClass = $result->fetch_assoc();
  $stmt->close();



  if ($loggedIn && !empty($_SESSION['LoggedInUserRoles'])) {

    $roles = $_SESSION['LoggedInUserRoles'];
    $role = is_array($roles) ? reset($roles) : $roles;

    switch ($role) {
      case SUPERADMIN:
        $dashboardUrl = "dashboard_superadmin.php";
        $dashboardPage = "dashboard_superadmin.php";
        break;

      case SITEADMIN:
        $dashboardUrl = "dashboard_siteadmin.php";
        $dashboardPage = "dashboard_siteadmin.php";
        break;

      case SCHOOLADMIN:
        $dashboardUrl = "dashboard_schooladmin.php";
        $dashboardPage = "dashboard_schooladmin.php";
        break;

      case TEACHER:
        $dashboardUrl = "dashboard_teacher.php";
        $dashboardPage = "dashboard_teacher.php";
        break;

      case STUDENT:
        $dashboardUrl = "dashboard_student.php";
        $dashboardPage = "dashboard_student.php";
        break;
    }
  }

  $userSchoolId = $_SESSION['LoggedInSchoolId'] ?? '';

  $schoolName = "";
  $schoolLogo = "";

  if (!empty($userSchoolId)) {
    $stmt = $conn->prepare("SELECT school_name, school_profile_image FROM schools WHERE school_id = ?");
    $stmt->bind_param("i", $userSchoolId);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
      $schoolName = $row['school_name'];
      $schoolLogo = $row['school_profile_image']; // path stored in DB
    }
    $stmt->close();
  }
  ?>
  <nav class="menu menu-desktop">
    <div class="menu-sizer">
      <div class="img1">
        <a href="index.php" class="logo">
          <img src="images/logo.png" alt="Coding4Schools Logo">
          <div class="name">
            <span style="color:black;font-size: 22px;">Coding<b
                style="color:orange;font-size: 22px;">4</b>Schools</span>
          </div>
        </a>
        <?php if (!empty($schoolName)) { ?>
          <div
            style="display:flex; align-items:center; gap:10px; border-left:1px solid #ccc; padding-left:15px;margin-top: 5%;">

            <!-- School Logo -->
            <?php if (!empty($schoolLogo)) { ?>
              <img src="<?= htmlspecialchars($schoolLogo); ?>" alt="School Logo"
                style="  height:40px !important; width:40px !important; border-radius:50%; object-fit:cover; border:1px solid #ddd;">
            <?php } ?>

            <!-- School Name -->
            <span style="font-size:16px; font-weight:600; color:#333;">
              <?= htmlspecialchars($schoolName); ?>
            </span>

          </div>
        <?php } ?>
      </div>

      <ul class="links">
        <?php if ($current_page == 'login.php' || $current_page == 'register.php') { ?>

          <!-- Only show Home & Login on Login/Register pages -->
          <li>
            <a href="index.php"
              class="nav-link <?php echo ($current_page == '' || $current_page == 'index.php') ? 'active' : ''; ?>">Home</a>
          </li>
          <li class="users">
            <?php if (!$loggedIn) { ?>
              <!-- SHOW LOGIN -->
              <a href="login.php" class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">
                Login
              </a>
            <?php } else { ?>
              <!-- SHOW LOGOUT -->
              <a href="logout.php" class="nav-link <?php echo ($current_page == 'logout.php') ? 'active' : ''; ?>">
                Logout
              </a>
            <?php } ?>
          </li>

        <?php } elseif ($current_page == 'teacherdashboard.php') { ?>

          <!-- Teacher Dashboard Menu -->
          <li>
            <a href="index.php"
              class="nav-link <?php echo ($current_page == '' || $current_page == 'index.php') ? 'active' : ''; ?>">Home</a>
          </li>


        <?php } else { ?>
          <!-- Full Menu on Other Pages -->
          <!-- USER NAME -->
          <span class="nav-link fw-semibold text-dark" style="font-size: 18px;">
            <i class="fas fa-user-circle me-1"></i>

            Welcome, <?= htmlspecialchars($loggedInUserName); ?>

            <?php if ($studentClass): ?>
              <br>
              <small style="color:#555;">
                Grade: <?= htmlspecialchars($studentClass['grade_name']); ?> |
                Section: <?= htmlspecialchars($studentClass['section_name']); ?> -
                <?= htmlspecialchars($studentClass['gender']); ?>
              </small>
            <?php endif; ?>
          </span>

          <li>
            <a href="index.php"
              class="nav-link <?php echo ($current_page == '' || $current_page == 'index.php') ? 'active' : ''; ?>">Home</a>
          </li>

          <li class="dropdown">
            <a href="javascript:void(0)">
              Online IDE <span class="caret">▼</span>
              <span class="underline"></span>
            </a>

            <ul class="dropdown-menu">

              <!-- INTERNAL MONACO EDITORS -->
              <li><a class="p-2 " href="codey.php?course=Codey Rockey">Codey Rockey</a></li>
              <li><a class="p-2 " href="ai_for_juniors.php">AI for Juniors</a></li>

              <li><a class="p-2 " href="3d_drawing.php">3D Drawing</a></li>
              <li><a class="p-2 " href="python_editor.php">Python</a></li>
              <li><a class="p-2 " href="vr_programming.php">Virtual Reality Programming</a></li>
              <li><a class="p-2 " href="introduction_to_ai.php">Introduction to Artificial Intelligence</a></li>
              <li><a class="p-2 " href="introduction_to_javascript_gr8.php">Introduction to Javascript gr8</a></li>
              <li><a class="p-2 " href="introduction_to_data_analytics.php">Introduction to Data Analytics</a></li>
              <li><a class="p-2 " href="html_css.php">HTML & CSS</a></li>

              <li><a class="p-2 " href="game_dev_js.php">Game Development JS</a></li>
              <li><a class="p-2 " href="data_visualisation.php">Data Visualisation</a></li>



              <!-- EXTERNAL / IFRAME EDITORS -->
              <li><a class="p-2 " href="coding_logic.php">Introduction to Coding Logic</a></li>
              <li><a class="p-2 " href="javaP5.php">Java Processing p5 js</a></li>
              <li><a class="p-2 " href="makeymakey.php">Makey Makey</a></li>
              <li><a class="p-2 " href="microbitprog.php">Microbit Programming</a></li>
              <li><a class="p-2 " href="microbitJs.php">Microbit JavaScript</a></li>
              <li><a class="p-2 " href="cyberpi.php">CyberPi</a></li>
              <li><a class="p-2 " href="mbotneo.php">mBot Neo</a></li>
              <li><a class="p-2 " href="tinybit_ai.php">Tinybit AI Vision</a></li>
              <li><a class="p-2 " href="scratchJr.php">Scratch Jr</a></li>
              <li><a class="p-2 " href="scratch.php">Scratch</a></li>
              <li><a class="p-2 " href="spherobolt.php">Sphero Bolt</a></li>
              <li><a class="p-2 " href="mbot.php">mBot Robot Coding</a></li>
              <li><a class="p-2 " href="arduino.php">Arduino</a></li>
              <li><a class="p-2 " href="arduinogr12.php">Arduino GR12</a></li>


            </ul>
          </li>

          <?php if ($loggedIn) { ?>
            <li>
              <a href="<?= $dashboardUrl ?>" class="nav-link <?= ($current_page == $dashboardPage) ? 'active' : ''; ?>">
                Dashboard
              </a>
            </li>
          <?php } ?>

          <!-- <li><a href="contact.php" class="nav-link">Contact Us</a></li> -->
          <!-- <li>
            <a href="blog.php" class="nav-link <?php echo ($current_page == 'blog.php') ? 'active' : ''; ?>">Blog</a>
          </li> -->
          <li class="users">
            <?php if (!$loggedIn) { ?>
              <!-- SHOW LOGIN -->
              <a href="login.php" class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">
                Login
              </a>
            <?php } else { ?>
              <!-- SHOW LOGOUT -->
              <a href="logout.php" class="nav-link <?php echo ($current_page == 'logout.php') ? 'active' : ''; ?>">
                Logout
              </a>
            <?php } ?>
          </li>

        <?php } ?>

      </ul>
  </nav>


  <script>
    function toggleMenu(icon) {
      icon.classList.toggle('active');
      document.querySelector('.site-navbar .site-navigation ul').classList.toggle('active');
    }

    // Close menu when clicking outside
    // document.addEventListener('click', function (e) {
    //   const menu = document.querySelector('.site-navbar .site-navigation ul');
    //   const hamburger = document.querySelector('.hamburger');
    //   if (!menu.contains(e.target) && !hamburger.contains(e.target)) {
    //     menu.classList.remove('active');
    //     hamburger.classList.remove('active');
    //   }
    // });
    document.addEventListener('click', function (e) {
      const menu = document.querySelector('.site-navbar .site-navigation ul');
      const hamburger = document.querySelector('.hamburger');

      if (!menu || !hamburger) return; // 🔥 VERY IMPORTANT

      if (!menu.contains(e.target) && !hamburger.contains(e.target)) {
        menu.classList.remove('active');
        hamburger.classList.remove('active');
      }
    });

    // Close menu when a link is clicked
    document.querySelectorAll('.site-navbar .site-navigation ul li a').forEach(link => {
      link.addEventListener('click', () => {
        document.querySelector('.site-navbar .site-navigation ul').classList.remove('active');
        document.querySelector('.hamburger').classList.remove('active');
      });
    });
  </script>

  <script>
    // POST Request
    function ajaxPost(url, data, callback) {
      $.post(url, data, function (response) {
        callback(response.trim());
      });
    }

    // INSERT
    // function insertData(apiUrl, data, callbackMsg = "Inserted Successfully!", type = "success", callback = null) {
    //   ajaxPost(apiUrl, data, function (res) {
    //     res = res.trim();

    //     if (res === "duplicate") {
    //       showMessage("Grade already exists!", "error");
    //       return;
    //     }
    //     if (res === "success") {
    //       showMessage(callbackMsg, type);
    //       if (callback) callback(); // 🔥 AJAX success → run callback
    //     } else {
    //       showMessage("Error Occurred!", "error");
    //     }
    //   });
    // }
    function insertData(apiUrl, data, callbackMsg = "Inserted Successfully!", type = "success", callback = null) {
      ajaxPost(apiUrl, data, function (res) {
        res = res.trim();

        if (res === "duplicate") {
          showMessage("⚠️ Grade already exists!", "error");
          return;
        }

        if (res === "duplicate_grade_number") {
          showMessage("⚠️ This Grade Number is already used!", "error");
          return;
        }

        if (res === "success") {
          showMessage(callbackMsg, type);
          if (callback) callback();
        } else {
          showMessage("❌ " + res, "error"); // 🔥 actual error show karega
        }
      });
    }


    // UPDATE
    // function updateData(apiUrl, data, callbackMsg = "Updated Successfully!", type = "success", callback = null) {
    //   ajaxPost(apiUrl, data, function (res) {
    //     res = res.trim();

    //     if (res === "duplicate") {
    //       showMessage("Grade already exists!", "error");
    //       return;
    //     }
    //     if (res === "success") {
    //       showMessage(callbackMsg, type);
    //       if (callback) callback();  // 🔥 AJAX success → run callback
    //     } else {
    //       showMessage("Error Occurred!", "error");
    //     }
    //   });
    // }

    function updateData(apiUrl, data, callbackMsg = "Updated Successfully!", type = "success", callback = null) {
      ajaxPost(apiUrl, data, function (res) {
        res = res.trim();

        if (res === "duplicate") {
          showMessage("⚠️ Grade already exists!", "error");
          return;
        }

        if (res === "duplicate_grade_number") {
          showMessage("⚠️ This Grade Number is already used!", "error");
          return;
        }

        if (res === "success") {
          showMessage(callbackMsg, type);
          if (callback) callback();
        } else {
          showMessage("❌ " + res, "error"); // 🔥 actual error show karega
        }
      });
    }


  </script>

  <!-- DELETE CONFIRMATION MODAL -->
  <div class="modal fade text-dark" id="deleteConfirmModal" tabindex="-1" style="margin-top:117px">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- HEADER -->
        <div class="modal-header bg-primary">
          <h3 class="modal-title text-white">Confirm Delete</h3>
          <button type="button" data-bs-dismiss="modal" class="closeicon">
            <i class="fas fa-times fs-4"></i>
          </button>
        </div>

        <!-- BODY -->
        <div class="modal-body text-center">
          <p class="fs-5">Are you sure you want to delete this record?</p>
          <p>This action cannot be undone.</p>
        </div>

        <!-- FOOTER -->
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary" id="confirmDeleteBtn" onclick="confirmDelete()">Yes, Delete</button>
        </div>

      </div>
    </div>
  </div>


  <!-- COMMON DELETE CONFIRM MODAL -->
  <div class="modal fade" id="commonDeleteModal" tabindex="-1" style="margin-top:117px">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-header bg-primary text-white">
          <h3 class="modal-title">Confirm Delete</h3>
          <button type="button" data-bs-dismiss="modal" class="closeicon">
            <i class="fas fa-times fs-4"></i>
          </button>
        </div>

        <div class="modal-body text-center">
          <p id="deleteMessage" class="fs-5">Are you sure you want to delete this item?</p>
          <p>This action cannot be undone.</p>
          <input type="hidden" id="deleteTargetUrl">
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary" id="commonDeleteBtn">Yes, Delete</button>
        </div>

      </div>
    </div>
  </div>
  <script>
    function openDeleteModal(deleteUrl, message = "Are you sure you want to delete this item?") {
      document.getElementById("deleteTargetUrl").value = deleteUrl;
      document.getElementById("deleteMessage").innerText = message;

      const modal = new bootstrap.Modal(document.getElementById('commonDeleteModal'));
      modal.show();
    }

    document.getElementById("commonDeleteBtn").addEventListener("click", function () {
      let url = document.getElementById("deleteTargetUrl").value;
      window.location.href = url;
    });
  </script>

  <script>
    function showMessage(message, type = "success") {
      const box = document.getElementById("globalMsg");

      const colors = {
        success: "#28a745", // Green
        error: "#dc3545"    // Red
      };

      box.style.background = colors[type] || "#17a2b8"; // default = blue
      box.innerHTML = message;
      box.style.display = "block";

      setTimeout(() => {
        box.style.display = "none";
      }, 2000);
    }

    function togglePassword(inputId, toggleSpan) {
      const input = document.getElementById(inputId);
      if (!input) return;

      const type = input.getAttribute("type") === "password" ? "text" : "password";
      input.setAttribute("type", type);

      // Change icon
      toggleSpan.innerHTML = type === "password"
        ? '<i class="fas fa-eye-slash text-white"></i>'
        : '<i class="fas fa-eye text-white"></i>';
    }


  </script>
</body>
<?php
// function userHasMenuPermission($menuId) {

//     if (!isset($_SESSION['UserMenus'])) {
//         return false;
//     }

//     // if menu id exists in allowed menus
//     return array_key_exists($menuId, $_SESSION['UserMenus']);
// }

function userHasPermission($permissionId)
{

  if (!isset($_SESSION['UserPermissions'])) {
    return false;
  }

  return array_key_exists($permissionId, $_SESSION['UserPermissions']);
}
?>