-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2025 at 03:21 PM
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
-- Database: `chineseschools`
--

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `menu`, `menu_order`) VALUES

(15, 'Dashboard', 2),
(16, 'Manage Grades', 3),
(17, 'Manage Teachers', 4),
(18, 'Manage Books', 5),
(19, 'Manage Students', 6),
(20, 'Lesson Permission', 7),
(21, 'Export Quiz', 8),
(22, 'Generate Quiz', 9),
(23, 'Manage Menus', 10),
(24, 'Manage Roles', 11),
(25, 'Manage Permissions', 12),
(26, 'Manage Quiz', 13),
(27, 'Manage Quetion Bank', 14),
(28, 'Manage Practices', 15),



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
(73, 'manage section'),
(74, 'lesson manage quiz'),
(75, 'quiz delete'),
(76, 'quiz attempt'),
(77, 'exam attempt'),
(78, 'quiz result view icon'),
(79, 'quiz dispatch'),
(80, 'question add'),
(81, 'question edit'),
(82, 'question delete');

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'siteadmin'),
(2, 'teacher'),
(3, 'student'),
(4, 'schooladmin'),
(6, 'superadmin');

--
-- Dumping data for table `role_menus`
--

INSERT INTO `role_menus` (`role_menu_id`, `role_id`, `menu_id`) VALUES
(6, 1, 15),
(7, 1, 16),
(14, 1, 18),
(15, 1, 19),
(16, 1, 17),
(17, 1, 20),
(19, 1, 21),
(20, 1, 22),
(22, 1, 23),
(23, 1, 25),
(24, 1, 28),
(25, 1, 27),
(26, 1, 26),
(27, 1, 24),
(28, 2, 15),
(29, 2, 18),
(30, 2, 16),
(32, 3, 15),
(36, 3, 21);

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_permission_id`, `role_id`, `permission_id`) VALUES
(2, 4, 1),
(3, 1, 1),
(4, 1, 7),
(6, 1, 8),
(7, 1, 16),
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
(27, 0, 16),
(28, 0, 50),
(29, 0, 48),
(30, 1, 48);

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `student_number`, `student_name`, `family_name`, `user_id`, `gender`) VALUES
(8, '123456', 'rohini s', 'sonawane', 6, 'Female'),
(9, '12345678', 'Rahul s', 'Patil', 7, 'Male'),
(10, '1234567891', 'pal sss', 'www', 8, 'Female'),
(16, '123444', 'dddddd ddd', 'dddd', 14, 'Male'),
(17, 'fff', 'fff fff', 'fff', 26, 'Male'),
(18, 'cvcxv', 'fgg fgg', 'fgd', 27, 'Male'),
(19, 'cxcx', 'dgdfg fgdfg', 'fgfg', 28, 'Male'),
(20, 'xcxzc', 'sdds sdsd', 'sdasd', 29, 'Male');

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`teacher_id`, `teacher_name`, `user_id`, `gender`) VALUES
(4, 'Rohini Shivade', 1, 'Female'),
(6, 'dd ddd', 15, 'Male'),
(12, 'w w', 24, 'Female');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `full_name`, `user_type`) VALUES
(1, 'r', '3IMyIPqz2FpCoagA9R64ow==', 'Rohini Shivade', 'teacher'),
(6, 'rohini', '3IMyIPqz2FpCoagA9R64ow==', 'rohini s sonawane', 'student'),
(7, 'rahul', '3IMyIPqz2FpCoagA9R64ow==', 'Rahul s Patil', 'student'),
(8, 'pal', '3IMyIPqz2FpCoagA9R64ow==', 'pal sss www', 'student'),
(14, 'HRMSJoy', 'LoESnbj/Wm2cZ492wVJ61w==', 'dddddd ddd dddd', 'student'),
(15, 'ddddd', '3IMyIPqz2FpCoagA9R64ow==', 'dd ddd', 'teacher'),
(16, 'schooladmin', '3IMyIPqz2FpCoagA9R64ow==', NULL, 'schooladmin'),
(17, 'siteadmin', '3IMyIPqz2FpCoagA9R64ow==', NULL, 'siteadmin'),
(24, 'w', '3IMyIPqz2FpCoagA9R64ow==', 'w w', 'teacher'),
(25, 'superadmin', '3IMyIPqz2FpCoagA9R64ow==', NULL, 'superadmin'),
(26, 'ff', '3IMyIPqz2FpCoagA9R64ow==', 'fff fff fff', 'student'),
(27, 'vcv', '3IMyIPqz2FpCoagA9R64ow==', 'fgg fgg fgd', 'student'),
(28, 'cxzc', '3IMyIPqz2FpCoagA9R64ow==', 'dgdfg fgdfg fgfg', 'student'),
(29, 'xccxz', '3IMyIPqz2FpCoagA9R64ow==', 'sdds sdsd sdasd', 'student');

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_role_id`, `role_id`, `user_id`) VALUES
(1, 2, 1),
(3, 2, 24),
(4, 3, 6),
(5, 3, 7),
(6, 3, 8),
(7, 3, 14),
(8, 2, 15),
(9, 4, 16),
(10, 1, 17),
(11, 6, 25),
(12, 3, 26),
(13, 3, 27),
(14, 3, 28),
(15, 3, 29);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


