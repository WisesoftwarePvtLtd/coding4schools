-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 14, 2026 at 04:22 AM
-- Server version: 10.11.14-MariaDB-0ubuntu0.24.04.1
-- PHP Version: 8.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uat_coding4schools`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL,
  `asset_name` varchar(255) NOT NULL,
  `asset_file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course_title` varchar(255) NOT NULL,
  `course_cover_page` varchar(255) DEFAULT NULL,
  `course_details` text DEFAULT NULL,
  `course_sort_order` int(11) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `grades` varchar(50) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `duration_type` varchar(25) DEFAULT NULL,
  `timeframe` varchar(50) DEFAULT NULL,
  `prerequisite` varchar(255) DEFAULT NULL,
  `course_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_code`
--

CREATE TABLE `course_code` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exercise_id` int(11) DEFAULT NULL,
  `course_name` varchar(100) DEFAULT NULL,
  `editor_type` varchar(50) DEFAULT NULL,
  `code` longtext DEFAULT NULL,
  `project_ref` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_overview`
--

CREATE TABLE `course_overview` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `certification_available` tinyint(1) DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_projects`
--

CREATE TABLE `course_projects` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drag_options`
--

CREATE TABLE `drag_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `drag_id` varchar(100) DEFAULT NULL,
  `drag_label` varchar(255) DEFAULT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `editors`
--

CREATE TABLE `editors` (
  `editor_id` int(11) NOT NULL,
  `editor_name` varchar(100) NOT NULL,
  `editor_language` varchar(50) DEFAULT NULL,
  `editor_type` varchar(50) DEFAULT NULL,
  `editor_save_code` int(11) DEFAULT NULL,
  `editor_url` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `editors`
--

INSERT INTO `editors` (`editor_id`, `editor_name`, `editor_language`, `editor_type`, `editor_save_code`, `editor_url`, `created_at`) VALUES
(5, 'Scratch Jr', NULL, NULL, 0, 'https://codejr.org/scratchjr/index.html', '2026-01-18 23:33:53'),
(6, 'Codey Rockey', NULL, NULL, 0, 'https://ide.mblock.cc/', '2026-01-19 02:17:34'),
(16, 'HTML CSS JS', NULL, NULL, 1, 'editors/htmlcss_editor.php', '2026-01-30 09:08:11'),
(17, 'Python', NULL, NULL, 1, 'editors/python_code_editor.php', '2026-02-02 08:16:47'),
(18, 'Virtual Reality Programming', NULL, NULL, 1, 'editors/virtual_editor.php', '2026-02-02 08:19:08'),
(20, 'Introduction to JavaScript gr8', NULL, NULL, 1, 'editors/introduction_to_javascript_gr8_editor.php', '2026-02-10 09:12:10'),
(21, 'Java Processing p5 js', 'javascript', 'monaco', 1, 'editors/javaP5_editor.php', '2026-02-10 09:14:17'),
(22, 'Game Development in JavaScript', NULL, NULL, 1, 'editors/game_dev_js_editor.php', '2026-02-10 09:15:52'),
(23, '3D Drawing', 'javascript', 'monaco', 0, 'editors/3d_drawing_editor.php', '2026-02-10 13:54:33'),
(24, 'Introduction to Data Analytics', NULL, 'trinket', 1, 'editors/introduction_to_data_analytics_editor.php', '2026-02-11 05:20:05'),
(25, 'AI for Juniors', 'python', 'monaco', 0, 'editors/ai_for_juniors_editor.php', '2026-02-11 05:37:43'),
(26, 'Data Visualisation', NULL, 'trinket', 1, 'editors/data_visualisation_editor.php', '2026-02-11 05:39:40'),
(27, 'Microbit Programming', NULL, NULL, 0, 'editors/microbitprog_editor.php', '2026-02-11 05:44:29'),
(28, 'Microbit JavaScript', NULL, NULL, 0, 'editors/microbitJs_editor.php', '2026-02-11 05:44:47'),
(29, 'CyberPi', NULL, NULL, 0, 'editors/cyberpi_editor.php', '2026-02-11 05:45:04'),
(30, 'mBot Neo', NULL, NULL, 0, 'editors/mbotneo_editor.php', '2026-02-11 05:45:24'),
(31, 'Tinybit AI Vision', NULL, NULL, 0, 'editors/tinybit_ai_editor.php', '2026-02-11 05:45:40'),
(32, 'Scratch', NULL, NULL, 1, 'editors/scratch_editor.php', '2026-02-11 05:46:44'),
(33, 'Sphero Bolt', NULL, NULL, 0, 'editors/spherobolt_editor.php', '2026-02-11 05:47:02'),
(34, 'mBot Robot Coding', NULL, NULL, 0, 'editors/mbot_editor.php', '2026-02-11 05:47:17'),
(35, 'Arduino', NULL, NULL, 1, 'editors/arduino_editor.php', '2026-02-11 05:47:33'),
(36, 'Introduction to Coding Logic', NULL, NULL, 0, 'editors/coding_logic_editor.php', '2026-02-11 05:48:12'),
(37, 'Introduction to Artificial Intelligence', NULL, NULL, 0, 'editors/introduction_to_ai_editor.php', '2026-02-11 05:49:19'),
(38, 'Makey Makey', NULL, NULL, 1, 'editors/makeymakey_editor.php', '2026-02-11 06:07:17'),
(39, 'Arduino GR12', NULL, 'arduinogr12', 1, 'https://app.arduino.cc/', '2026-04-13 04:36:13');

-- --------------------------------------------------------

--
-- Table structure for table `exercise`
--

CREATE TABLE `exercise` (
  `exercise_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `instructions` text NOT NULL,
  `instruction_image` varchar(255) DEFAULT NULL,
  `hint` text DEFAULT NULL,
  `hint_image` varchar(255) DEFAULT NULL,
  `editor_key` varchar(50) NOT NULL,
  `editor_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE `exercises` (
  `exercise_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `editor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `exercise_name` varchar(255) NOT NULL,
  `exercise_discription` varchar(255) DEFAULT NULL,
  `sprite_image` varchar(255) DEFAULT NULL,
  `instruction_guideline` text DEFAULT NULL,
  `exercise_sort_order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_games`
--

CREATE TABLE `exercise_games` (
  `game_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `game_url` text NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_instruction_hints`
--

CREATE TABLE `exercise_instruction_hints` (
  `id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `instruction_text` text DEFAULT NULL,
  `instruction_sort_order` int(11) DEFAULT NULL,
  `instruction_image` varchar(255) DEFAULT NULL,
  `hint_text` text DEFAULT NULL,
  `hint_image` varchar(255) DEFAULT NULL,
  `sprite_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `grade_name` varchar(50) NOT NULL,
  `grade_number` int(11) DEFAULT NULL,
  `academic_year` varchar(50) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `lesson_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lesson_title` varchar(255) NOT NULL,
  `lesson_guideline` text DEFAULT NULL,
  `lesson_guideline_image` varchar(255) DEFAULT NULL,
  `lesson_file_path` varchar(255) DEFAULT NULL,
  `lesson_plan_path` varchar(255) DEFAULT NULL,
  `lesson_summary_path` varchar(255) DEFAULT NULL,
  `lesson_video_path` varchar(255) DEFAULT NULL,
  `lesson_type` varchar(100) DEFAULT NULL COMMENT '(Syllabus/lesson/cultural)',
  `lesson_sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `teacher_game_file` varchar(255) DEFAULT NULL,
  `student_game_file` varchar(255) DEFAULT NULL,
  `editor_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_audio`
--

CREATE TABLE `lesson_audio` (
  `lesson_audio_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `audio_file_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_practices`
--

CREATE TABLE `lesson_practices` (
  `lesson_practice_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `match_items`
--

CREATE TABLE `match_items` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `item_key` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `correct_word` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `match_line_pairs`
--

CREATE TABLE `match_line_pairs` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `left_label` varchar(255) DEFAULT NULL,
  `left_image` varchar(255) DEFAULT NULL,
  `right_label` varchar(255) DEFAULT NULL,
  `right_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `menu_id` int(11) NOT NULL,
  `menu` varchar(100) NOT NULL,
  `menu_icon` varchar(200) DEFAULT NULL,
  `menu_link_path` varchar(200) DEFAULT NULL,
  `menu_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `menu`, `menu_icon`, `menu_link_path`, `menu_order`) VALUES
(16, 'Manage Grades', 'fas fa-layer-group', 'manage-grade.php', 3),
(17, 'Manage Teachers', 'fas fa-chalkboard-teacher', 'manage-teacher.php', 6),
(18, 'Manage Courses', 'fas fa-book', 'manage_course.php', 5),
(19, 'Manage Students', 'fas fa-book-reader', 'manage-student.php', 7),
(20, 'Lesson Permission', 'fas fa-book-open', 'manage-lesson-permission.php', 8),
(21, 'Export Quiz', 'fas fa-file-export', 'manage-export-quiz.php', 9),
(22, 'Generate Quiz', 'fas fa-file-alt', 'manage_generate_quiz.php', 10),
(23, 'Manage Menus', 'fas fa-bars', 'manage_menus.php', 11),
(24, 'Manage Roles', 'fas fa-user-shield', 'manage_roles.php', 12),
(25, 'Manage Permissions', 'fas fa-key', 'manage_permissions.php', 14),
(27, 'Manage Question Bank', 'fas fa-book-medical', 'manage_question_bank.php', 15),
(35, 'My Course', 'fas fa-book', 'manage_course.php?title=My Courses', 19),
(36, 'My Grades', 'fas fa-layer-group', 'manage-grade.php?title=My Grades', 20),
(37, 'My Exam', 'fas fa-layer-group', 'my_exam.php', 21),
(38, 'Manage Editors', 'fas fa-file-alt', 'manage_editors.php', 24),
(41, 'My Quiz', 'fas fa-file-alt', 'my_quiz.php', 25),
(42, 'My Results', 'fas fa-layer-group', 'my_results.php', 26),
(43, 'Manage Assets', 'fas fa-file-alt', 'manage_assets.php', 27),
(44, 'My Account', 'fas fa-user', 'my_profile.php', 28),
(45, 'Manage Users', 'fas fa-users', 'manage_admin.php', 29),
(46, 'Manage Schools', 'fas fa-school', 'manage_schools.php', 30),
(47, 'Change school', 'fas fa-school', 'select_school.php', 2);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_key` varchar(100) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `item_text` varchar(255) NOT NULL,
  `item_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL,
  `permission` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `permission`) VALUES
(7, 'add course'),
(8, 'edit course'),
(9, 'delete course'),
(10, 'export course'),
(11, 'add lesson'),
(12, 'edit lesson'),
(13, 'delete lesson'),
(15, 'add grade'),
(16, 'edit grade'),
(17, 'delete grade'),
(18, 'add section'),
(19, 'edit section'),
(20, 'delete section'),
(21, 'add teacher'),
(22, 'edit teacher'),
(23, 'delete teacher'),
(24, 'import teacher'),
(25, 'add student'),
(26, 'edit student'),
(27, 'delete student'),
(28, 'import student'),
(29, 'manage lesson permission'),
(30, 'export quiz'),
(31, 'quiz add'),
(32, 'quiz edit'),
(33, 'manage question bank'),
(34, 'manage practices'),
(35, 'manage menus'),
(36, 'manage roles'),
(37, 'manage permissions'),
(38, 'grade view icon'),
(40, 'manage students'),
(41, 'manage teachers'),
(42, 'manage course'),
(43, 'delete section from student'),
(44, 'delete section from teacher'),
(45, 'delete section from course'),
(46, 'add teacher to section'),
(47, 'add course to section'),
(48, 'search grade'),
(49, 'section search'),
(50, 'grade view'),
(51, 'section view'),
(52, 'section view icon'),
(53, 'section student list view'),
(54, 'section student search'),
(55, 'section teacher list view'),
(56, 'section teacher search'),
(57, 'section course list view'),
(58, 'section course search'),
(59, 'teacher search'),
(60, 'teacher view'),
(61, 'teacher view icon'),
(62, 'student search'),
(63, 'student view'),
(64, 'student view icon'),
(65, 'ddddd'),
(66, 'ssss'),
(67, 'course search'),
(68, 'course view'),
(69, 'course syllabus add'),
(70, 'lesson manage practices'),
(71, 'lesson view'),
(72, 'lesson search'),
(73, 'manage section'),
(74, 'lesson manage quiz'),
(75, 'quiz delete'),
(76, 'quiz attempt'),
(77, 'exam attempt'),
(78, 'quiz result view icon'),
(79, 'quiz dispatch'),
(80, 'question add'),
(81, 'question edit'),
(82, 'question delete'),
(84, 'manage exercise'),
(85, 'manage problem'),
(86, 'answer hide'),
(87, 'assets use button');

-- --------------------------------------------------------

--
-- Table structure for table `problem`
--

CREATE TABLE `problem` (
  `problem_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `problem_name` varchar(255) NOT NULL,
  `problem_text` text DEFAULT NULL,
  `editor_url` varchar(255) DEFAULT NULL,
  `problem_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `question_type` enum('image-select','drag-match-text-to-image','click-select-image','order','match-line','image-drag-drop-to-name') DEFAULT NULL,
  `question_text` varchar(200) DEFAULT NULL,
  `correct_answer` varchar(255) DEFAULT NULL,
  `main_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `quiz_id` int(11) NOT NULL,
  `quiz_title` varchar(150) DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `is_unlocked` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('work in progress','dispatch') DEFAULT 'work in progress' COMMENT 'Ye column task ke status ko indicate karta hai',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_answer`
--

CREATE TABLE `quiz_answer` (
  `quiz_answer_id` int(11) NOT NULL,
  `quiz_attempt_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `question_type` varchar(50) DEFAULT NULL,
  `quiz_attempt_answer` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_applicable_for`
--

CREATE TABLE `quiz_applicable_for` (
  `quiz_applicable_for_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `quiz_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempt`
--

CREATE TABLE `quiz_attempt` (
  `quiz_attempt_id` int(11) NOT NULL,
  `quiz_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `status` enum('in_progress','completed') DEFAULT 'in_progress',
  `total_marks` int(11) NOT NULL DEFAULT 0,
  `student_marks` int(11) NOT NULL DEFAULT 0,
  `percentage` varchar(10) DEFAULT '0%'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `quiz_question_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'siteadmin'),
(2, 'teacher'),
(3, 'student'),
(4, 'schooladmin'),
(6, 'superadmin');

-- --------------------------------------------------------

--
-- Table structure for table `role_menus`
--

CREATE TABLE `role_menus` (
  `role_menu_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_menus`
--

INSERT INTO `role_menus` (`role_menu_id`, `role_id`, `menu_id`) VALUES
(7, 1, 16),
(14, 1, 18),
(15, 1, 19),
(16, 1, 17),
(17, 1, 20),
(19, 1, 21),
(25, 1, 27),
(38, 4, 16),
(39, 4, 18),
(40, 4, 17),
(41, 4, 19),
(42, 4, 21),
(44, 4, 20),
(46, 4, 27),
(64, 2, 20),
(65, 2, 21),
(70, 2, 27),
(71, 2, 36),
(72, 2, 35),
(73, 3, 35),
(77, 3, 41),
(78, 1, 43),
(80, 2, 44),
(81, 1, 45),
(82, 1, 46),
(83, 1, 47);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_permission_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_permission_id`, `role_id`, `permission_id`) VALUES
(4, 1, 9),
(6, 1, 10),
(7, 1, 11),
(8, 1, 12),
(9, 1, 13),
(10, 1, 14),
(11, 1, 15),
(12, 1, 16),
(13, 1, 17),
(14, 1, 18),
(15, 1, 19),
(16, 1, 20),
(17, 1, 21),
(18, 1, 22),
(19, 1, 23),
(20, 1, 24),
(21, 1, 25),
(22, 1, 26),
(23, 1, 27),
(25, 1, 29),
(26, 1, 30),
(27, 1, 31),
(28, 1, 32),
(29, 1, 33),
(30, 1, 34),
(31, 1, 35),
(32, 1, 36),
(33, 1, 37),
(34, 1, 38),
(35, 1, 39),
(36, 1, 40),
(37, 1, 41),
(38, 1, 42),
(39, 1, 43),
(40, 1, 44),
(41, 1, 45),
(42, 1, 46),
(43, 1, 47),
(44, 1, 48),
(45, 1, 49),
(46, 1, 50),
(47, 1, 51),
(48, 1, 52),
(49, 1, 53),
(50, 1, 54),
(51, 1, 55),
(52, 1, 56),
(53, 1, 57),
(54, 1, 58),
(55, 1, 59),
(56, 1, 60),
(57, 1, 61),
(58, 1, 62),
(59, 1, 63),
(60, 1, 64),
(67, 4, 13),
(69, 4, 15),
(70, 4, 16),
(72, 4, 18),
(74, 4, 20),
(81, 4, 27),
(87, 4, 33),
(89, 4, 35),
(92, 4, 38),
(93, 4, 39),
(102, 4, 48),
(103, 4, 49),
(104, 4, 50),
(112, 4, 58),
(115, 4, 61),
(117, 4, 63),
(118, 4, 64),
(120, 4, 10),
(123, 4, 13),
(133, 4, 23),
(136, 4, 26),
(137, 4, 27),
(138, 4, 28),
(139, 4, 29),
(140, 4, 30),
(142, 4, 32),
(144, 4, 34),
(145, 4, 35),
(149, 4, 39),
(152, 4, 42),
(156, 4, 46),
(157, 4, 47),
(159, 4, 49),
(162, 4, 52),
(164, 4, 54),
(166, 4, 56),
(167, 4, 57),
(169, 4, 59),
(171, 4, 61),
(172, 4, 62),
(174, 4, 64),
(177, 1, 67),
(178, 1, 68),
(179, 1, 69),
(180, 1, 70),
(181, 1, 71),
(182, 1, 72),
(183, 4, 65),
(184, 4, 66),
(185, 4, 67),
(186, 4, 68),
(187, 4, 69),
(188, 4, 70),
(189, 4, 71),
(190, 4, 72),
(193, 1, 7),
(195, 1, 8),
(196, 1, 28),
(197, 0, 28),
(198, 1, 75),
(199, 1, 73),
(200, 3, 68),
(201, 3, 67),
(202, 3, 71),
(203, 3, 72),
(204, 2, 50),
(205, 2, 48),
(206, 2, 51),
(207, 2, 49),
(208, 2, 73),
(209, 2, 40),
(210, 2, 42),
(211, 2, 53),
(212, 2, 57),
(213, 2, 58),
(214, 2, 54),
(215, 2, 68),
(216, 2, 67),
(217, 2, 71),
(218, 2, 72),
(219, 2, 74),
(220, 2, 70),
(221, 2, 31),
(222, 2, 75),
(223, 2, 32),
(225, 3, 76),
(227, 4, 21),
(228, 4, 22),
(229, 4, 60),
(230, 4, 51),
(231, 4, 55),
(232, 4, 53),
(233, 4, 41),
(234, 4, 14),
(235, 4, 25),
(236, 4, 17),
(237, 4, 45),
(238, 4, 43),
(239, 4, 44),
(240, 4, 73),
(241, 4, 40),
(242, 4, 19),
(243, 4, 12),
(244, 4, 74),
(245, 4, 31),
(246, 4, 75),
(248, 2, 78),
(250, 1, 74),
(251, 4, 11),
(252, 2, 79),
(253, 4, 79),
(254, 1, 79),
(256, 0, 9),
(259, 2, 80),
(260, 4, 80),
(261, 4, 81),
(262, 4, 82),
(263, 2, 81),
(264, 2, 82),
(265, 1, 80),
(266, 1, 81),
(267, 1, 82),
(268, 4, 78),
(269, 1, 78),
(270, 1, 84),
(271, 1, 85),
(272, 2, 84),
(273, 2, 85),
(274, 1, 86),
(275, 2, 86),
(276, 1, 87),
(277, 2, 87),
(278, 4, 87);

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `school_id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `school_username` varchar(255) DEFAULT NULL,
  `school_password` varchar(255) DEFAULT NULL,
  `school_profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `section_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `section_name` varchar(100) NOT NULL,
  `gender` enum('boy','girl') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_courses`
--

CREATE TABLE `section_courses` (
  `section_course_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_students`
--

CREATE TABLE `section_students` (
  `section_students_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `grade_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `section_teachers`
--

CREATE TABLE `section_teachers` (
  `section_teacher_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `student_number` varchar(50) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `family_name` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `teacher_name` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `gender` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unlock_lessons`
--

CREATE TABLE `unlock_lessons` (
  `unlocklessonid` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `is_unlocked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unlock_quiz`
--

CREATE TABLE `unlock_quiz` (
  `unlockquizid` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `is_unlocked` tinyint(1) DEFAULT 0,
  `unlockquizgrade` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `user_type` varchar(100) NOT NULL COMMENT '(Student,teacher,school,siteadmin)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `school_id`, `username`, `password_hash`, `full_name`, `user_type`) VALUES
(16, NULL, 'schooladmin', '3IMyIPqz2FpCoagA9R64ow==', NULL, '4'),
(17, NULL, 'siteadmin', '3IMyIPqz2FpCoagA9R64ow==', NULL, '1'),
(25, NULL, 'superadmin', '3IMyIPqz2FpCoagA9R64ow==', NULL, '6'),
(96, 13, 'abc', '3IMyIPqz2FpCoagA9R64ow==', NULL, '4'),
(97, 13, 'Susu', '3IMyIPqz2FpCoagA9R64ow==', 'Susu Wei Wang', '3'),
(98, 13, 'Maria', '3IMyIPqz2FpCoagA9R64ow==', 'Maria Jun Chen', '3'),
(99, 13, 'Xin', '3IMyIPqz2FpCoagA9R64ow==', 'Xin Li', '2'),
(100, 14, 'xyz', '3IMyIPqz2FpCoagA9R64ow==', NULL, '4'),
(101, 14, 'lam', '3IMyIPqz2FpCoagA9R64ow==', 'lam Li', '2'),
(102, 14, 'Meilin', '3IMyIPqz2FpCoagA9R64ow==', 'Meilin Wang Chen', '3'),
(103, 14, 'tim', '3IMyIPqz2FpCoagA9R64ow==', 'Wang Li', '2'),
(106, 13, 'junlai', '3IMyIPqz2FpCoagA9R64ow==', 'Junlai Wei Chen', '3'),
(108, 19, 'ASD', '3IMyIPqz2FpCoagA9R64ow==', NULL, '4'),
(109, 19, 'TEACHER', '3IMyIPqz2FpCoagA9R64ow==', 'TEACHER Li', '2'),
(110, 19, 'STUDENT', '3IMyIPqz2FpCoagA9R64ow==', 'Lianhua Wang Wang', '3'),
(111, 13, 'Xin12', '3IMyIPqz2FpCoagA9R64ow==', '1234 Wang Shaaban', '3');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_role_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_role_id`, `role_id`, `user_id`) VALUES
(1, 2, 1),
(3, 2, 24),
(5, 3, 7),
(6, 3, 8),
(7, 3, 14),
(8, 2, 15),
(9, 4, 16),
(10, 1, 17),
(11, 6, 25),
(12, 3, 26),
(14, 3, 28),
(16, 3, 30),
(17, 3, 31),
(19, 2, 33),
(22, 2, 36),
(25, 2, 39),
(26, 3, 40),
(29, 3, 43),
(30, 3, 44),
(31, 3, 0),
(32, 3, 0),
(33, 3, 0),
(34, 3, 0),
(35, 3, 0),
(36, 3, 0),
(37, 3, 0),
(38, 3, 0),
(39, 3, 0),
(40, 3, 0),
(41, 3, 0),
(42, 3, 0),
(43, 3, 0),
(44, 3, 0),
(45, 3, 0),
(46, 3, 0),
(47, 3, 0),
(48, 3, 0),
(49, 3, 0),
(50, 3, 0),
(51, 3, 0),
(52, 3, 0),
(53, 3, 0),
(54, 3, 0),
(55, 3, 0),
(56, 3, 0),
(57, 3, 0),
(58, 3, 0),
(59, 3, 0),
(60, 3, 0),
(61, 3, 0),
(62, 3, 0),
(63, 3, 0),
(64, 3, 0),
(65, 3, 0),
(66, 3, 0),
(67, 3, 0),
(68, 3, 0),
(69, 3, 0),
(70, 3, 0),
(71, 3, 0),
(72, 3, 0),
(73, 3, 0),
(74, 3, 0),
(75, 3, 0),
(76, 3, 0),
(77, 3, 0),
(78, 3, 0),
(79, 3, 0),
(83, 3, 49),
(84, 3, 50),
(86, 2, 52),
(88, 3, 55),
(89, 3, 56),
(90, 3, 57),
(91, 3, 58),
(92, 3, 59),
(93, 3, 60),
(94, 2, 61),
(95, 2, 62),
(96, 3, 63),
(100, 3, 67),
(109, 2, 76),
(110, 3, 77),
(111, 3, 78),
(117, 2, 84),
(118, 3, 85),
(119, 2, 86),
(120, 3, 87),
(121, 3, 88),
(122, 2, 89),
(123, 2, 90),
(124, 3, 91),
(125, 3, 92),
(126, 3, 93),
(127, 2, 94),
(129, 4, 96),
(130, 3, 97),
(131, 3, 98),
(132, 2, 99),
(133, 4, 100),
(134, 2, 101),
(135, 3, 102),
(136, 2, 103),
(139, 3, 106),
(141, 4, 108),
(142, 2, 109),
(143, 3, 110),
(144, 3, 111);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `unique_course_title` (`course_title`);

--
-- Indexes for table `course_code`
--
ALTER TABLE `course_code`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_course_code_user` (`user_id`),
  ADD KEY `fk_course_code_exercise` (`exercise_id`);

--
-- Indexes for table `course_overview`
--
ALTER TABLE `course_overview`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_course_overview_book` (`course_id`);

--
-- Indexes for table `course_projects`
--
ALTER TABLE `course_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_course_projects_course` (`course_id`);

--
-- Indexes for table `drag_options`
--
ALTER TABLE `drag_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `editors`
--
ALTER TABLE `editors`
  ADD PRIMARY KEY (`editor_id`);

--
-- Indexes for table `exercise`
--
ALTER TABLE `exercise`
  ADD PRIMARY KEY (`exercise_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `exercises`
--
ALTER TABLE `exercises`
  ADD PRIMARY KEY (`exercise_id`),
  ADD KEY `fk_exercise_lesson` (`lesson_id`),
  ADD KEY `fk_exercise_editor` (`editor_id`);

--
-- Indexes for table `exercise_games`
--
ALTER TABLE `exercise_games`
  ADD PRIMARY KEY (`game_id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indexes for table `exercise_instruction_hints`
--
ALTER TABLE `exercise_instruction_hints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exercise_id` (`exercise_id`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`lesson_id`),
  ADD KEY `book_id` (`course_id`);

--
-- Indexes for table `lesson_audio`
--
ALTER TABLE `lesson_audio`
  ADD PRIMARY KEY (`lesson_audio_id`),
  ADD KEY `lesson_id` (`lesson_id`);

--
-- Indexes for table `lesson_practices`
--
ALTER TABLE `lesson_practices`
  ADD PRIMARY KEY (`lesson_practice_id`),
  ADD KEY `book_id` (`course_id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `match_items`
--
ALTER TABLE `match_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `match_line_pairs`
--
ALTER TABLE `match_line_pairs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `problem`
--
ALTER TABLE `problem`
  ADD PRIMARY KEY (`problem_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `fk_questions_course` (`course_id`),
  ADD KEY `fk_questions_lesson` (`lesson_id`),
  ADD KEY `fk_questions_user` (`user_id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `fk_quiz_lesson` (`lesson_id`),
  ADD KEY `fk_quiz_user` (`user_id`);

--
-- Indexes for table `quiz_answer`
--
ALTER TABLE `quiz_answer`
  ADD PRIMARY KEY (`quiz_answer_id`);

--
-- Indexes for table `quiz_applicable_for`
--
ALTER TABLE `quiz_applicable_for`
  ADD PRIMARY KEY (`quiz_applicable_for_id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `quiz_applicable_for_fk1` (`section_id`),
  ADD KEY `fk_quiz_grade` (`grade_id`);

--
-- Indexes for table `quiz_attempt`
--
ALTER TABLE `quiz_attempt`
  ADD PRIMARY KEY (`quiz_attempt_id`),
  ADD KEY `fk_quiz_attempt_quiz` (`quiz_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`quiz_question_id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_menus`
--
ALTER TABLE `role_menus`
  ADD PRIMARY KEY (`role_menu_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `fk_role_menu` (`menu_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_permission_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `menu_id` (`permission_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`school_id`),
  ADD UNIQUE KEY `school_username` (`school_username`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`section_id`),
  ADD KEY `fk_sections_grade` (`grade_id`);

--
-- Indexes for table `section_courses`
--
ALTER TABLE `section_courses`
  ADD PRIMARY KEY (`section_course_id`),
  ADD KEY `section_books_ibfk_1` (`section_id`),
  ADD KEY `section_books_ibfk_2` (`course_id`);

--
-- Indexes for table `section_students`
--
ALTER TABLE `section_students`
  ADD PRIMARY KEY (`section_students_id`),
  ADD KEY `section_students_fk1` (`section_id`),
  ADD KEY `fk_section_students_grade` (`grade_id`);

--
-- Indexes for table `section_teachers`
--
ALTER TABLE `section_teachers`
  ADD PRIMARY KEY (`section_teacher_id`),
  ADD KEY `section_teachers_ibfk_2` (`section_id`),
  ADD KEY `fk_section_teachers_grade` (`grade_id`),
  ADD KEY `fk_section_teachers_teacher` (`teacher_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `fk_students_user` (`user_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `fk_teachers_user` (`user_id`);

--
-- Indexes for table `unlock_lessons`
--
ALTER TABLE `unlock_lessons`
  ADD PRIMARY KEY (`unlocklessonid`),
  ADD UNIQUE KEY `uniq_lesson` (`grade_id`,`section_id`,`lesson_id`);

--
-- Indexes for table `unlock_quiz`
--
ALTER TABLE `unlock_quiz`
  ADD PRIMARY KEY (`unlockquizid`),
  ADD KEY `fk_unlock_quiz` (`quiz_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_code`
--
ALTER TABLE `course_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_overview`
--
ALTER TABLE `course_overview`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_projects`
--
ALTER TABLE `course_projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drag_options`
--
ALTER TABLE `drag_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `editors`
--
ALTER TABLE `editors`
  MODIFY `editor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `exercise`
--
ALTER TABLE `exercise`
  MODIFY `exercise_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exercises`
--
ALTER TABLE `exercises`
  MODIFY `exercise_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exercise_games`
--
ALTER TABLE `exercise_games`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exercise_instruction_hints`
--
ALTER TABLE `exercise_instruction_hints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `lesson_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lesson_audio`
--
ALTER TABLE `lesson_audio`
  MODIFY `lesson_audio_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lesson_practices`
--
ALTER TABLE `lesson_practices`
  MODIFY `lesson_practice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `match_items`
--
ALTER TABLE `match_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `match_line_pairs`
--
ALTER TABLE `match_line_pairs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `problem`
--
ALTER TABLE `problem`
  MODIFY `problem_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_answer`
--
ALTER TABLE `quiz_answer`
  MODIFY `quiz_answer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_applicable_for`
--
ALTER TABLE `quiz_applicable_for`
  MODIFY `quiz_applicable_for_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_attempt`
--
ALTER TABLE `quiz_attempt`
  MODIFY `quiz_attempt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `quiz_question_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `role_menus`
--
ALTER TABLE `role_menus`
  MODIFY `role_menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `role_permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=279;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section_courses`
--
ALTER TABLE `section_courses`
  MODIFY `section_course_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section_students`
--
ALTER TABLE `section_students`
  MODIFY `section_students_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `section_teachers`
--
ALTER TABLE `section_teachers`
  MODIFY `section_teacher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unlock_lessons`
--
ALTER TABLE `unlock_lessons`
  MODIFY `unlocklessonid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unlock_quiz`
--
ALTER TABLE `unlock_quiz`
  MODIFY `unlockquizid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `user_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `drag_options`
--
ALTER TABLE `drag_options`
  ADD CONSTRAINT `drag_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `fk_lessons_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_audio`
--
ALTER TABLE `lesson_audio`
  ADD CONSTRAINT `lesson_audio_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lesson_id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_practices`
--
ALTER TABLE `lesson_practices`
  ADD CONSTRAINT `lesson_practices_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lesson_practices_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lesson_id`),
  ADD CONSTRAINT `lesson_practices_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `match_items`
--
ALTER TABLE `match_items`
  ADD CONSTRAINT `match_items_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `match_line_pairs`
--
ALTER TABLE `match_line_pairs`
  ADD CONSTRAINT `match_line_pairs_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `options`
--
ALTER TABLE `options`
  ADD CONSTRAINT `options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `fk_questions_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_questions_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lesson_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_questions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `fk_quiz_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lesson_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_quiz_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_applicable_for`
--
ALTER TABLE `quiz_applicable_for`
  ADD CONSTRAINT `fk_quiz_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_applicable_for_fk1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_applicable_for_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`quiz_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_applicable_for_ibfk_2` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_applicable_for_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_attempt`
--
ALTER TABLE `quiz_attempt`
  ADD CONSTRAINT `fk_quiz_attempt_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`quiz_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`quiz_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `role_menus`
--
ALTER TABLE `role_menus`
  ADD CONSTRAINT `fk_role_menu` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_menus_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  ADD CONSTRAINT `role_menus_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `fk_sections_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`) ON DELETE CASCADE;

--
-- Constraints for table `section_courses`
--
ALTER TABLE `section_courses`
  ADD CONSTRAINT `fk_section_courses_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_courses_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_courses_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `section_students`
--
ALTER TABLE `section_students`
  ADD CONSTRAINT `fk_section_students_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_section_students_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE;

--
-- Constraints for table `section_teachers`
--
ALTER TABLE `section_teachers`
  ADD CONSTRAINT `fk_section_teachers_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_section_teachers_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_teachers_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_teachers_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `fk_teachers_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `unlock_quiz`
--
ALTER TABLE `unlock_quiz`
  ADD CONSTRAINT `fk_unlock_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`quiz_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- 2026-5-14
ALTER TABLE `questions` CHANGE `question_text` `question_text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;