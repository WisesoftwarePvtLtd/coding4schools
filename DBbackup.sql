-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jan 23, 2026 at 07:25 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chineseschools`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `course_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_cover_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `level` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grades` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration_type` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `timeframe` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prerequisite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`course_id`),
  UNIQUE KEY `unique_course_title` (`course_title`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--


-- --------------------------------------------------------

--
-- Table structure for table `course_code`
--

DROP TABLE IF EXISTS `course_code`;
CREATE TABLE IF NOT EXISTS `course_code` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `editor_type` enum('monaco','external') NOT NULL,
  `code` longtext,
  `project_ref` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_course` (`user_id`,`course_name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_code`
--

-------------------------

--
-- Table structure for table `course_overview`
--

DROP TABLE IF EXISTS `course_overview`;
CREATE TABLE IF NOT EXISTS `course_overview` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `certification_available` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_course_overview_book` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_projects`
--

DROP TABLE IF EXISTS `course_projects`;
CREATE TABLE IF NOT EXISTS `course_projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `project_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_course_projects_course` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drag_options`
--

DROP TABLE IF EXISTS `drag_options`;
CREATE TABLE IF NOT EXISTS `drag_options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `drag_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drag_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drag_options`
--


--
-- Table structure for table `editors`
--

DROP TABLE IF EXISTS `editors`;
CREATE TABLE IF NOT EXISTS `editors` (
  `editor_id` int NOT NULL AUTO_INCREMENT,
  `editor_name` varchar(100) NOT NULL,
  `editor_url` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`editor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `editors`
--

INSERT INTO `editors` (`editor_id`, `editor_name`, `editor_url`, `created_at`) VALUES
(5, 'Scratch Jr', 'https://codejr.org/scratchjr/index.html', '2026-01-19 05:03:53'),
(6, 'Codey Rockey', 'https://ide.mblock.cc/', '2026-01-19 07:47:34');

-- --------------------------------------------------------

--
-- Table structure for table `exercise`
--

DROP TABLE IF EXISTS `exercise`;
CREATE TABLE IF NOT EXISTS `exercise` (
  `exercise_id` int NOT NULL AUTO_INCREMENT,
  `lesson_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `instructions` text NOT NULL,
  `instruction_image` varchar(255) DEFAULT NULL,
  `hint` text,
  `hint_image` varchar(255) DEFAULT NULL,
  `editor_key` varchar(50) NOT NULL,
  `editor_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`exercise_id`),
  KEY `lesson_id` (`lesson_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercise`
--

--------------------------------------------

--
-- Table structure for table `exercises`
--

DROP TABLE IF EXISTS `exercises`;
CREATE TABLE IF NOT EXISTS `exercises` (
  `exercise_id` int NOT NULL AUTO_INCREMENT,
  `lesson_id` int NOT NULL,
  `editor_id` int NOT NULL,
  `exercise_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`exercise_id`),
  KEY `fk_exercise_lesson` (`lesson_id`),
  KEY `fk_exercise_editor` (`editor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercises`
--



-- --------------------------------------------------------

--
-- Table structure for table `exercise_games`
--

DROP TABLE IF EXISTS `exercise_games`;
CREATE TABLE IF NOT EXISTS `exercise_games` (
  `game_id` int NOT NULL AUTO_INCREMENT,
  `exercise_id` int NOT NULL,
  `game_url` text NOT NULL,
  `description` text,
  PRIMARY KEY (`game_id`),
  KEY `exercise_id` (`exercise_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_instruction_hints`
--

DROP TABLE IF EXISTS `exercise_instruction_hints`;
CREATE TABLE IF NOT EXISTS `exercise_instruction_hints` (
  `id` int NOT NULL AUTO_INCREMENT,
  `exercise_id` int NOT NULL,
  `instruction_text` text,
  `instruction_image` varchar(255) DEFAULT NULL,
  `hint_text` text,
  `hint_image` varchar(255) DEFAULT NULL,
  `hint_video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `exercise_id` (`exercise_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercise_instruction_hints`
--


-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

DROP TABLE IF EXISTS `grades`;
CREATE TABLE IF NOT EXISTS `grades` (
  `grade_id` int NOT NULL AUTO_INCREMENT,
  `grade_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_year` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`grade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grades`
--


-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

DROP TABLE IF EXISTS `lessons`;
CREATE TABLE IF NOT EXISTS `lessons` (
  `lesson_id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `lesson_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lesson_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lesson_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lesson_plan_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lesson_ppt_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lesson_workbook_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lesson_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(Syllabus/lesson/cultural)',
  `lesson_sort_order` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`lesson_id`),
  KEY `book_id` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--


-- --------------------------------------------------------

--
-- Table structure for table `lesson_audio`
--

DROP TABLE IF EXISTS `lesson_audio`;
CREATE TABLE IF NOT EXISTS `lesson_audio` (
  `lesson_audio_id` int NOT NULL AUTO_INCREMENT,
  `lesson_id` int NOT NULL,
  `audio_file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`lesson_audio_id`),
  KEY `lesson_id` (`lesson_id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lesson_audio`
--


-- --------------------------------------------------------

--
-- Table structure for table `lesson_practices`
--

DROP TABLE IF EXISTS `lesson_practices`;
CREATE TABLE IF NOT EXISTS `lesson_practices` (
  `lesson_practice_id` int NOT NULL AUTO_INCREMENT,
  `course_id` int NOT NULL,
  `lesson_id` int NOT NULL,
  `question_id` int NOT NULL,
  PRIMARY KEY (`lesson_practice_id`),
  KEY `book_id` (`course_id`),
  KEY `lesson_id` (`lesson_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lesson_practices`
--


-- --------------------------------------------------------

--
-- Table structure for table `match_items`
--

DROP TABLE IF EXISTS `match_items`;
CREATE TABLE IF NOT EXISTS `match_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `item_key` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correct_word` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `match_items`
--


-- --------------------------------------------------------

--
-- Table structure for table `match_line_pairs`
--

DROP TABLE IF EXISTS `match_line_pairs`;
CREATE TABLE IF NOT EXISTS `match_line_pairs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `left_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `left_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `right_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `right_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `match_line_pairs`
--


-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `menu_id` int NOT NULL AUTO_INCREMENT,
  `menu` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_icon` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_link_path` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_order` int DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `menu`, `menu_icon`, `menu_link_path`, `menu_order`) VALUES
(16, 'Manage Grades', 'fas fa-layer-group', 'manage-grade.php', 2),
(17, 'Manage Teachers', 'fas fa-chalkboard-teacher', 'manage-teacher.php', 5),
(18, 'Manage Courses', 'fas fa-book', 'manage_course.php', 4),
(19, 'Manage Students', 'fas fa-book-reader', 'manage-student.php', 6),
(20, 'Lesson Permission', 'fas fa-book-open', 'manage-lesson-permission.php', 7),
(21, 'Export Quiz', 'fas fa-file-export', 'manage-export-quiz.php', 8),
(22, 'Generate Quiz', 'fas fa-file-alt', 'manage_generate_quiz.php', 9),
(23, 'Manage Menus', 'fas fa-bars', 'manage_menus.php', 10),
(24, 'Manage Roles', 'fas fa-user-shield', 'manage_roles.php', 11),
(25, 'Manage Permissions', 'fas fa-key', 'manage_permissions.php', 12),
(27, 'Manage Question Bank', 'fas fa-book-medical', 'manage_question_bank.php', 14),
(35, 'My Books', 'fas fa-book', 'manage_course.php?title=My Books', 18),
(36, 'My Grades', 'fas fa-layer-group', 'manage-grade.php?title=My Grades', 19),
(37, 'My Exam', 'fas fa-layer-group', 'my_exam.php', 20),
(38, 'Manage Editors', 'fas fa-file-alt', 'manage_editors.php', 23);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `option_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `options`
--



-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `item_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_order` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--



-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `permission_id` int NOT NULL AUTO_INCREMENT,
  `permission` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `permission`) VALUES
(7, 'add book'),
(8, 'edit book'),
(9, 'delete book'),
(10, 'export book'),
(11, 'add lesson'),
(12, 'edit lesson'),
(13, 'delete lesson'),
(14, 'book cultural activity add'),
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
(31, 'generate quiz'),
(32, 'manage quiz'),
(33, 'manage question bank'),
(34, 'manage practices'),
(35, 'manage menus'),
(36, 'manage roles'),
(37, 'manage permissions'),
(38, 'grade view icon'),
(40, 'manage students'),
(41, 'manage teachers'),
(42, 'manage books'),
(43, 'delete section from student'),
(44, 'delete section from teacher'),
(45, 'delete section from book'),
(46, 'add teacher to section'),
(47, 'add book to section'),
(48, 'search grade'),
(49, 'section search'),
(50, 'grade view'),
(51, 'section view'),
(52, 'section view icon'),
(53, 'section student list view'),
(54, 'section student search'),
(55, 'section teacher list view'),
(56, 'section teacher search'),
(57, 'section book list view'),
(58, 'section book search'),
(59, 'teacher search'),
(60, 'teacher view'),
(61, 'teacher view icon'),
(62, 'student search'),
(63, 'student view'),
(64, 'student view icon'),
(65, 'ddddd'),
(66, 'ssss'),
(67, 'book search'),
(68, 'book view'),
(69, 'book syllabus add'),
(70, 'lesson manage practices'),
(71, 'lesson view'),
(72, 'lesson search'),
(73, 'manage section');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `question_id` int NOT NULL AUTO_INCREMENT,
  `book_id` int DEFAULT NULL,
  `lesson_id` int DEFAULT NULL,
  `question_type` enum('image-select','drag-match-text-to-image','click-select-image','order','match-line','image-drag-drop-to-name') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `question_text` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correct_answer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--


-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

DROP TABLE IF EXISTS `quiz`;
CREATE TABLE IF NOT EXISTS `quiz` (
  `quiz_id` int NOT NULL AUTO_INCREMENT,
  `quiz_title` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `book_id` int NOT NULL,
  `status` enum('work in progress','dispatch') COLLATE utf8mb4_unicode_ci DEFAULT 'work in progress' COMMENT 'Ye column task ke status ko indicate karta hai',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`quiz_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz`
--


-- --------------------------------------------------------

--
-- Table structure for table `quiz_answer`
--

DROP TABLE IF EXISTS `quiz_answer`;
CREATE TABLE IF NOT EXISTS `quiz_answer` (
  `quiz_answer_id` int NOT NULL AUTO_INCREMENT,
  `quiz_attempt_id` int DEFAULT NULL,
  `question_id` int DEFAULT NULL,
  `question_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quiz_attempt_answer` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`quiz_answer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=295 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_answer`
--


-- --------------------------------------------------------

--
-- Table structure for table `quiz_applicable_for`
--

DROP TABLE IF EXISTS `quiz_applicable_for`;
CREATE TABLE IF NOT EXISTS `quiz_applicable_for` (
  `quiz_applicable_for_id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `grade_id` int NOT NULL,
  `section_id` int NOT NULL,
  `quiz_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`quiz_applicable_for_id`),
  KEY `quiz_id` (`quiz_id`),
  KEY `quiz_applicable_for_fk1` (`section_id`),
  KEY `fk_quiz_grade` (`grade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_applicable_for`
--


-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempt`
--

DROP TABLE IF EXISTS `quiz_attempt`;
CREATE TABLE IF NOT EXISTS `quiz_attempt` (
  `quiz_attempt_id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `status` enum('in_progress','completed') COLLATE utf8mb4_unicode_ci DEFAULT 'in_progress',
  PRIMARY KEY (`quiz_attempt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_attempt`
--


-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

DROP TABLE IF EXISTS `quiz_questions`;
CREATE TABLE IF NOT EXISTS `quiz_questions` (
  `quiz_question_id` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `question_id` int NOT NULL,
  PRIMARY KEY (`quiz_question_id`),
  KEY `quiz_id` (`quiz_id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_questions`
--

--------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'siteadmin'),
(2, 'teacher'),
(3, 'student'),
(4, 'schooladmin'),
(6, 'superadmin'),
(7, 'ddddd');

-- --------------------------------------------------------

--
-- Table structure for table `role_menus`
--

DROP TABLE IF EXISTS `role_menus`;
CREATE TABLE IF NOT EXISTS `role_menus` (
  `role_menu_id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `menu_id` int NOT NULL,
  PRIMARY KEY (`role_menu_id`),
  KEY `role_id` (`role_id`),
  KEY `fk_role_menu` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_menus`
--

INSERT INTO `role_menus` (`role_menu_id`, `role_id`, `menu_id`) VALUES
(7, 1, 16),
(14, 1, 18),
(15, 1, 19),
(16, 1, 17),
(22, 1, 23),
(23, 1, 25),
(25, 1, 27),
(27, 1, 24),
(38, 7, 21),
(41, 7, 22),
(43, 3, 35),
(44, 2, 36),
(45, 2, 35),
(46, 4, 18),
(47, 4, 16),
(48, 1, 37),
(49, 3, 37),
(50, 3, 16),
(51, 3, 17),
(52, 3, 18),
(53, 3, 19),
(54, 3, 20),
(55, 3, 21),
(56, 3, 22),
(57, 3, 23),
(58, 3, 24),
(59, 3, 25),
(60, 3, 27),
(61, 3, 35),
(62, 3, 36),
(63, 3, 37),
(65, 4, 16),
(66, 4, 17),
(67, 4, 18),
(68, 4, 19),
(69, 4, 20),
(70, 4, 21),
(71, 4, 22),
(72, 4, 23),
(73, 4, 24),
(74, 4, 25),
(75, 4, 27),
(76, 4, 35),
(77, 4, 36),
(78, 4, 37);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_permission_id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  PRIMARY KEY (`role_permission_id`),
  KEY `role_id` (`role_id`),
  KEY `menu_id` (`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_permission_id`, `role_id`, `permission_id`) VALUES
(2, 4, 1),
(3, 1, 1),
(4, 1, 7),
(8, 1, 17),
(9, 1, 39),
(10, 1, 15),
(11, 1, 18),
(12, 1, 40),
(13, 1, 42),
(14, 1, 41),
(15, 1, 50),
(16, 1, 51),
(17, 1, 25),
(18, 1, 27),
(19, 1, 26),
(20, 1, 63),
(21, 1, 64),
(22, 1, 62),
(23, 1, 22),
(24, 1, 21),
(25, 1, 23),
(26, 1, 38),
(27, 1, 16),
(29, 1, 48),
(31, 1, 55),
(32, 1, 54),
(33, 1, 53),
(34, 1, 56),
(35, 1, 58),
(36, 1, 19),
(37, 1, 20),
(38, 1, 52),
(39, 1, 61),
(40, 1, 59),
(41, 1, 60),
(42, 1, 28),
(43, 1, 46),
(44, 1, 44),
(45, 1, 45),
(46, 1, 43),
(47, 1, 47),
(48, 1, 57),
(49, 1, 67),
(50, 1, 68),
(53, 1, 72),
(54, 1, 71),
(55, 1, 11),
(56, 1, 14),
(57, 1, 70),
(58, 1, 12),
(59, 1, 13),
(60, 1, 8),
(61, 1, 9),
(62, 1, 69),
(63, 4, 7),
(64, 4, 8),
(65, 4, 9),
(66, 4, 10),
(67, 4, 11),
(68, 4, 12),
(69, 4, 13),
(70, 4, 14),
(71, 4, 15),
(72, 4, 16),
(73, 4, 17),
(74, 4, 18),
(75, 4, 19),
(76, 4, 20),
(77, 4, 21),
(78, 4, 22),
(79, 4, 23),
(80, 4, 24),
(81, 4, 25),
(82, 4, 26),
(83, 4, 27),
(84, 4, 28),
(85, 4, 29),
(86, 4, 30),
(87, 4, 31),
(88, 4, 32),
(89, 4, 33),
(90, 4, 34),
(91, 4, 35),
(92, 4, 36),
(93, 4, 37),
(94, 4, 38),
(95, 4, 39),
(96, 4, 40),
(97, 4, 41),
(98, 4, 42),
(99, 4, 43),
(100, 4, 44),
(101, 4, 45),
(102, 4, 46),
(103, 4, 47),
(104, 4, 48),
(105, 4, 49),
(106, 4, 50),
(107, 4, 51),
(108, 4, 52),
(109, 4, 53),
(110, 4, 54),
(111, 4, 55),
(112, 4, 56),
(113, 4, 57),
(114, 4, 58),
(115, 4, 59),
(116, 4, 60),
(117, 4, 61),
(118, 4, 62),
(119, 4, 63),
(120, 4, 64),
(121, 4, 65),
(122, 4, 66),
(123, 4, 68),
(124, 4, 69),
(125, 4, 70),
(126, 4, 71),
(127, 4, 72),
(128, 7, 7),
(130, 7, 47),
(131, 3, 68),
(132, 3, 67),
(133, 3, 72),
(134, 3, 71),
(135, 2, 50),
(136, 2, 48),
(138, 2, 51),
(139, 2, 49),
(140, 1, 73),
(141, 2, 73),
(147, 2, 40),
(148, 2, 42),
(149, 2, 53),
(150, 2, 57),
(151, 2, 58),
(152, 2, 54),
(153, 2, 68),
(154, 2, 67),
(155, 2, 72),
(156, 2, 71);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
CREATE TABLE IF NOT EXISTS `sections` (
  `section_id` int NOT NULL AUTO_INCREMENT,
  `grade_id` int NOT NULL,
  `section_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('boy','girl') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`section_id`),
  KEY `fk_sections_grade` (`grade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--


-- --------------------------------------------------------

--
-- Table structure for table `section_books`
--

DROP TABLE IF EXISTS `section_books`;
CREATE TABLE IF NOT EXISTS `section_books` (
  `section_book_id` int NOT NULL AUTO_INCREMENT,
  `section_id` int NOT NULL,
  `course_id` int NOT NULL,
  PRIMARY KEY (`section_book_id`),
  KEY `section_books_ibfk_1` (`section_id`),
  KEY `section_books_ibfk_2` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `section_books`
--


-- --------------------------------------------------------

--
-- Table structure for table `section_students`
--

DROP TABLE IF EXISTS `section_students`;
CREATE TABLE IF NOT EXISTS `section_students` (
  `section_students_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `grade_id` int DEFAULT NULL,
  `section_id` int DEFAULT NULL,
  PRIMARY KEY (`section_students_id`),
  KEY `section_students_fk1` (`section_id`),
  KEY `fk_section_students_grade` (`grade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `section_students`
--


-- --------------------------------------------------------

--
-- Table structure for table `section_teachers`
--

DROP TABLE IF EXISTS `section_teachers`;
CREATE TABLE IF NOT EXISTS `section_teachers` (
  `section_teacher_id` int NOT NULL AUTO_INCREMENT,
  `teacher_id` int NOT NULL,
  `grade_id` int NOT NULL,
  `section_id` int NOT NULL,
  PRIMARY KEY (`section_teacher_id`),
  KEY `section_teachers_ibfk_2` (`section_id`),
  KEY `fk_section_teachers_grade` (`grade_id`),
  KEY `fk_section_teachers_teacher` (`teacher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `section_teachers`
--


-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `student_id` int NOT NULL AUTO_INCREMENT,
  `student_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--


-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `teacher_id` int NOT NULL AUTO_INCREMENT,
  `teacher_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`teacher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--


-- --------------------------------------------------------

--
-- Table structure for table `unlock_lessons`
--

DROP TABLE IF EXISTS `unlock_lessons`;
CREATE TABLE IF NOT EXISTS `unlock_lessons` (
  `unlocklessonid` int NOT NULL AUTO_INCREMENT,
  `grade_id` int NOT NULL,
  `section_id` int NOT NULL,
  `lesson_id` int NOT NULL,
  `is_unlocked` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`unlocklessonid`),
  UNIQUE KEY `uniq_lesson` (`grade_id`,`section_id`,`lesson_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unlock_lessons`
--


-- --------------------------------------------------------

--
-- Table structure for table `unlock_quiz`
--

DROP TABLE IF EXISTS `unlock_quiz`;
CREATE TABLE IF NOT EXISTS `unlock_quiz` (
  `unlockquizid` int NOT NULL AUTO_INCREMENT,
  `quiz_id` int NOT NULL,
  `is_unlocked` tinyint(1) DEFAULT '0',
  `unlockgrade` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`unlockquizid`),
  UNIQUE KEY `uniq_quiz` (`quiz_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unlock_quiz`
--



-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(Student,teacher,school,siteadmin)',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `full_name`, `user_type`) VALUES
(1, 'r', '3IMyIPqz2FpCoagA9R64ow==', 'Rohini Shivade', '2'),
(7, 'rahul', '3IMyIPqz2FpCoagA9R64ow==', 'Rahul s Patil', '3'),
(8, 'pal', '3IMyIPqz2FpCoagA9R64ow==', 'pal sss www', '3'),
(14, 'HRMSJoy', 'LoESnbj/Wm2cZ492wVJ61w==', 'dddddd ddd dddd', '3'),
(15, 'ddddd', '3IMyIPqz2FpCoagA9R64ow==', 'dd ddd', '2'),
(16, 'schooladmin', '3IMyIPqz2FpCoagA9R64ow==', NULL, '4'),
(17, 'siteadmin', '3IMyIPqz2FpCoagA9R64ow==', NULL, '1'),
(24, 'w', '3IMyIPqz2FpCoagA9R64ow==', 'w w', '2'),
(25, 'superadmin', '3IMyIPqz2FpCoagA9R64ow==', NULL, '6'),
(26, 'ff', '3IMyIPqz2FpCoagA9R64ow==', 'fff fff fff', '3'),
(28, 'cxzc', '3IMyIPqz2FpCoagA9R64ow==', 'dgdfg fgdfg fgfg', '3'),
(30, 'rohini', '3IMyIPqz2FpCoagA9R64ow==', 'rohini sahebrao sonawane', '3'),
(33, 'ww', '3IMyIPqz2FpCoagA9R64ow==', 'rrrrr rrrrr', '2'),
(36, 'rr', '3IMyIPqz2FpCoagA9R64ow==', 'rr rr', '2'),
(39, 'weee', '3IMyIPqz2FpCoagA9R64ow==', 'w w', '2'),
(40, 'ds', '3IMyIPqz2FpCoagA9R64ow==', 'wew wwew wewe', '3'),
(43, '123-4789', '3IMyIPqz2FpCoagA9R64ow==', 'hhhh mmm iii', '3'),
(44, '2', '3IMyIPqz2FpCoagA9R64ow==', 'hhhh mmm iii', '3'),
(49, 'Zh', '3IMyIPqz2FpCoagA9R64ow==', 'Zhang Kim Lum', '3'),
(50, 'Zihan', '3IMyIPqz2FpCoagA9R64ow==', 'Zihan  Zimo Li', '3'),
(52, 'fgdfgdfg', 'vuEfRZY7MYpvLP3N7h/rkA==', 'dfdf dfsdfds', '2'),
(54, 'fff', 'eKGAEq5MJj6fNcIKg+mJUQ==', 'fff ff fff', '3'),
(55, 'aa', '3IMyIPqz2FpCoagA9R64ow==', 'as as as', '3'),
(56, 'qq', '3IMyIPqz2FpCoagA9R64ow==', 'qqq qq qqq', '3'),
(57, 'ss', 'youWxvAf/ET4zUtJk6yp5g==', 'ss ss ss', '3'),
(58, 'xx', 'Gsuf+T8ScNX5WhX6JI+OkA==', 'xx xx xx', '3'),
(59, 'kk', 'moaorAGydXm7Ce0MWik3BQ==', 'kk kk kk', '3'),
(60, 'vv', 'mo5yYA3CbQ4JhtJ8GkdLBw==', 'vv vv vv', '3'),
(61, 'rahi', '3IMyIPqz2FpCoagA9R64ow==', 'rahi patil', '2'),
(62, 'e', '3IMyIPqz2FpCoagA9R64ow==', 'e e', '2'),
(63, 's', '3IMyIPqz2FpCoagA9R64ow==', 's s s', '3'),
(64, 'q', '3IMyIPqz2FpCoagA9R64ow==', NULL, '1'),
(65, 't', '3IMyIPqz2FpCoagA9R64ow==', NULL, '4'),
(66, 'i', '3IMyIPqz2FpCoagA9R64ow==', NULL, '6'),
(67, 'A', '3IMyIPqz2FpCoagA9R64ow==', 'A A A', '3');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE IF NOT EXISTS `user_roles` (
  `user_role_id` int NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`user_role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(97, 3, 64),
(98, 3, 65),
(99, 3, 66),
(100, 3, 67);

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
  ADD CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_audio`
--
ALTER TABLE `lesson_audio`
  ADD CONSTRAINT `lesson_audio_ibfk_1` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lesson_id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_practices`
--
ALTER TABLE `lesson_practices`
  ADD CONSTRAINT `lesson_practices_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`),
  ADD CONSTRAINT `lesson_practices_ibfk_2` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`lesson_id`),
  ADD CONSTRAINT `lesson_practices_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);

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
-- Constraints for table `quiz_applicable_for`
--
ALTER TABLE `quiz_applicable_for`
  ADD CONSTRAINT `fk_quiz_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_applicable_for_fk1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_applicable_for_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`quiz_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_applicable_for_ibfk_2` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_applicable_for_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `quiz_questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`quiz_id`),
  ADD CONSTRAINT `quiz_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`);

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
-- Constraints for table `section_books`
--
ALTER TABLE `section_books`
  ADD CONSTRAINT `section_books_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_books_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE;

--
-- Constraints for table `section_students`
--
ALTER TABLE `section_students`
  ADD CONSTRAINT `fk_section_students_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_students_fk1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE;

--
-- Constraints for table `section_teachers`
--
ALTER TABLE `section_teachers`
  ADD CONSTRAINT `fk_section_teachers_grade` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_section_teachers_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_teachers_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `section_teachers_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- 2026-1-27

ALTER TABLE `lessons` DROP `lesson_no`;

-- 2026-1-28

ALTER TABLE `questions` CHANGE `book_id` `course_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `questions` ADD `user_id` INT(11) NOT NULL AFTER `lesson_id`;
ALTER TABLE `options` CHANGE `option_key` `option_key` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `section_books` CHANGE `section_book_id` `section_course_id` INT(11) NOT NULL AUTO_INCREMENT;
RENAME TABLE section_books TO section_courses;
ALTER TABLE `quiz` CHANGE `book_id` `course_id` INT(11) NOT NULL;
ALTER TABLE `section_courses` ADD `grade_id` INT(11) NOT NULL AFTER `section_id`;
ALTER TABLE `quiz` ADD `lesson_id` INT(11) NOT NULL AFTER `course_id`, ADD `user_id` INT(11) NOT NULL AFTER `lesson_id`, ADD `is_unlocked` TINYINT(1) NOT NULL DEFAULT '0' AFTER `user_id`;

-- 2026-1-29

ALTER TABLE quiz_attempt
ADD COLUMN total_marks INT(11) NOT NULL DEFAULT 0,
ADD COLUMN student_marks INT(11) NOT NULL DEFAULT 0,
ADD COLUMN percentage VARCHAR(10) DEFAULT '0%';

-- 2026-2-2
ALTER TABLE `course_code` ADD `exercise_id` INT(11) NOT NULL AFTER `user_id`;
ALTER TABLE course_code
MODIFY editor_type VARCHAR(50) NOT NULL;

-- 2026-2-3
ALTER TABLE `courses` CHANGE `level` `level` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `courses` CHANGE `grades` `grades` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `courses` CHANGE `duration` `duration` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `courses` CHANGE `duration_type` `duration_type` VARCHAR(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `courses` CHANGE `timeframe` `timeframe` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

-- 2026-2-4
ALTER TABLE `lessons` CHANGE `lesson_workbook_path` `lesson_video_path` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `lessons` CHANGE `lesson_ppt_path` `lesson_summary_path` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `exercises` ADD `exercise_sort_order` INT(11) NOT NULL AFTER `exercise_name`;

-- 2026-2-5
CREATE TABLE IF NOT EXISTS `problem` (
  `problem_id` int NOT NULL AUTO_INCREMENT,
  `lesson_id` int NOT NULL,
  `problem_name` varchar(255) NOT NULL,
  `problem_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`problem_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `exercise_instruction_hints` CHANGE `hint_video` `sprite_image` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

-- 2026-2-10
ALTER TABLE `exercises` ADD `exercise_discription` VARCHAR(255) NULL DEFAULT NULL AFTER `exercise_name`;

ALTER TABLE `exercises` ADD `sprite_image` VARCHAR(255) NULL DEFAULT NULL AFTER `exercise_discription`;

ALTER TABLE `editors` ADD `editor_language` VARCHAR(50) NULL DEFAULT NULL AFTER `editor_name`;
ALTER TABLE `editors` ADD `editor_type` VARCHAR(50) NULL DEFAULT NULL AFTER `editor_language`;
ALTER TABLE `editors` ADD `editor_save_code` INT(11) NULL DEFAULT NULL AFTER `editor_type`;


CREATE TABLE  `assets` (
  `asset_id` int NOT NULL AUTO_INCREMENT,
  `asset_name` varchar(255) NOT NULL,
  `asset_file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`asset_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- 2026-2-18
ALTER TABLE `exercises` ADD `instruction_guideline` VARCHAR(255) NULL DEFAULT NULL AFTER `sprite_image`;
ALTER TABLE `grades` ADD `grade_number` INT(11) NULL DEFAULT NULL AFTER `grade_name`;
ALTER TABLE `courses` CHANGE `course_cover_page` `course_cover_page` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `lessons` ADD `lesson_guideline` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL AFTER `lesson_title`;
ALTER TABLE `lessons` CHANGE `lesson_type` `lesson_type` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '(Syllabus/lesson/cultural)';
ALTER TABLE `lessons` CHANGE `lesson_guideline` `lesson_guideline` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `exercises` CHANGE `instruction_guideline` `instruction_guideline` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

-- 2026-2-26
ALTER TABLE `course_code` CHANGE `exercise_id` `exercise_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `unlock_quiz` CHANGE `unlockgrade` `unlockquizgrade` TINYINT(1) NULL DEFAULT '0';

-- 2026-3-10
ALTER TABLE `exercises` ADD `user_id` INT(11) NOT NULL AFTER `editor_id`;

-- 2026-3-13
ALTER TABLE `problem` ADD `problem_text` VARCHAR(255) NULL DEFAULT NULL AFTER `problem_name`, ADD `editor_url` VARCHAR(255) NULL DEFAULT NULL AFTER `problem_text`;

-- 2026-3-16
ALTER TABLE `lessons` ADD `lesson_guideline_image` VARCHAR(255) NULL DEFAULT NULL AFTER `lesson_guideline`;

CREATE TABLE IF NOT EXISTS `schools` (
  `school_id` INT NOT NULL AUTO_INCREMENT,
  `school_name` VARCHAR(255) NOT NULL,
  `school_username` VARCHAR(255) NOT NULL UNIQUE,
  `school_password` VARCHAR(255) NOT NULL,
  `school_profile_image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`school_id`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `users` ADD `school_id` INT(11) NULL DEFAULT NULL AFTER `user_id`;
ALTER TABLE `grades` ADD `school_id` INT(11) NULL DEFAULT NULL AFTER `academic_year`;
-- 2026-3-17
UPDATE `editors` SET `editor_type` = 'scratch' WHERE `editors`.`editor_id` = 32;
ALTER TABLE `schools` CHANGE `school_username` `school_username` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `schools` CHANGE `school_password` `school_password` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;


-- 2026-3-19

ALTER TABLE lessons 
ADD COLUMN `teacher_game_file` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
ADD COLUMN `student_game_file` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL;

ALTER TABLE `exercise_instruction_hints` ADD `instruction_sort_order` INT(11) NULL DEFAULT NULL AFTER `instruction_text`;


-- 2026-3-24
ALTER TABLE `courses` ADD `course_sort_order` INT(11) NULL DEFAULT NULL AFTER `course_details`;

-- 2026-3-27
ALTER TABLE questions ADD school_id INT AFTER user_id;
ALTER TABLE `problem` CHANGE `problem_text` `problem_text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE quiz ADD school_id INT AFTER user_id;

-- 2026-3-31

ALTER TABLE lessons
DROP FOREIGN KEY lessons_ibfk_1;

ALTER TABLE lessons
ADD CONSTRAINT fk_lessons_course
FOREIGN KEY (course_id)
REFERENCES courses(course_id)
ON DELETE CASCADE;

ALTER TABLE exercises
ADD CONSTRAINT fk_exercises_lesson
FOREIGN KEY (lesson_id)
REFERENCES lessons(lesson_id)
ON DELETE CASCADE;

ALTER TABLE quiz_questions
DROP FOREIGN KEY quiz_questions_ibfk_1;

ALTER TABLE quiz_questions
ADD CONSTRAINT quiz_questions_ibfk_1
FOREIGN KEY (quiz_id)
REFERENCES quiz(quiz_id)
ON DELETE CASCADE;


ALTER TABLE quiz
ADD CONSTRAINT fk_quiz_lesson
FOREIGN KEY (lesson_id)
REFERENCES lessons(lesson_id)
ON DELETE CASCADE;

ALTER TABLE quiz_attempt
ADD CONSTRAINT fk_quiz_attempt_quiz
FOREIGN KEY (quiz_id)
REFERENCES quiz(quiz_id)
ON DELETE CASCADE;

ALTER TABLE unlock_quiz
DROP INDEX uniq_quiz;

ALTER TABLE unlock_quiz
ADD CONSTRAINT fk_unlock_quiz
FOREIGN KEY (quiz_id)
REFERENCES quiz(quiz_id)
ON DELETE CASCADE;

ALTER TABLE students
ADD CONSTRAINT fk_students_user
FOREIGN KEY (user_id)
REFERENCES users(user_id)
ON DELETE CASCADE;

ALTER TABLE teachers
ADD CONSTRAINT fk_teachers_user
FOREIGN KEY (user_id)
REFERENCES users(user_id)
ON DELETE CASCADE;

ALTER TABLE section_students
DROP FOREIGN KEY section_students_fk1;

ALTER TABLE section_students
ADD CONSTRAINT fk_section_students_section
FOREIGN KEY (section_id)
REFERENCES sections(section_id)
ON DELETE CASCADE;

ALTER TABLE section_courses
ADD CONSTRAINT fk_section_courses_course
FOREIGN KEY (course_id)
REFERENCES courses(course_id)
ON DELETE CASCADE;

ALTER TABLE questions
ADD CONSTRAINT fk_questions_course FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
ADD CONSTRAINT fk_questions_lesson FOREIGN KEY (lesson_id) REFERENCES lessons(lesson_id) ON DELETE CASCADE,
ADD CONSTRAINT fk_questions_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE;

ALTER TABLE quiz
ADD CONSTRAINT fk_quiz_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE;

ALTER TABLE course_code
ADD CONSTRAINT fk_course_code_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
ADD CONSTRAINT fk_course_code_exercise FOREIGN KEY (exercise_id) REFERENCES exercises(exercise_id) ON DELETE CASCADE;


-- 2026-4-8

ALTER TABLE unlock_quiz 
ADD COLUMN school_id INT NOT NULL AFTER quiz_id;

ALTER TABLE `lessons`  ADD `editor_url` VARCHAR(255) NULL DEFAULT NULL AFTER `student_game_file`;

-- 2026-4-13
ALTER TABLE `course_code` CHANGE `course_name` `course_name` VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `course_code` CHANGE `editor_type` `editor_type` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
