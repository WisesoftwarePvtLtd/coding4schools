<?php
include "config.php";

$courses = [
    'Primary' => [],
    'Middle School' => [],
    'High School' => []
];

$sql = "SELECT course_title, level, course_link FROM courses WHERE course_link IS NOT NULL";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("DB Error: " . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {

    $rawLevel = strtolower(trim($row['level']));

    // 🔁 LEVEL MAPPING (THIS IS THE KEY FIX)
    if (in_array($rawLevel, ['primary', 'junior'])) {
        $courses['Primary'][] = $row;
    } elseif (in_array($rawLevel, ['middle', 'middle school'])) {
        $courses['Middle School'][] = $row;
    } elseif (in_array($rawLevel, ['high', 'senior', 'high school'])) {
        $courses['High School'][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Coding4Schools</title>
        <!-- ALL FAVICONS EXACT -->
<link rel="icon" type="image/png" sizes="32x32" href="images/logo.png">

    <!-- ALL ORIGINAL META TAGS, ANALYTICS, FAVICONS EXACTLY PRESERVED -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JH49K7C941"></script>
     <script src="js/common.js"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-JH49K7C941');
    </script>
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-TD552ZH');
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="theme-color" content="#000">
    <meta name="msapplication-navbutton-color" content="#000">
    <meta name="apple-mobile-web-app-status-bar-style" content="#000">
    <meta name="robots" content="all, index, follow">
    <meta name="keywords" content="STEM education,online coding for kids,coding websites for kids,best coding websites,best coding games for kids">
    <meta name="description" content="Discover a whole new way of learning and problem solving with cutting edge technology and a leading STEM certified curriculum">
    <link href="index.php" rel="canonical">




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
         /* .menu-desktop { height: 80px !important; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: relative; z-index: 100; }
        .img1 a.logo { height: 80px !important; display: flex; align-items: center; }
        .img1 img { height: 50px !important; max-height: 50px !important; width: auto !important; }
        .menu-sizer { display: flex; align-items: center; justify-content: space-between; padding: 0 5%; max-width: 1400px; margin: 0 auto; height: 80px; }
        .links { display: flex; list-style: none; align-items: center; gap: 2rem; }
        .links li a { text-decoration: none; color: #333; font-weight: 500; font-size: 16px; padding: 10px 0; position: relative; transition: color 0.3s; }
        .links li a:hover { color: #137ff0; }
        .links li a::after { content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 50%; background: #137ff0; transition: all 0.3s; transform: translateX(-50%); }
        .links li a:hover::after { width: 100%; }*/
        /* COURSES DROPDOWN - ULTRA SIMPLE 
        .courses-dropdown { 
            position: absolute; top: 100%; left: 0; width: 450px; background: #fff; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-radius: 8px; 
            display: none; z-index: 9999; border: 1px solid #eee; overflow: hidden;
        }
        .courses-dropdown.visible { display: block !important; }

        .level-item { 
            display: block; padding: 18px 20px; cursor: pointer; border-bottom: 1px solid #f0f0f0; 
            color: #333; font-weight: 500; text-decoration: none; position: relative;
            transition: all 0.3s ease;
        }
        .level-item:hover { background: #f8f9ff; color: #137ff0; }
        .level-item.active { background: #137ff0; color: white; }

        .sub-courses { display: none; background: #fafafa; padding-left: 0; margin: 0; }
        .sub-courses.visible { display: block !important; }
        .sub-courses li { list-style: none; }
        .sub-courses li a { display: block; padding: 15px 25px 15px 45px; color: #555; text-decoration: none; border-bottom: 1px solid #eee; font-size: 14px; transition: all 0.3s; }
        .sub-courses li a:hover { background: #f0f8ff; color: #137ff0; }

        

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
    </style>

    <link href="css/main.css" rel="stylesheet" media="all">
    <link href="css/course_module.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="menu-marker"></div>

    <?php
    $current_page = basename($_SERVER['PHP_SELF']);
    $loggedIn = isset($_SESSION['LoggedInUserRoles']) && !empty($_SESSION['LoggedInUserRoles']);
    ?>

    <!-- DESKTOP MENU -->
    <nav class="menu menu-desktop">
        <div class="menu-sizer">
            <div class="img1">
                <a href="index.php" class="logo">
                    <img src="images/logo.png" alt="Coding4Schools Logo">
                    <div class="name">
                        <span style="color:black;font-size: 22px;">Coding<b style="color:orange;font-size: 22px;">4</b>Schools</span>
                    </div>
                </a>
            </div>
            <ul class="links">
                <?php if ($current_page === 'login.php') { ?>
                    <li><a href="index.php" class="<?php echo ($current_page === 'index.php') ? 'active' : ''; ?>">Home</a></li>
                    <li class="users"><a href="login.php">Login</a></li>
                <?php } else { ?>

                    <li><a href="index.php" class="<?php echo ($current_page === 'index.php') ? 'active' : ''; ?>">Home</a></li>
                    <li><a href="about.php">About Us<span class="underline"></span></a></li>
                    <li class="dropdown">
                        <a id="course_dropdpwn" class="dropdown_menu " href="javascript:;">Courses
                            <img src="https://www.codingfirst.org/images/arrow-down.svg" />
                            <span class="underline"></span>
                        </a>
                        <div class="dropdown_list">
                            <div>
                                <a data-attr="1" class="course_level" href="javascript:;"><span>Primary</span>
                                    <i class="fas fa-caret-right"></i>
                                </a>
                                <ul class="dropdown_sub">
                                    <?php if (!empty($courses['Primary'])) { ?>
                                        <?php foreach ($courses['Primary'] as $course) { ?>
                                            <li>
                                                <a href="<?= htmlspecialchars($course['course_link']) ?>">
                                                    <?= htmlspecialchars($course['course_title']) ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <li style="padding:12px;color:#999;">No courses available</li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div>
                                <a data-attr="2" class="course_level" href="javascript:;"><span>Middle School</span>
                                    <i class="fas fa-caret-right"></i>
                                </a>
                                <ul class="dropdown_sub">
                                    <?php if (!empty($courses['Middle School'])) { ?>
                                        <?php foreach ($courses['Middle School'] as $course) { ?>
                                            <li>
                                                <a href="<?= htmlspecialchars($course['course_link']) ?>">
                                                    <?= htmlspecialchars($course['course_title']) ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <li style="padding:12px;color:#999;">No courses available</li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div>
                                <a data-attr="3" class="course_level" href="javascript:;"><span>High School</span>
                                    <i class="fas fa-caret-right"></i>
                                </a>
                                <ul class="dropdown_sub">
                                    <?php if (!empty($courses['High School'])) { ?>
                                        <?php foreach ($courses['High School'] as $course) { ?>
                                            <li>
                                                <a href="<?= htmlspecialchars($course['course_link']) ?>">
                                                    <?= htmlspecialchars($course['course_title']) ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <li style="padding:12px;color:#999;">No courses available</li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li><a href="school.php">Schools<span class="underline"></span></a></li>
                    <li><a href="blog.php">Blog<span class="underline"></span></a></li>
                    <li><a href="javascript:void(0)" id="contact-hover">Contact Us<span class="underline"></span></a></li>
                    <li class="users"><a href="login.php"><span>Login</span></a></li>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <!-- MOBILE MENU -->
    <nav class="menu-mobile">
        <div class="menu-mobile-container">
            <div class="logo-wrap-mobile">
                <a href="index.php"><img src="images/logo.png" alt="Coding4Schools Logo">
                </a>
                <div class="name">
                    <span>Coding<b style="color:orange;font-size: 22px;">4</b>Schools</span>
                </div>
            </div>
            <a href="javascript:void(0)" class="main-nav-mobile-link" onclick="toggleMobileMenu()">
                <i class="fas fa-bars"></i>
            </a>
            <ul class="menu-mobile-ul" id="mobile-menu">
                <?php if ($current_page === 'login.php') { ?>
                    <li><a href="index.php" class="MobileMenuItem">Home</a></li>
                    <li><a href="login.php" class="MobileMenuItem">Login</a></li>
                <?php } else { ?>
                <!-- NORMAL MOBILE MENU -->
                    <li><a href="index.php" class="MobileMenuItem">Home</a></li>
                    <li><a href="about.php" class="MobileMenuItem">About Us</a></li>

                    <li class="MobileMenuItem" onclick="toggleMobileCourses()" style="cursor:pointer;">
                        Courses <i class="fas fa-chevron-down"></i>
                    </li>
                    <div id="mobile-courses-container" class="mobile-courses-container">

                        <!-- PRIMARY -->
                        <a href="javascript:void(0)"
                            class="MobileMenuItem level-item"
                            onclick="toggleMobileSubmenu('primary')">
                            Primary
                        </a>

                        <ul id="mobile-primary" class="sub-courses">
                            <?php if (!empty($courses['Primary'])) { ?>
                                <?php foreach ($courses['Primary'] as $course) { ?>
                                    <li>
                                        <a href="<?= htmlspecialchars($course['course_link']) ?>">
                                            <?= htmlspecialchars($course['course_title']) ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } else { ?>
                                <li style="padding:12px;color:#999;">No courses available</li>
                            <?php } ?>
                        </ul>

                        <!-- MIDDLE SCHOOL -->
                        <a href="javascript:void(0)"
                            class="MobileMenuItem level-item"
                            onclick="toggleMobileSubmenu('middle')">
                            Middle School
                        </a>

                        <ul id="mobile-middle" class="sub-courses">
                            <?php if (!empty($courses['Middle School'])) { ?>
                                <?php foreach ($courses['Middle School'] as $course) { ?>
                                    <li>
                                        <a href="<?= htmlspecialchars($course['course_link']) ?>">
                                            <?= htmlspecialchars($course['course_title']) ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } else { ?>
                                <li style="padding:12px;color:#999;">No courses available</li>
                            <?php } ?>
                        </ul>

                        <!-- HIGH SCHOOL -->
                        <a href="javascript:void(0)"
                            class="MobileMenuItem level-item"
                            onclick="toggleMobileSubmenu('high')">
                            High School
                        </a>

                        <ul id="mobile-high" class="sub-courses">
                            <?php if (!empty($courses['High School'])) { ?>
                                <?php foreach ($courses['High School'] as $course) { ?>
                                    <li>
                                        <a href="<?= htmlspecialchars($course['course_link']) ?>">
                                            <?= htmlspecialchars($course['course_title']) ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } else { ?>
                                <li style="padding:12px;color:#999;">No courses available</li>
                            <?php } ?>
                        </ul>

                    </div>
                    <li><a href="school.php" class="MobileMenuItem">Schools</a></li>
                    <li><a href="blog.php" class="MobileMenuItem">Blog</a></li>
                    <li><a href="contact.php" class="MobileMenuItem">Contact Us</a></li>

                    <?php if (!$loggedIn) { ?>
                        <li><a href="login.php" class="MobileMenuItem">Login</a></li>
                    <?php } ?>

                <?php } ?>

            </ul>
        </div>
    </nav>

    <!-- INLINE FUNCTIONS - NO DOMContentLoaded NEEDED -->
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const contact = document.getElementById("contact-hover");
            const footer = document.getElementById("site-footer");

            if (!contact || !footer) return;

            let timer = null;

            contact.addEventListener("mouseenter", function() {
                // Require intentional hover (not pass-by)
                timer = setTimeout(() => {
                    footer.scrollIntoView({
                        behavior: "smooth",
                        block: "start"
                    });
                }, 450); // 👈 key fix (feels natural)
            });

            contact.addEventListener("mouseleave", function() {
                clearTimeout(timer);
                timer = null;
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/require.js/require.js"></script>
    <script src="js/functions.js"></script>
</body>

</html>