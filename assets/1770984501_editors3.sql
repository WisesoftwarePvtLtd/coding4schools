-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2026 at 07:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chinese4school`
--

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
(17, 'Python', 'python', 'monaco', 1, 'editors/python_code_editor.php', '2026-02-02 08:16:47'),
(18, 'Virtual Reality Programming', NULL, NULL, 1, 'editors/virtual_editor.php', '2026-02-02 08:19:08'),
(20, 'Introduction to JavaScript gr8', 'javascript', 'monaco', 1, 'editors/introduction_to_javascript_gr8_editor.php', '2026-02-10 09:12:10'),
(21, 'Java Processing p5 js', 'javascript', 'monaco', 1, 'editors/javaP5_editor.php', '2026-02-10 09:14:17'),
(22, 'Game Development in JavaScript', 'javascript', 'monaco', 1, 'editors/game_dev_js_editor.php', '2026-02-10 09:15:52'),
(23, '3D Drawing', 'javascript', 'monaco', 0, 'editors/3d_drawing_editor.php', '2026-02-10 13:54:33'),
(24, 'Introduction to Data Analytics', 'python', 'monaco', 0, 'editors/introduction_to_data_analytics_editor.php', '2026-02-11 05:20:05'),
(25, 'AI for Juniors', 'python', 'monaco', 0, 'editors/ai_for_juniors_editor.php', '2026-02-11 05:37:43'),
(26, 'Data Visualisation', NULL, NULL, 0, 'data_visualisation.php', '2026-02-11 05:39:40'),
(27, 'Microbit Programming', NULL, NULL, 0, 'microbitprog.php', '2026-02-11 05:44:29'),
(28, 'Microbit JavaScript', NULL, NULL, 0, 'microbitJs.php', '2026-02-11 05:44:47'),
(29, 'CyberPi', NULL, NULL, 0, 'cyberpi.php', '2026-02-11 05:45:04'),
(30, 'mBot Neo', NULL, NULL, 0, 'mbotneo.php', '2026-02-11 05:45:24'),
(31, 'Tinybit AI Vision', NULL, NULL, 0, 'tinybit_ai.php', '2026-02-11 05:45:40'),
(32, 'Scratch', NULL, NULL, 1, 'scratch.php', '2026-02-11 05:46:44'),
(33, 'Sphero Bolt', NULL, NULL, 0, 'spherobolt.php', '2026-02-11 05:47:02'),
(34, 'mBot Robot Coding', NULL, NULL, 0, 'mbot.php', '2026-02-11 05:47:17'),
(35, 'Arduino', NULL, NULL, 1, 'arduino.php', '2026-02-11 05:47:33'),
(36, 'Introduction to Coding Logic', NULL, NULL, 0, 'coding_logic.php', '2026-02-11 05:48:12'),
(37, 'Introduction to Artificial Intelligence', NULL, NULL, 0, 'introduction_to_ai.php', '2026-02-11 05:49:19'),
(38, 'Makey Makey', NULL, NULL, 1, 'makeymakey.php', '2026-02-11 06:07:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `editors`
--
ALTER TABLE `editors`
  ADD PRIMARY KEY (`editor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `editors`
--
ALTER TABLE `editors`
  MODIFY `editor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
