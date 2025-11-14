-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2025 at 12:19 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `egazette`
--

-- --------------------------------------------------------

--
-- Table structure for table `gz_activity_log`
--

CREATE TABLE `gz_activity_log` (
  `id` int(11) NOT NULL,
  `log` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `module` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store activity log';

-- --------------------------------------------------------

--
-- Table structure for table `gz_applicants_details`
--

CREATE TABLE `gz_applicants_details` (
  `id` int(55) NOT NULL,
  `login_id` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL COMMENT '0="Not Login", 1="Login"',
  `name` varchar(255) NOT NULL,
  `relation_id` int(55) DEFAULT NULL,
  `father_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `module_id` int(11) NOT NULL COMMENT 'Link to gz_modules',
  `mobile` varchar(10) NOT NULL,
  `email` varchar(96) NOT NULL,
  `address` varchar(100) NOT NULL,
  `sms_request_count` int(200) NOT NULL,
  `last_sms_request_time` datetime NOT NULL,
  `blocked_until` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `is_logged` tinyint(1) NOT NULL,
  `otp_verified` tinyint(1) DEFAULT NULL,
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_applicant_otp`
--

CREATE TABLE `gz_applicant_otp` (
  `id` int(11) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `otp` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `expired_at` datetime NOT NULL,
  `verification_code` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store gazette user login otp';

-- --------------------------------------------------------

--
-- Table structure for table `gz_applicant_password_history`
--

CREATE TABLE `gz_applicant_password_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `password` varchar(500) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table to store user password history';

-- --------------------------------------------------------

--
-- Table structure for table `gz_archival_extraordinary_gazettes`
--

CREATE TABLE `gz_archival_extraordinary_gazettes` (
  `id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `notification_type_id` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `notification_number` varchar(255) DEFAULT NULL,
  `gazette_number` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `gazette_file` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gz_archival_weekly_gazettes`
--

CREATE TABLE `gz_archival_weekly_gazettes` (
  `id` int(11) NOT NULL,
  `week_id` enum('1','2','3','4','5') DEFAULT NULL,
  `notification_number` varchar(255) DEFAULT NULL,
  `gazette_number` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `gazette_file` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gz_block_master`
--

CREATE TABLE `gz_block_master` (
  `id` int(11) NOT NULL,
  `district_id` int(11) DEFAULT NULL COMMENT 'link distict master table as foreign key',
  `block_name` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `block_code` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unique_id` int(55) NOT NULL COMMENT '	Assign unique code to every block',
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `district_code` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district_unique_id` int(55) NOT NULL COMMENT '	make foreign key connection with unique_id with district master table',
  `change_request` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store blocks ';

--
-- Dumping data for table `gz_block_master`
--

INSERT INTO `gz_block_master` (`id`, `district_id`, `block_name`, `block_code`, `unique_id`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`, `deleted`, `district_code`, `district_unique_id`, `change_request`) VALUES
(1, 1, 'Anugul', '3276', 1, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2401', 1, 0),
(2, 1, 'Athmallik', '3277', 2, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2401', 1, 0),
(3, 1, 'Banarpal', '3278', 3, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2401', 1, 0),
(4, 1, 'Chhendipada', '3279', 4, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2401', 1, 0),
(5, 1, 'Kaniha', '3280', 5, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2401', 1, 0),
(6, 1, 'Kishorenagar', '3281', 6, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2401', 1, 0),
(7, 1, 'Palalahada', '3282', 7, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2401', 1, 0),
(8, 1, 'Talacher', '3283', 8, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2401', 1, 0),
(9, 2, 'Agalpur', '3284', 9, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(10, 2, 'Balangir', '3285', 10, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(11, 2, 'Bangomunda', '3286', 11, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(12, 2, 'Belpara', '3287', 12, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(13, 2, 'Deogaon', '3288', 13, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(14, 2, 'Gudvella', '3289', 14, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(15, 2, 'Khaprakhol', '3290', 15, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(16, 2, 'Loisinga', '3291', 16, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(17, 2, 'Muribahal', '3292', 17, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(18, 2, 'Patnagarh', '3293', 18, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(19, 2, 'Puintala', '3294', 19, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(20, 2, 'Saintala', '3295', 20, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(21, 2, 'Titlagarh', '3296', 21, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(22, 2, 'Turekela', '3297', 22, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2402', 2, 0),
(23, 3, 'Bahanaga', '3298', 23, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(24, 3, 'Baleshwar', '3299', 24, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(25, 3, 'Baliapal', '3300', 25, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(26, 3, 'Basta', '3301', 26, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(27, 3, 'Bhograi', '3302', 27, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(28, 3, 'Jaleswar', '3303', 28, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(29, 3, 'Khaira', '3304', 29, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(30, 3, 'Nilgiri', '3305', 30, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(31, 3, 'Oupada', '3306', 31, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(32, 3, 'Remuna', '3307', 32, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(33, 3, 'Simulia', '3308', 33, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(34, 3, 'Soro', '3309', 34, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2403', 3, 0),
(35, 4, 'Ambabhona', '3310', 35, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2404', 4, 0),
(36, 4, 'Attabira', '3311', 36, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2404', 4, 0),
(37, 4, 'Bargarh', '3312', 37, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2404', 4, 0),
(38, 4, 'Barpali', '3313', 38, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2404', 4, 0),
(39, 4, 'Bhatli', '3314', 39, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2404', 4, 0),
(40, 4, 'Bheden', '3315', 40, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2404', 4, 0),
(41, 4, 'Bijepur', '3316', 41, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2404', 4, 0),
(42, 4, 'Gaisilet', '3317', 42, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2404', 4, 0),
(43, 4, 'Jharbandh', '3318', 43, 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2404', 4, 0),
(44, 4, 'Padampur', '3319', 44, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2404', 4, 0),
(45, 4, 'Paikmal', '3320', 45, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2404', 4, 0),
(46, 4, 'Sohella', '3321', 46, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2404', 4, 0),
(47, 5, 'Basudevpur', '3322', 47, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2405', 5, 0),
(48, 5, 'Bhadrak', '3323', 48, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2405', 5, 0),
(49, 5, 'Bhandaripokhari', '3324', 49, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2405', 5, 0),
(50, 5, 'Bonth', '3325', 50, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2405', 5, 0),
(51, 5, 'Chandabali', '3326', 51, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2405', 5, 0),
(52, 5, 'Dhamanagar', '3327', 52, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2405', 5, 0),
(53, 5, 'Tihidi', '3328', 53, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2405', 5, 0),
(54, 6, 'Boudh', '3329', 54, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2406', 6, 0),
(55, 6, 'Harabhanga', '3330', 55, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2406', 6, 0),
(56, 6, 'Kantamal', '3331', 56, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2406', 6, 0),
(57, 7, 'Athagad', '3332', 57, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(58, 7, 'Badamba', '3333', 58, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(59, 7, 'Banki', '3334', 59, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(60, 7, 'Banki- Dampara', '3335', 60, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(61, 7, 'Baranga', '3336', 61, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(62, 7, 'Cuttacksadar', '3337', 62, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(63, 7, 'Kantapada', '3338', 63, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(64, 7, 'Mahanga', '3339', 64, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(65, 7, 'Narasinghpur', '3340', 65, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(66, 7, 'Niali', '3341', 66, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(67, 7, 'Nischinta Koili', '3342', 67, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(68, 7, 'Salepur', '3343', 68, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(69, 7, 'Tangi Choudwar', '3344', 69, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(70, 7, 'Tigiria', '3345', 70, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2407', 7, 0),
(71, 8, 'Barkote', '3346', 71, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2408', 8, 0),
(72, 8, 'Reamal', '3347', 72, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2408', 8, 0),
(73, 8, 'Tileibani', '3348', 73, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2408', 8, 0),
(74, 9, 'Bhuban', '3349', 74, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2409', 9, 0),
(75, 9, 'Dhenkanal Sadar', '3350', 75, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2409', 9, 0),
(76, 9, 'Gondia', '3351', 76, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2409', 9, 0),
(77, 9, 'Hindol', '3352', 77, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2409', 9, 0),
(78, 9, 'Kamakhyanagar', '3353', 78, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2409', 9, 0),
(79, 9, 'Kankada Had', '3354', 79, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2409', 9, 0),
(80, 9, 'Odapada', '3355', 80, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2409', 9, 0),
(81, 9, 'Parjang', '3356', 81, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2409', 9, 0),
(82, 10, 'Gosani', '3357', 82, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2410', 10, 0),
(83, 10, 'Gumma', '3358', 83, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2410', 10, 0),
(84, 10, 'Kasinagar', '3359', 84, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2410', 10, 0),
(85, 10, 'Mohana', '3360', 85, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2410', 10, 0),
(86, 10, 'Nuagada', '3361', 86, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2410', 10, 0),
(87, 10, 'Rayagada', '3363', 87, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2410', 10, 0),
(88, 10, 'R.Udayagiri', '3362', 88, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2410', 10, 0),
(89, 11, 'Aska', '3364', 89, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(90, 11, 'Beguniapada', '3365', 90, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(91, 11, 'Bellaguntha', '3366', 91, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(92, 11, 'Bhanjanagar', '3367', 92, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(93, 11, 'Buguda', '3368', 93, 0, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 11, 0),
(94, 11, 'Chatrapur', '3369', 94, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(95, 11, 'Chikiti', '3370', 95, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(96, 11, 'Dharakote', '3371', 96, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(97, 11, 'Digapahandi', '3372', 97, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(98, 11, 'Ganjam', '3373', 98, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(99, 11, 'Hinjilicut', '3374', 99, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(100, 11, 'Jagannathprasad', '3375', 100, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(101, 11, 'Kabisuryanagar', '3376', 101, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(102, 11, 'Khallikote', '3377', 102, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(103, 11, 'Kukudakhandi', '3378', 103, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(104, 11, 'Patrapur', '3379', 104, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(105, 11, 'Polosara', '3380', 105, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(106, 11, 'Purushottampur', '3381', 106, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(107, 11, 'Rangeilunda', '3382', 107, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(108, 11, 'Sanakhemundi', '3383', 108, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(109, 11, 'Sheragada', '3384', 109, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(110, 11, 'Surada', '3385', 110, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2411', 11, 0),
(111, 12, 'Balikuda', '3386', 111, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2412', 12, 0),
(112, 12, 'Biridi', '3387', 112, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2412', 12, 0),
(113, 12, 'Erasama', '3388', 113, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2412', 12, 0),
(114, 12, 'Jagatsinghpur', '3389', 114, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2412', 12, 0),
(115, 12, 'Kujang', '3390', 115, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2412', 12, 0),
(116, 12, 'Naugaon', '3391', 116, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2412', 12, 0),
(117, 12, 'Raghunathpur', '3392', 117, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2412', 12, 0),
(118, 12, 'Tirtol', '3393', 118, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2412', 12, 0),
(119, 13, 'Badchana', '3394', 119, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2413', 13, 0),
(120, 13, 'Bari', '3395', 120, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2413', 13, 0),
(121, 13, 'Binjharpur', '3396', 121, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2413', 13, 0),
(122, 13, 'Dahrmasala', '3397', 122, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2413', 13, 0),
(123, 13, 'Danagadi', '3398', 123, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2413', 13, 0),
(124, 13, 'Dasarathapur', '3399', 124, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2413', 13, 0),
(125, 13, 'Jajpur', '3400', 125, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2413', 13, 0),
(126, 13, 'Korei', '3401', 126, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2413', 13, 0),
(127, 13, 'Rasulpur', '3402', 127, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2413', 13, 0),
(128, 13, 'Sukinda', '3403', 128, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2413', 13, 0),
(129, 14, 'Jharsuguda', '3404', 129, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2414', 14, 0),
(130, 14, 'Kirmira', '3405', 130, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2414', 14, 0),
(131, 14, 'Kolabira', '3406', 131, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2414', 14, 0),
(132, 14, 'Laikera', '3407', 132, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2414', 14, 0),
(133, 14, 'Lakhanpur', '3408', 133, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2414', 14, 0),
(134, 15, 'Bhawanipatna', '3409', 134, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(135, 15, 'Dharamagarh', '3410', 135, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(136, 15, 'Golamunda', '3411', 136, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(137, 15, 'Jayapatna', '3412', 137, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(138, 15, 'Junagarh', '3413', 138, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(139, 15, 'Kalampur', '3414', 139, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(140, 15, 'Karlamunda', '3415', 140, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(141, 15, 'Kesinga', '3416', 141, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(142, 15, 'Kokasara', '3417', 142, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(143, 15, 'Lanjigarh', '3418', 143, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(144, 15, 'Madanpur Rampur', '3419', 144, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(145, 15, 'Narala', '3420', 145, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(146, 15, 'Thuamul Ram Pur', '3421', 146, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2415', 15, 0),
(147, 16, 'Baliguda', '3422', 147, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(148, 16, 'Chakapad', '3423', 148, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(149, 16, 'Daringibadi', '3424', 149, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(150, 16, 'G.Udayagiri', '3425', 150, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(151, 16, 'Khajuripada', '3427', 151, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(152, 16, 'K.Nuagan', '3426', 152, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(153, 16, 'Kotagarh', '3428', 153, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(154, 16, 'Phiringia', '3429', 154, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(155, 16, 'Phulbani', '3430', 155, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(156, 16, 'Raikia', '3431', 156, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(157, 16, 'Tikabali', '3432', 157, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(158, 16, 'Tumudibandh', '3433', 158, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2416', 16, 0),
(159, 17, 'Aul', '3434', 159, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2417', 17, 0),
(160, 17, 'Derabish', '3435', 160, 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2417', 17, 0),
(161, 17, 'Garadapur', '3436', 161, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2417', 17, 0),
(162, 17, 'Kendrapada', '3437', 162, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2417', 17, 0),
(163, 17, 'Mahakalapada', '3438', 163, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2417', 17, 0),
(164, 17, 'Marsaghai', '3439', 164, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2417', 17, 0),
(165, 17, 'Pattamundai', '3440', 165, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2417', 17, 0),
(166, 17, 'Rajkanika', '3441', 166, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2417', 17, 0),
(167, 17, 'Rajnagar', '3442', 167, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2417', 17, 0),
(168, 18, 'Anandapur', '3443', 168, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(169, 18, 'Bansapal', '3444', 169, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(170, 18, 'Champua', '3445', 170, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(171, 18, 'Ghasipura', '3446', 171, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(172, 18, 'Ghatgaon', '3447', 172, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(173, 18, 'Harichadanpur', '3448', 173, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(174, 18, 'Hatadihi', '3449', 174, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(175, 18, 'Jhumpura', '3450', 175, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(176, 18, 'Joda', '3451', 176, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(177, 18, 'Kendujhar Sadar', '3452', 177, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(178, 18, 'Patana', '3453', 178, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(179, 18, 'Saharapada', '3454', 179, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(180, 18, 'Telkoi', '3455', 180, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2418', 18, 0),
(181, 19, 'Balianta', '3456', 181, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2419', 19, 0),
(182, 19, 'Balipatna', '3457', 182, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2419', 19, 0),
(183, 19, 'Banapur', '3458', 183, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2419', 19, 0),
(184, 19, 'Begunia', '3459', 184, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2419', 19, 0),
(185, 19, 'Bhubaneswar', '3460', 185, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2419', 19, 0),
(186, 19, 'Bolagarh', '3461', 186, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2419', 19, 0),
(187, 19, 'Chilika', '3462', 187, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2419', 19, 0),
(188, 19, 'Jatni', '3463', 188, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2419', 19, 0),
(189, 19, 'Khordha', '3464', 189, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2419', 19, 0),
(190, 19, 'Tangi', '3465', 190, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2419', 19, 0),
(191, 20, 'Bandhugaon', '3466', 191, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(192, 20, 'Boipariguda', '3467', 192, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(193, 20, 'Borigumma', '3468', 193, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(194, 20, 'Dasamantapur', '3469', 194, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(195, 20, 'Jeypore', '3470', 195, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(196, 20, 'Koraput', '3471', 196, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(197, 20, 'Kotpad', '3472', 197, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(198, 20, 'Kundura', '3473', 198, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(199, 20, 'Lamtaput', '3474', 199, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(200, 20, 'Laxmipur', '3475', 200, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(201, 20, 'Nandapur', '3476', 201, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(202, 20, 'Narayan Patana', '3477', 202, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(203, 20, 'Pottangi', '3478', 203, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(204, 20, 'Semiliguda', '3479', 204, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2420', 20, 0),
(205, 21, 'Chitrakonda', '3483', 205, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2421', 21, 0),
(206, 21, 'Kalimela', '3480', 206, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2421', 21, 0),
(207, 21, 'Khairaput', '3481', 207, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2421', 21, 0),
(208, 21, 'Korukonda', '3482', 208, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2421', 21, 0),
(209, 21, 'Malkangiri', '3484', 209, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2421', 21, 0),
(210, 21, 'Mathili', '3485', 210, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2421', 21, 0),
(211, 21, 'Podia', '3486', 211, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2421', 21, 0),
(212, 22, 'Badasahi', '3487', 212, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(213, 22, 'Bahalda', '3488', 213, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(214, 22, 'Bangriposi', '3489', 214, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(215, 22, 'Baripada', '3490', 215, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(216, 22, 'Betnoti', '3491', 216, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(217, 22, 'Bijatala', '3492', 217, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(218, 22, 'Bisoi', '3493', 218, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(219, 22, 'Gopabandhunagar', '3494', 219, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(220, 22, 'Jamda', '3495', 220, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(221, 22, 'Joshipur', '3496', 221, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(222, 22, 'Kaptipada', '3497', 222, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(223, 22, 'Karanjia', '3498', 223, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(224, 22, 'Khunta', '3499', 224, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(225, 22, 'Kuliana', '3500', 225, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(226, 22, 'Kusumi', '3501', 226, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(227, 22, 'Morada', '3502', 227, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(228, 22, 'Rairangpur', '3503', 228, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(229, 22, 'Raruan', '3504', 229, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(230, 22, 'Rasgovindpur', '3505', 230, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(231, 22, 'Samakhunta', '3506', 231, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(232, 22, 'Saraskana', '3507', 232, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(233, 22, 'Sukruli', '3508', 233, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(234, 22, 'Suliapada', '3509', 234, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(235, 22, 'Thakurmunda', '3510', 235, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(236, 22, 'Tiring', '3511', 236, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(237, 22, 'Udala', '3512', 237, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2422', 22, 0),
(238, 23, 'Chandahandi', '3513', 238, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2423', 23, 0),
(239, 23, 'Dabugam', '3514', 239, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2423', 23, 0),
(240, 23, 'Jharigam', '3515', 240, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2423', 23, 0),
(241, 23, 'Kosagumuda', '3516', 241, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2423', 23, 0),
(242, 23, 'Nabarangpur', '3517', 242, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2423', 23, 0),
(243, 23, 'Nandahandi', '3518', 243, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2423', 23, 0),
(244, 23, 'Papadahandi', '3519', 244, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2423', 23, 0),
(245, 23, 'Raighar', '3520', 245, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2423', 23, 0),
(246, 23, 'Tentulikhunti', '3521', 246, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2423', 23, 0),
(247, 23, 'Umerkote', '3522', 247, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2423', 23, 0),
(248, 24, 'Bhapur', '3523', 248, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2424', 24, 0),
(249, 24, 'Dasapalla', '3524', 249, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2424', 24, 0),
(250, 24, 'Gania', '3525', 250, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2424', 24, 0),
(251, 24, 'Khandapara', '3526', 251, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2424', 24, 0),
(252, 24, 'Nayagarh', '3527', 252, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2424', 24, 0),
(253, 24, 'Nuagaon', '3528', 253, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2424', 24, 0),
(254, 24, 'Odagaon', '3529', 254, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2424', 24, 0),
(255, 24, 'Ranapur', '3530', 255, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2424', 24, 0),
(256, 25, 'Boden', '3531', 256, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2425', 25, 0),
(257, 25, 'Khariar', '3532', 257, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2425', 25, 0),
(258, 25, 'Komna', '3533', 258, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2425', 25, 0),
(259, 25, 'Nuapada', '3534', 259, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2425', 25, 0),
(260, 25, 'Sinapali', '3535', 260, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2425', 25, 0),
(261, 26, 'Astaranga', '3536', 261, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2426', 26, 0),
(262, 26, 'Brahmagiri', '3537', 262, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2426', 26, 0),
(263, 26, 'Delanga', '3538', 263, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2426', 26, 0),
(264, 26, 'Gop', '3539', 264, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2426', 26, 0),
(265, 26, 'Kakat Pur', '3540', 265, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2426', 26, 0),
(266, 26, 'Kanas', '3541', 266, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2426', 26, 0),
(267, 26, 'Krushnaprasad', '3542', 267, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2426', 26, 0),
(268, 26, 'Nimapada', '3543', 268, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2426', 26, 0),
(269, 26, 'Pipili', '3544', 269, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2426', 26, 0),
(270, 26, 'Sadar', '3545', 270, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2426', 26, 0),
(271, 26, 'Satyabadi', '3546', 271, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2426', 26, 0),
(272, 27, 'Bissamcuttack', '3547', 272, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2427', 27, 0),
(273, 27, 'Chandrapur', '3548', 273, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2427', 27, 0),
(274, 27, 'Gudari', '3549', 274, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2427', 27, 0),
(275, 27, 'Gunupur', '3550', 275, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2427', 27, 0),
(276, 27, 'Kalyansingpur', '3551', 276, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2427', 27, 0),
(277, 27, 'Kasipur', '3552', 277, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2427', 27, 0),
(278, 27, 'Kolnara', '3553', 278, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2427', 27, 0),
(279, 27, 'Muniguda', '3554', 279, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2427', 27, 0),
(280, 27, 'Padmapur', '3555', 280, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2427', 27, 0),
(281, 27, 'Ramanaguda', '3556', 281, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2427', 27, 0),
(282, 27, 'Rayagada', '3557', 282, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2427', 27, 0),
(283, 28, 'Bamra', '3558', 283, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2428', 28, 0),
(284, 28, 'Dhankauda', '3559', 284, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2428', 28, 0),
(285, 28, 'Jamankira', '3560', 285, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2428', 28, 0),
(286, 28, 'Jujomura', '3561', 286, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2428', 28, 0),
(287, 28, 'Kuchinda', '3562', 287, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2428', 28, 0),
(288, 28, 'Maneswar', '3563', 288, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2428', 28, 0),
(289, 28, 'Naktideul', '3564', 289, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2428', 28, 0),
(290, 28, 'Rairakhol', '3565', 290, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2428', 28, 0),
(291, 28, 'Rengali', '3566', 291, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2428', 28, 0),
(292, 29, 'Binika', '3567', 292, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2429', 29, 0),
(293, 29, 'Birmaharajpur', '3568', 293, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2429', 29, 0),
(294, 29, 'Dunguripali', '3569', 294, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2429', 29, 0),
(295, 29, 'Sonepur', '3570', 295, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2429', 29, 0),
(296, 29, 'Tarbha', '3571', 296, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2429', 29, 0),
(297, 29, 'Ullunda', '3572', 297, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2429', 29, 0),
(298, 30, 'Balisankara', '3573', 298, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(299, 30, 'Bargaon', '3574', 299, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(300, 30, 'Bisra', '3575', 300, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(301, 30, 'Bonaigarh', '3576', 301, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(302, 30, 'Gurundia', '3577', 302, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(303, 30, 'Hemgir', '3578', 303, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(304, 30, 'Koida', '3579', 304, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(305, 30, 'Kuarmunda', '3580', 305, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(306, 30, 'Kutra', '3581', 306, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(307, 30, 'Lahunipara', '3582', 307, 0, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2430', 30, 0),
(308, 30, 'Lathikata', '3583', 308, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(309, 30, 'Lephripara', '3584', 309, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(310, 30, 'Nuagaon', '3585', 310, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(311, 30, 'Rajgangpur', '3586', 311, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(312, 30, 'Subdega', '3587', 312, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(313, 30, 'Sundargarh', '3588', 313, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0),
(314, 30, 'Tangarpali', '3589', 314, 1, 1, '0000-00-00 00:00:00', NULL, NULL, 0, '2430', 30, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_applicant_gender_notice_details`
--

CREATE TABLE `gz_change_of_applicant_gender_notice_details` (
  `id` int(55) NOT NULL,
  `place` varchar(100) DEFAULT NULL,
  `date` text DEFAULT NULL,
  `salutation` varchar(100) DEFAULT NULL,
  `name_for_notice` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `old_gender_1` varchar(100) DEFAULT NULL,
  `new_gender` varchar(100) DEFAULT NULL,
  `new_gender_1` varchar(100) DEFAULT NULL,
  `old_name_2` varchar(50) DEFAULT NULL,
  `new_name_2` varchar(50) DEFAULT NULL,
  `old_gender_2` varchar(50) DEFAULT NULL,
  `new_gender_2` varchar(50) DEFAULT NULL,
  `new_name_3` varchar(50) DEFAULT NULL,
  `new_gender_3` varchar(50) DEFAULT NULL,
  `signature` varchar(100) DEFAULT NULL,
  `son_daughter` varchar(20) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `gender_his_her` varchar(20) DEFAULT NULL,
  `approver` varchar(50) DEFAULT NULL,
  `address` varchar(80) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `change_gender_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_gender_document_det`
--

CREATE TABLE `gz_change_of_gender_document_det` (
  `id` int(11) NOT NULL,
  `gz_master_id` varchar(255) NOT NULL,
  `affidavit` varchar(255) NOT NULL,
  `original_newspaper` varchar(255) NOT NULL,
  `notice_in_softcopy` varchar(255) NOT NULL,
  `medical_cert` varchar(255) NOT NULL,
  `id_proof_doc` varchar(255) NOT NULL,
  `address_proof_doc` varchar(255) NOT NULL,
  `approval_authority` varchar(255) NOT NULL,
  `deed_changing_form` varchar(255) NOT NULL,
  `age_proof` varchar(255) NOT NULL,
  `notice_softcopy_pdf` varchar(255) NOT NULL,
  `press_notice_in_softcopy_word` varchar(255) NOT NULL,
  `press_pdf` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_gender_document_master`
--

CREATE TABLE `gz_change_of_gender_document_master` (
  `id` int(55) NOT NULL,
  `document_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `created_by` int(55) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(55) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gz_change_of_gender_document_master`
--

INSERT INTO `gz_change_of_gender_document_master` (`id`, `document_name`, `created_by`, `created_at`, `modified_by`, `modified_at`, `status`, `deleted`) VALUES
(1, 'Affidavit', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(2, 'Original Newspaper', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(3, 'Notice in Softcopy', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(4, 'Medical Certificate', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(6, 'ID/Address Proof Document', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(7, 'Deed Changing Form', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(8, 'Age Proof', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(9, 'Approval Authority Document', 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(10, 'Notice in Softcopy PDF', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_gender_history`
--

CREATE TABLE `gz_change_of_gender_history` (
  `id` int(11) NOT NULL,
  `gz_master_id` varchar(255) NOT NULL,
  `gz_docu_id` int(11) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_gender_master`
--

CREATE TABLE `gz_change_of_gender_master` (
  `id` int(11) NOT NULL,
  `sl_no` int(55) NOT NULL,
  `gazette_type_id` int(11) NOT NULL,
  `state_id` int(55) DEFAULT NULL,
  `district_id` int(55) DEFAULT NULL,
  `police_station_id` int(55) DEFAULT NULL,
  `address_1` varchar(255) DEFAULT NULL,
  `address_2` varchar(255) DEFAULT NULL,
  `address_3` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `file_no` varchar(255) NOT NULL,
  `govt_employee` tinyint(1) NOT NULL COMMENT '1 - yes, 2 - no',
  `is_minor` tinyint(1) NOT NULL COMMENT '1 - yes, 0 - no',
  `is_name_change` tinyint(1) NOT NULL COMMENT '1 - yes, 0 - no',
  `current_status` int(11) NOT NULL COMMENT 'Link to change of name surname status master',
  `press_signed_pdf` varchar(255) NOT NULL,
  `is_published` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `type` varchar(55) DEFAULT NULL,
  `block_ulb_id` int(11) DEFAULT NULL,
  `notice_softcopy_doc` varchar(255) DEFAULT NULL,
  `pin_code` int(55) DEFAULT NULL,
  `saka_date` varchar(255) DEFAULT NULL,
  `offline_pay_status` int(11) NOT NULL DEFAULT 0 COMMENT '0-none,1-pending_payment,2-success_payment',
  `total_price_to_paid` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_gender_payment_details`
--

CREATE TABLE `gz_change_of_gender_payment_details` (
  `id` int(11) NOT NULL,
  `change_gender_id` int(11) NOT NULL,
  `file_number` varchar(255) NOT NULL,
  `dept_ref_id` varchar(255) NOT NULL,
  `challan_ref_id` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `pay_mode` varchar(255) DEFAULT NULL,
  `bank_trans_id` varchar(255) NOT NULL,
  `bank_name` tinytext DEFAULT NULL,
  `bank_trans_msg` tinytext DEFAULT NULL,
  `bank_trans_time` datetime DEFAULT NULL,
  `trans_status` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `hoa` varchar(255) NOT NULL,
  `challan_no` varchar(255) NOT NULL,
  `challan_dt` date NOT NULL,
  `challan_amt` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_gender_remarks_master`
--

CREATE TABLE `gz_change_of_gender_remarks_master` (
  `id` int(11) NOT NULL,
  `change_of_gender_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `status_history_id` int(11) NOT NULL,
  `remarks` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_gender_status_his`
--

CREATE TABLE `gz_change_of_gender_status_his` (
  `id` int(11) NOT NULL,
  `gz_master_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `change_of_gender_status` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_gender_status_master`
--

CREATE TABLE `gz_change_of_gender_status_master` (
  `id` int(55) NOT NULL,
  `status_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_change_of_gender_status_master`
--

INSERT INTO `gz_change_of_gender_status_master` (`id`, `status_name`, `status`, `deleted`) VALUES
(1, 'Submmitted Successfully', 1, 0),
(2, 'Forward by C & T Processor to Verifier', 1, 0),
(3, 'C & T Processor User Rejected', 1, 0),
(4, 'Verifier Forward to C & T Approver', 1, 0),
(5, 'C & T Verifier User Rejected', 1, 0),
(6, 'Applicant Resubmmitted', 1, 0),
(7, 'C & T Approver Approved', 1, 0),
(8, 'C & T Approver User Rejected', 1, 0),
(9, 'Forward To Pay', 1, 0),
(10, 'Payment Completed', 1, 0),
(11, 'Published', 1, 0),
(12, 'C & T Processor Reject Request Approved', 1, 0),
(13, 'C & T Verifier Reject Request Approved', 1, 0),
(14, 'Returned To Applicant', 1, 0),
(15, 'Payment Pending', 1, 0),
(16, 'Payment Fail', 1, 0),
(17, 'Press Approved', 1, 0),
(18, 'Application Resubmitted(Processor)', 1, 0),
(19, 'Application Resubmitted(Verifier)', 1, 0),
(20, 'Application Resubmitted(Approver)', 1, 0),
(21, 'Rejected', 1, 0),
(22, 'Application Returned (Processor)', 1, 0),
(23, 'Application Returned (Verifier)', 1, 0),
(24, 'Application Returned (Approver)', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_name_surname_document_master`
--

CREATE TABLE `gz_change_of_name_surname_document_master` (
  `id` int(55) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `created_by` int(55) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(55) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_change_of_name_surname_document_master`
--

INSERT INTO `gz_change_of_name_surname_document_master` (`id`, `document_name`, `created_by`, `created_at`, `modified_by`, `modified_at`, `status`, `deleted`) VALUES
(1, 'Affidavit', 1, '2019-11-20 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(2, 'Original Newspaper', 1, '2019-11-20 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(3, 'Notice in Softcopy', 1, '2019-11-20 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(4, 'ID Proof and Address Proof Document', 1, '2019-11-20 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(5, 'Approval Authority Document  ', 1, '2019-11-20 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(6, 'Deed Changing Form  ', 1, '2019-11-20 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(7, 'Age Proof', 1, '2019-11-20 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(8, 'Notice in Softcopy (PDF)', 1, '2019-11-20 00:00:00', 0, '0000-00-00 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_name_surname_doument_det`
--

CREATE TABLE `gz_change_of_name_surname_doument_det` (
  `id` int(11) NOT NULL,
  `gz_master_id` int(11) NOT NULL,
  `affidavit` varchar(255) NOT NULL,
  `original_newspaper` varchar(255) NOT NULL,
  `notice_in_softcopy` varchar(255) NOT NULL,
  `kyc_document` varchar(255) NOT NULL,
  `approval_authority` varchar(255) NOT NULL,
  `deed_changing_form` varchar(255) NOT NULL,
  `birth_certificate` varchar(255) NOT NULL,
  `notice_softcopy_pdf` varchar(255) NOT NULL,
  `press_notice_in_softcopy_word` varchar(255) NOT NULL,
  `press_pdf` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_name_surname_history`
--

CREATE TABLE `gz_change_of_name_surname_history` (
  `id` int(11) NOT NULL,
  `gz_master_id` varchar(255) NOT NULL,
  `gz_docu_id` int(11) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_name_surname_master`
--

CREATE TABLE `gz_change_of_name_surname_master` (
  `id` int(11) NOT NULL,
  `sl_no` int(55) NOT NULL,
  `gazette_type_id` int(11) NOT NULL,
  `state_id` int(55) DEFAULT NULL,
  `district_id` int(55) DEFAULT NULL,
  `police_station_id` int(55) DEFAULT NULL,
  `address_1` varchar(255) DEFAULT NULL,
  `address_2` varchar(255) DEFAULT NULL,
  `address_3` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `file_no` varchar(255) NOT NULL,
  `govt_employee` tinyint(1) NOT NULL COMMENT '1 - yes, 2 - no',
  `is_minor` tinyint(1) NOT NULL COMMENT '1 - yes, 2 - no',
  `current_status` int(11) NOT NULL COMMENT 'Link to change of name surname status master',
  `press_signed_pdf` varchar(255) NOT NULL,
  `is_published` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `type` varchar(55) DEFAULT NULL,
  `block_ulb_id` int(11) DEFAULT NULL,
  `notice_softcopy_doc` varchar(255) DEFAULT NULL,
  `pin_code` int(55) DEFAULT NULL,
  `saka_date` varchar(255) DEFAULT NULL,
  `offline_pay_status` int(11) NOT NULL DEFAULT 0 COMMENT '0-none,1-pending_payment,2-success_payment',
  `total_price_to_paid` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_name_surname_payment_details`
--

CREATE TABLE `gz_change_of_name_surname_payment_details` (
  `id` int(11) NOT NULL,
  `change_name_surname_id` int(11) NOT NULL,
  `file_number` varchar(255) NOT NULL,
  `dept_ref_id` varchar(255) NOT NULL,
  `challan_ref_id` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `pay_mode` varchar(255) DEFAULT NULL,
  `bank_trans_id` varchar(255) NOT NULL,
  `bank_name` tinytext DEFAULT NULL,
  `bank_trans_msg` tinytext DEFAULT NULL,
  `bank_trans_time` datetime DEFAULT NULL,
  `trans_status` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `hoa` varchar(255) NOT NULL,
  `challan_no` varchar(255) NOT NULL,
  `challan_dt` date NOT NULL,
  `challan_amt` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_name_surname_remarks_master`
--

CREATE TABLE `gz_change_of_name_surname_remarks_master` (
  `id` int(11) NOT NULL,
  `change_of_name_surname_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `status_history_id` int(11) NOT NULL,
  `remarks` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_name_surname_status_his`
--

CREATE TABLE `gz_change_of_name_surname_status_his` (
  `id` int(11) NOT NULL,
  `gz_master_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `change_of_name_surname_status` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_name_surname_status_master`
--

CREATE TABLE `gz_change_of_name_surname_status_master` (
  `id` int(55) NOT NULL,
  `status_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_change_of_name_surname_status_master`
--

INSERT INTO `gz_change_of_name_surname_status_master` (`id`, `status_name`, `status`, `deleted`) VALUES
(1, 'Submmitted Successfully', 1, 0),
(2, 'C & T Processor User Forward', 1, 0),
(3, 'C & T Processor User Rejected', 1, 0),
(4, 'C & T Verifier User Forward', 1, 0),
(5, 'C & T Verifier User Rejected', 1, 0),
(6, 'Applicant Resubmmitted', 1, 0),
(7, 'C & T Approver User Approved', 1, 0),
(8, 'C & T Approver User Rejected', 1, 0),
(9, 'Forward To Pay', 1, 0),
(10, 'Payment Completed', 1, 0),
(11, 'Published', 1, 0),
(12, 'C & T Processor Reject Request Approved', 1, 0),
(13, 'C & T Verifier Reject Request Approved', 1, 0),
(14, 'Returned To Applicant', 1, 0),
(15, 'Payment Pending', 1, 0),
(16, 'Payment Fail', 1, 0),
(17, 'Press Approved', 1, 0),
(18, 'Application Resubmitted(Processor)', 1, 0),
(19, 'Application Resubmitted(Verifier)', 1, 0),
(20, 'Application Resubmitted(Approver)', 1, 0),
(21, 'Rejected', 1, 0),
(22, 'Application Returned (Processor)', 1, 0),
(23, 'Application Returned (Verifier)', 1, 0),
(24, 'Application Returned (Approver)', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_partnership_history`
--

CREATE TABLE `gz_change_of_partnership_history` (
  `id` int(11) NOT NULL,
  `gz_mas_type_id` varchar(255) NOT NULL,
  `gz_docu_id` int(11) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_partnership_make_pay`
--

CREATE TABLE `gz_change_of_partnership_make_pay` (
  `id` int(11) NOT NULL,
  `par_id` int(11) NOT NULL,
  `file_no` varchar(100) NOT NULL,
  `deptRefId` varchar(55) NOT NULL,
  `amount` int(55) NOT NULL,
  `challanRefId` varchar(100) NOT NULL,
  `pay_mode` varchar(55) DEFAULT NULL,
  `bank_trans_id` varchar(400) DEFAULT NULL,
  `bank_name` tinytext DEFAULT NULL,
  `bankTransactionStatus` varchar(55) NOT NULL,
  `bank_trans_time` datetime DEFAULT NULL,
  `bank_trans_msg` tinytext DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `hoa` varchar(255) NOT NULL,
  `challan_no` varchar(255) NOT NULL,
  `challan_dt` date NOT NULL,
  `challan_amt` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_partnership_master`
--

CREATE TABLE `gz_change_of_partnership_master` (
  `id` int(11) NOT NULL,
  `sl_no` int(55) NOT NULL,
  `gazette_type_id` int(11) NOT NULL,
  `state_id` int(55) DEFAULT NULL,
  `district_id` int(55) DEFAULT NULL,
  `police_station_id` int(55) DEFAULT NULL,
  `address_1` varchar(255) DEFAULT NULL,
  `address_2` varchar(255) DEFAULT NULL,
  `pincode` int(6) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `file_no` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `cur_status` int(11) NOT NULL COMMENT 'status id of the status master table',
  `press_signed_pdf` varchar(100) NOT NULL,
  `press_publish` tinyint(1) NOT NULL,
  `partnership_firm_name` varchar(255) NOT NULL,
  `partnership_registration_no` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `is_paid` int(1) NOT NULL DEFAULT 0,
  `offline_pay_status` int(11) NOT NULL DEFAULT 0 COMMENT '0-none,1-pending_payment,2-success_payment',
  `total_price_to_paid` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_partnership_pan_det`
--

CREATE TABLE `gz_change_of_partnership_pan_det` (
  `id` int(11) NOT NULL,
  `gz_mas_type_id` int(11) NOT NULL,
  `pan_card_of_partnetship` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_partnetship_doument_det`
--

CREATE TABLE `gz_change_of_partnetship_doument_det` (
  `id` int(11) NOT NULL,
  `gz_mas_type_id` int(11) NOT NULL,
  `orignal_partnership_deed` varchar(255) NOT NULL,
  `deed_of_reconstitution_of_partnership` varchar(255) NOT NULL,
  `igr_certificate_file` varchar(255) NOT NULL,
  `orignal_newspaper_of_advertisement` varchar(255) NOT NULL,
  `notice_of_softcopy` varchar(255) NOT NULL,
  `pdf_for_notice_of_softcopy` varchar(255) NOT NULL,
  `press_word` varchar(255) NOT NULL,
  `press_pdf` varchar(255) NOT NULL,
  `noc_notice_of_outgoing` varchar(255) NOT NULL,
  `challan` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_par_aadhar_det`
--

CREATE TABLE `gz_change_of_par_aadhar_det` (
  `id` int(11) NOT NULL,
  `gz_mas_type_id` int(11) NOT NULL,
  `aadhar_card_of_partnetship` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_change_of_par_status_his`
--

CREATE TABLE `gz_change_of_par_status_his` (
  `id` int(11) NOT NULL,
  `gz_mas_type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `par_status` varchar(55) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_cms_content`
--

CREATE TABLE `gz_cms_content` (
  `cms_id` int(11) NOT NULL,
  `cms_type` varchar(200) NOT NULL,
  `cms_desc` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cdate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_cms_content`
--

INSERT INTO `gz_cms_content` (`cms_id`, `cms_type`, `cms_desc`, `cdate`) VALUES
(1, 'about_us', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>The Directorate of Printing, Stationery, and Publication, Odisha established at Cuttack in the year 1936 is a vast organization of the State of Odisha which aims to meet the printing needs of all Government Offices. To get the jobs in a more streamlined way and to cope up with the heavy workload it has nine (9) branch presses established at Bhubaneswar, Khandapada, Chhatrapur, Deogarh, Balangir, Keonjhar, Bhawanipatna, and Berhampur. Besides, a School of Printing and Allied Trades, established in the year 1955 in the Directorate premises imparts basic training to the apprentices in different trades of printing technology.</p>\r\n</body>\r\n</html>', '2019-06-26 17:17:13'),
(2, 'gazette', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p class=\"MsoNormal\" style=\"text-align: justify; line-height: 18.0pt; background: white; vertical-align: baseline;\"><span style=\"mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: Calibri; mso-bidi-theme-font: minor-latin; color: #555555; background: white;\">Odisha Government Press is the authorized publisher and custodian of Govt. of Odisha Gazette Publications. Gazette of Odisha Notifications are published by Odisha Printing Press, Cuttack functioning under Commerce and Transport Department at regular intervals. There are two types of Gazette publications i.e. extraordinary gazette which is published whenever required and weekly gazette which is published every week.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify; line-height: 18.0pt; background: white; vertical-align: baseline;\"><strong><span style=\"mso-fareast-font-family: \'Times New Roman\'; mso-bidi-font-family: Calibri; mso-bidi-theme-font: minor-latin; color: #555555; background: white;\">Now with the introduction of the e-gazette portal, the gazette notifications can be accessed and downloaded by the general public.</span></strong></p>\r\n</body>\r\n</html>', '2019-06-26 17:46:19'),
(3, 'disclaimer', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p><span lang=\"EN-IN\">This website of the Directorate of Printing, Stationery and publication is being maintained by NIC Bhubaneswar for information purposes only. Even though every effort is taken to provide accurate and up to date information, officers making use of the circulars posted on the website are advised to get in touch with the Directorate of Printing, Stationery and publication whenever there is any doubt regarding the correctness of the information contained therein. In the event of any conflict between the contents of the circulars on the website and the hard copy of the circulars issued by Directorate of Printing, Stationery and publication, the information in the hard copy should be relied upon and the matter shall be brought to the notice of the Directorate of Printing, Stationery and publication.&nbsp;</span></p>\r\n</body>\r\n</html>', '2019-06-26 18:24:53'),
(4, 'acknowledgement', '<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>\r\n<p>Directorate of Printing and Department of Publication acknowledge with thanks to Central Secretariat Library for their valuable contribution and cooperation in providing digitized contents of Gazette of India notifications (1922 to 2002) for the benefit of scholars and general public.</p>\r\n</body>\r\n</html>', '2019-06-26 18:25:09');

-- --------------------------------------------------------

--
-- Table structure for table `gz_con_surname_applicant_notice_details`
--

CREATE TABLE `gz_con_surname_applicant_notice_details` (
  `id` int(55) NOT NULL,
  `place` varchar(100) DEFAULT NULL,
  `date` text DEFAULT NULL,
  `salutation` varchar(100) DEFAULT NULL,
  `old_name` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `old_name_1` varchar(100) DEFAULT NULL,
  `new_name` varchar(100) DEFAULT NULL,
  `new_name_1` varchar(100) DEFAULT NULL,
  `sign_name` varchar(100) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `approver` varchar(50) DEFAULT NULL,
  `son_daughter` varchar(20) DEFAULT NULL,
  `address` varchar(80) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `chnage_surname_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_c_and_t`
--

CREATE TABLE `gz_c_and_t` (
  `id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL COMMENT '0="Not Login", 1="Login"',
  `user_type` varchar(50) NOT NULL COMMENT '2="c&t"',
  `verify_approve` varchar(55) NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(95) NOT NULL,
  `email` varchar(96) NOT NULL,
  `dob` datetime NOT NULL,
  `module_id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `force_password` varchar(255) DEFAULT '0',
  `sms_request_count` int(200) NOT NULL,
  `last_sms_request_time` datetime NOT NULL,
  `blocked_until` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `is_logged` tinyint(1) NOT NULL,
  `emp_id` int(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_c_and_t`
--

INSERT INTO `gz_c_and_t` (`id`, `session_id`, `user_type`, `verify_approve`, `name`, `user_name`, `mobile`, `email`, `dob`, `module_id`, `login_id`, `password`, `force_password`, `sms_request_count`, `last_sms_request_time`, `blocked_until`, `created_at`, `created_by`, `modified_at`, `modified_by`, `status`, `deleted`, `is_logged`, `emp_id`) VALUES
(1, '0', '2', 'Approver', 'Usha Padhee', 'approver', '9437015000', 'mspadhi@gmail.com', '0000-00-00 00:00:00', 2, 658957, '$2y$10$Aw4teBQc8XsOvpWDm30khuOsQEBrCAUvwKtRfMwtSTDWRu5.ZlrS6', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2023-06-08 12:21:27', 1, '2024-02-26 17:44:54', 0, 1, 0, 0, NULL),
(2, '0', '2', 'Processor', 'Debasis Mohanty', 'processor', '9438742018', 'deb.dxm@gmail.com', '0000-00-00 00:00:00', 2, 123456, '$2y$10$Aw4teBQc8XsOvpWDm30khuOsQEBrCAUvwKtRfMwtSTDWRu5.ZlrS6', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2021-11-10 08:34:29', 1, '2024-02-26 16:55:34', 1, 1, 0, 0, NULL),
(3, '0', '2', 'Verifier', 'Laxmikanta Behera', 'verifier', '7504776047', 'commerce.addlsec.odisha@gmail.com', '0000-00-00 00:00:00', 2, 231254, '$2y$10$Aw4teBQc8XsOvpWDm30khuOsQEBrCAUvwKtRfMwtSTDWRu5.ZlrS6', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2021-11-12 07:15:58', 1, '2024-02-26 17:42:17', 1, 1, 0, 0, NULL),
(4, '0', '2', 'Verifier', 'C&T Verifier', 'eg_CT_Ver', '6655236677', 'test202@gmail.com', '2009-11-11 12:57:32', 1, 456688, '$2y$10$tDm3Lr2/V/s0jOElW6LKMuo4TWUv/fb85JHd8BRaZQrsRLKhRsD06', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2019-11-11 12:57:32', 1, '2019-11-11 12:57:32', 1, 1, 0, 0, 0),
(5, '0', '2', 'Approver', 'C&T Approver', 'eg_CT_App', '1199886677', 'test203@gmail.com', '2017-11-11 12:57:32', 1, 466688, '$2y$10$6uMMF.xSFuSbw30I2UtsdOqUxGOEV./zh6viFlk/CKse2pBcnq0BK', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2016-11-11 12:57:32', 1, '2014-11-11 12:57:32', 1, 1, 0, 0, 0),
(6, '0', '2', 'Processor', 'C&T Processor', 'eg_CT_Pro', '9955236677', 'test2021@gmail.com', '2007-11-11 12:57:32', 1, 496688, '$2y$10$DRJ4vUx/Hwqu4Y/Ij2BxhOyq8cmsU9npyqQckfw0YDR.qJF8WpFfK', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2019-11-11 12:57:32', 1, '2019-11-11 12:57:32', 1, 1, 0, 0, 0),
(7, '0', '2', 'Verifier', 'C&T Verifier', 'eg_CT_Gen_Ver', '6655236677', 'test205@gmail.com', '2009-11-11 12:57:38', 6, 456699, '$2y$10$ghfdOBLBg212UrNDj9UOCe6rN6R5/Hnr7CYSQA3xJoHQoM8KGoS9y', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2019-11-11 12:57:32', 1, '2019-11-11 12:57:32', 1, 1, 0, 0, 0),
(8, '0', '2', 'Approver', 'C&T Approver', 'eg_CT_Gen_App', '1199886677', 'test206@gmail.com', '2017-11-11 12:57:37', 6, 466677, '$2y$10$ghfdOBLBg212UrNDj9UOCe6rN6R5/Hnr7CYSQA3xJoHQoM8KGoS9y', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2016-11-11 12:57:32', 1, '2014-11-11 12:57:32', 1, 1, 0, 0, 0),
(9, '0', '2', 'Processor', 'C&T Processor Gender', 'eg_CT_Gen_Pro', '9955236677', 'test207@gmail.com', '2007-11-11 12:57:34', 6, 496666, '$2y$10$ghfdOBLBg212UrNDj9UOCe6rN6R5/Hnr7CYSQA3xJoHQoM8KGoS9y', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2019-11-11 12:57:32', 1, '2019-11-11 12:57:32', 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_c_and_t_password_history`
--

CREATE TABLE `gz_c_and_t_password_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `password` varchar(500) NOT NULL,
  `force_password` varchar(255) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table to store user password history';

-- --------------------------------------------------------

--
-- Table structure for table `gz_department`
--

CREATE TABLE `gz_department` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `facebook_page` text COLLATE utf8_unicode_ci NOT NULL,
  `twitter_page` text COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store departments';

--
-- Dumping data for table `gz_department`
--

INSERT INTO `gz_department` (`id`, `department_name`, `facebook_page`, `twitter_page`, `status`, `datetime`) VALUES
(2, 'Labour & ESI Department', 'https://facebook.com/LESIOdisha', 'https://twitter.com/labourodisha', 1, '2019-06-13 16:18:38'),
(5, 'Co-operation Department', 'http://facebook.com/odishacooperative', 'http://twitter.com/odishacooperative', 1, '2019-06-13 16:18:38'),
(7, 'Finance Department', 'http://facebook.com/odishafinance', 'http://twitter.com/odishafinance', 1, '2019-06-13 16:18:38'),
(51, 'Raj Bhawan', 'https://facebook.com', 'https://twitter.com', 1, '0000-00-00 00:00:00'),
(52, 'Steel & Mines Department', '', '', 1, '2019-12-29 02:12:32'),
(53, 'Panchayati Raj & DW Department', '', '', 1, '2019-12-29 02:12:32'),
(54, 'Information & PR Department', '', '', 1, '2019-12-29 02:12:32'),
(55, 'Department of Women & Child Development', '', '', 1, '2019-12-29 02:12:32'),
(56, 'Health & Family Welfare Department', '', '', 1, '2019-12-29 02:12:32'),
(57, 'Industries Department', '', '', 1, '2019-12-29 02:16:01'),
(58, 'Excise Department', '', '', 1, '2019-12-29 02:16:01'),
(59, 'Handlooms, Textiles & Handicrafts Department', '', '', 1, '2019-12-29 02:16:01'),
(60, 'Skill Development & Technical Education Department', '', '', 1, '2019-12-29 02:16:01'),
(61, 'Food Supplies & Consumer Welfare Department', '', '', 1, '2019-12-29 02:16:01'),
(62, 'ST & SC Development Department', '', '', 1, '2019-12-29 02:19:01'),
(63, 'Law Department', '', '', 1, '2019-12-29 02:19:01'),
(64, 'Water Resources Department', '', '', 1, '2019-12-29 02:19:01'),
(65, 'Revenue & Disaster Management Department', '', '', 1, '2019-12-29 02:19:01'),
(66, 'Fisheries & ARD Department', '', '', 1, '2019-12-29 02:19:01'),
(67, 'Housing & Urban Development Departmentt', '', '', 1, '2019-12-29 02:21:37'),
(68, 'Rural Development Department', '', '', 1, '2019-12-29 02:21:37'),
(69, 'Sports & Youth Services Department', '', '', 1, '2019-12-29 02:21:37'),
(70, 'Tourism Department', '', '', 1, '2019-12-29 02:21:37'),
(71, 'Planning & Convergence Department', '', '', 1, '2019-12-29 02:21:37'),
(72, 'Electronics & IT Department', '', '', 1, '2019-12-29 02:22:43'),
(73, 'Public Enterprises Department', '', '', 1, '2019-12-29 02:22:43'),
(74, 'Parliamentary Affairs Department', '', '', 1, '2020-02-17 12:36:56'),
(75, 'Science & Technology Department', '', '', 1, '2020-02-17 12:36:56'),
(76, 'Forest & Environment Department', '', '', 1, '2020-02-17 12:36:56'),
(77, 'MSME Department', '', '', 1, '2020-02-17 12:36:56'),
(78, 'GA & PG Department', '', '', 1, '2020-02-17 12:36:56'),
(79, 'Agriculture & Farmers Empowerment Department', '', '', 1, '2020-02-17 12:38:18'),
(80, 'Higher Education Department', '', '', 1, '2020-02-17 12:38:18'),
(81, 'Commerce & Transport (Transport) Department ', '', '', 1, '2020-02-17 12:38:18'),
(82, 'Works Department', '', '', 1, '2020-02-17 12:38:18'),
(83, 'Miscellaneous', '', '', 1, '2020-02-24 15:59:11'),
(84, 'Commerce & Transport (Commerce) Department', '', '', 1, '2020-03-12 15:56:07'),
(85, 'Home Department', '', '', 1, '2020-03-12 15:56:07'),
(86, 'Energy Department', '', '', 1, '2020-03-12 15:56:07'),
(87, 'State Election Commission', '', '', 1, '2020-03-12 15:56:07'),
(88, 'School & Mass Education Department', '', '', 1, '2020-03-12 15:56:07'),
(89, 'SSEP Department', '', '', 1, '2020-03-12 15:56:07'),
(90, 'Odia Language, Literature & Culture Department', '', '', 1, '2024-02-19 11:45:59'),
(91, 'Mission Shakti', '', '', 1, '2024-02-19 11:40:55'),
(94, 'Odisha Legislative Assembly', '', '', 1, '2024-02-19 11:41:59'),
(95, 'Odisha High Court', '', '', 1, '2024-02-19 11:42:29'),
(96, 'Social Security & Empowerment of Persons with Disabilities', '', '', 1, '2024-02-19 11:43:31'),
(97, 'Home Election Department', '', '', 1, '2024-09-20 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `gz_dep_data_table`
--

CREATE TABLE `gz_dep_data_table` (
  `id` int(11) NOT NULL,
  `hoa` varchar(255) NOT NULL,
  `dept_code` varchar(100) NOT NULL COMMENT 'department code',
  `dep_ref_id` varchar(100) NOT NULL COMMENT 'department reference ID',
  `user_id` int(11) NOT NULL COMMENT 'id of the current user',
  `mobile` varchar(11) NOT NULL,
  `amount` varchar(50) NOT NULL COMMENT 'amount',
  `file_number` varchar(255) NOT NULL,
  `gazette_id` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL COMMENT 'change of name surname',
  `transaction_type` varchar(50) NOT NULL COMMENT 'COS=''Change of name surname'', COP=''Change of Partnership''',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gz_designation`
--

CREATE TABLE `gz_designation` (
  `id` int(11) NOT NULL,
  `name` tinytext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store designation';

--
-- Dumping data for table `gz_designation`
--

INSERT INTO `gz_designation` (`id`, `name`) VALUES
(1, 'Addl. Chief Secretary to Govt.'),
(2, 'Principal Secretary to Govt.'),
(3, 'Commissioner-Cum-Secretary to Govt.'),
(4, 'Special Secretary to Govt.'),
(5, 'Addl. Secretary to Govt.'),
(6, 'Joint Secretary to Govt.'),
(7, 'Director'),
(8, 'Deputy Secretary to Govt.'),
(9, 'Under Secretary to Govt.');

-- --------------------------------------------------------

--
-- Table structure for table `gz_district_master`
--

CREATE TABLE `gz_district_master` (
  `id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT NULL COMMENT 'link state master table as foreign key',
  `district_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district_code` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store districts';

--
-- Dumping data for table `gz_district_master`
--

INSERT INTO `gz_district_master` (`id`, `state_id`, `district_name`, `district_code`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`, `deleted`) VALUES
(1, 26, 'Anugul', '2401', 1, 2147483647, NULL, 1, '2021-12-14 10:23:25', 0),
(2, 26, 'Balangir', '2402', 1, 2147483647, NULL, 1, '2021-12-15 10:23:25', 0),
(3, 26, 'Baleshwar', '2403', 1, 2147483647, NULL, 1, '2021-12-16 10:23:25', 0),
(4, 26, 'Bargarh', '2404', 1, 2147483647, NULL, 1, '2021-12-17 10:23:25', 0),
(5, 26, 'Bhadrak', '2405', 1, 2147483647, NULL, 1, '2021-12-18 10:23:25', 0),
(6, 26, 'Boudh', '2406', 1, 2147483647, NULL, 1, '2021-12-19 10:23:25', 0),
(7, 26, 'Cuttack', '2407', 1, 2147483647, NULL, 1, '2021-12-20 10:23:25', 0),
(8, 26, 'Deogarh', '2408', 1, 2147483647, NULL, 1, '2021-12-21 10:23:25', 0),
(9, 26, 'Dhenkanal', '2409', 1, 2147483647, NULL, 1, '2021-12-22 10:23:25', 0),
(10, 26, 'Gajapati', '2410', 1, 2147483647, NULL, 1, '2021-12-23 10:23:25', 0),
(11, 26, 'Ganjam', '2411', 1, 2147483647, NULL, 1, '2021-12-24 10:23:25', 0),
(12, 26, 'Jagatsinghapur', '2412', 1, 2147483647, NULL, 1, '2021-12-25 10:23:25', 0),
(13, 26, 'Jajapur', '2413', 1, 2147483647, NULL, 1, '2021-12-26 10:23:25', 0),
(14, 26, 'Jharsuguda', '2414', 1, 2147483647, NULL, 1, '2021-12-27 10:23:25', 0),
(15, 26, 'Kalahandi', '2415', 1, 2147483647, NULL, 1, '2021-12-28 10:23:25', 0),
(16, 26, 'Kandhamal', '2416', 1, 2147483647, NULL, 1, '2021-12-29 10:23:25', 0),
(17, 26, 'Kendrapara', '2417', 1, 2147483647, NULL, 1, '2021-12-30 10:23:25', 0),
(18, 26, 'Kendujhar', '2418', 1, 2147483647, NULL, 1, '2021-12-31 10:23:25', 0),
(19, 26, 'Khordha', '2419', 1, 2147483647, NULL, 1, '2022-01-01 10:23:25', 0),
(20, 26, 'Koraput', '2420', 1, 2147483647, NULL, 1, '2022-01-02 10:23:25', 0),
(21, 26, 'Malkangiri', '2421', 1, 2147483647, NULL, 1, '2022-01-03 10:23:25', 0),
(22, 26, 'Mayurbhanj', '2422', 1, 2147483647, NULL, 1, '2022-01-04 10:23:25', 0),
(23, 26, 'Nabarangpur', '2423', 1, 2147483647, NULL, 1, '2022-01-05 10:23:25', 0),
(24, 26, 'Nayagarh', '2424', 1, 2147483647, NULL, 1, '2022-01-06 10:23:25', 0),
(25, 26, 'Nuapada', '2425', 1, 2147483647, NULL, 1, '2022-01-06 10:05:45', 0),
(26, 26, 'Puri', '2426', 1, 2147483647, NULL, 1, '2022-01-06 18:05:01', 0),
(27, 26, 'Rayagada', '2427', 1, 2147483647, NULL, 1, '2022-01-06 18:05:01', 0),
(28, 26, 'Sambalpur', '2428', 1, 2147483647, NULL, 1, '2022-01-06 18:05:01', 0),
(29, 26, 'Sonepur', '2429', 1, 2147483647, NULL, 1, '2022-01-06 18:05:01', 0),
(30, 26, 'Sundargarh', '2430', 1, 2147483647, NULL, 1, '2022-01-06 18:05:01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_documents`
--

CREATE TABLE `gz_documents` (
  `id` int(11) NOT NULL,
  `gazette_id` int(11) NOT NULL,
  `dept_word_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `dept_pdf_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `dept_signed_pdf_path` text COLLATE utf8_unicode_ci NOT NULL,
  `press_word_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `press_pdf_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `press_signed_pdf_path` text COLLATE utf8_unicode_ci NOT NULL,
  `press_signed_pdf_file_size` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store gazette document files and paths';

--
-- Dumping data for table `gz_documents`
--

INSERT INTO `gz_documents` (`id`, `gazette_id`, `dept_word_file_path`, `dept_pdf_file_path`, `dept_signed_pdf_path`, `press_word_file_path`, `press_pdf_file_path`, `press_signed_pdf_path`, `press_signed_pdf_file_size`) VALUES
(1, 1, './uploads/dept_doc/156ec7c10de5e142f5e5562d5e38d81d.docx', './uploads/dept_pdf/1753683996_1.pdf', '', '', '', '', ''),
(2, 1, './uploads/dept_doc/ceaa06b06e8f88729e888cecab0b0107.docx', './uploads/dept_pdf/1753683996_1.pdf', '', '', '', '', ''),
(3, 2, './uploads/dept_doc/cb01b427ad84c009318ed0e75d147365.docx', './uploads/dept_pdf/1753685039_2.pdf', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `gz_documents_history`
--

CREATE TABLE `gz_documents_history` (
  `id` int(11) NOT NULL,
  `gazette_id` int(11) NOT NULL,
  `gz_document_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `word_file_path` text CHARACTER SET utf8mb4 NOT NULL,
  `pdf_file_path` text CHARACTER SET utf8mb4 NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Table to store department document history for extraordinary';

--
-- Dumping data for table `gz_documents_history`
--

INSERT INTO `gz_documents_history` (`id`, `gazette_id`, `gz_document_id`, `dept_id`, `word_file_path`, `pdf_file_path`, `created_at`, `created_by`, `deleted`) VALUES
(1, 1, 1, 52, './uploads/dept_doc/156ec7c10de5e142f5e5562d5e38d81d.docx', './uploads/dept_pdf/1711519356_1.pdf', '2024-03-27 11:32:38', 45, 0),
(2, 1, 2, 84, './uploads/dept_doc/ceaa06b06e8f88729e888cecab0b0107.docx', './uploads/dept_pdf/1753683996_1.pdf', '2025-07-28 11:56:37', 3, 0),
(3, 2, 3, 84, './uploads/dept_doc/cb01b427ad84c009318ed0e75d147365.docx', './uploads/dept_pdf/1753685039_2.pdf', '2025-07-28 12:14:00', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_esign_transaction`
--

CREATE TABLE `gz_esign_transaction` (
  `id` int(11) NOT NULL,
  `trns_id` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `page` int(11) DEFAULT NULL,
  `gazette_id` int(11) DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `signed_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `designation` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `dept` int(11) DEFAULT NULL,
  `source_file_path` tinytext CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_feedback`
--

CREATE TABLE `gz_feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `occupation` varchar(500) NOT NULL,
  `address` text NOT NULL,
  `subject` text NOT NULL,
  `feedback` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_final_weekly_gazette`
--

CREATE TABLE `gz_final_weekly_gazette` (
  `id` int(11) NOT NULL,
  `month` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `week` int(2) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `year` year(4) NOT NULL,
  `word_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `pdf_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `signed_pdf_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `signed_pdf_file_size` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store the final weekly gazette to be published';

-- --------------------------------------------------------

--
-- Table structure for table `gz_gazette`
--

CREATE TABLE `gz_gazette` (
  `id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sl_no` int(11) NOT NULL,
  `sro_available` tinyint(1) DEFAULT NULL,
  `letter_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gazette_type_id` int(11) NOT NULL,
  `subject` text COLLATE utf8_unicode_ci NOT NULL,
  `notification_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notification_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reject_remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `issue_date` text COLLATE utf8_unicode_ci NOT NULL,
  `status_id` int(1) NOT NULL,
  `tags` text COLLATE utf8_unicode_ci NOT NULL,
  `sakabda_date` tinytext COLLATE utf8_unicode_ci NOT NULL,
  `saka_month` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `saka_date` int(2) DEFAULT NULL,
  `saka_year` int(4) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `is_paid` tinyint(1) DEFAULT NULL,
  `offline_pay_status` int(11) NOT NULL DEFAULT 0 COMMENT '0-none,1-pending_payment,2-success_payment',
  `total_price_to_paid` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store gazette';

-- --------------------------------------------------------

--
-- Table structure for table `gz_gazette_parts`
--

CREATE TABLE `gz_gazette_parts` (
  `id` int(11) NOT NULL,
  `part_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `section_name` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gz_gazette_parts`
--

INSERT INTO `gz_gazette_parts` (`id`, `part_name`, `section_name`) VALUES
(1, 'Part - I', 'Appointments, Confirmations, Postings, Transfers, Deputations, Powers, Leave, Programmes & Results of Departmental Examinations of Officers and their Personal Notices.'),
(2, 'Part - II', 'Educational Notices, Programmes and Results of School and College Examinations and other Examinations, etc.'),
(3, 'Part - III', 'Statutory Rules, Orders, Notifications, Rules, etc., issued by the Governor, Heads of Departments and High Court.'),
(4, 'Part - III-A', 'Regulations, Orders, Notifications, Rules, etc., issued by the Governor, Heads of Departments and High Court.'),
(5, 'Part - IV', 'Regulations, Orders, Notifications and Rules of the Government of India, Papers extracted from the Gazette of India and Gazettes of other States and Notifications, Orders, etc., in connection with Elections.'),
(6, 'Part - V', 'Acts of the Parliament assented to by the President.'),
(7, 'Part - VI', 'Bills introduced into the Parliament and Bills published before introduction in the Parliament.'),
(8, 'Part - VII', 'Advertisements, Notices, Press Notes and Audit Reports and Awards on Industrial Disputes, etc.'),
(9, 'Part - VIII', 'Sale Notices of Forest Products, etc.'),
(10, 'Part - IX', 'Circulars and General letters by the Accountant-General, Odisha.'),
(11, 'Part - X', 'Acts of the Legislative Assembly, Odisha.'),
(12, 'Part - XI', 'Bills introduced into the Legislative Assembly of Odisha, Reports of the Select Committees presented or to be presented to that Assembly and Bills published before introduction in that Assembly.'),
(13, 'Part - XII', 'Materials relating to Transport Organisations.'),
(14, 'SUPPLEMENT', 'Resolutions, Weather and Crop Reports and other Statistical Reports, etc.'),
(15, 'SUPPLEMENT (A)', 'Register of persons dismissed from Government Service.'),
(16, 'APPENDIX', 'Catalogue of Books and Periodicals registered in Odisha.');

-- --------------------------------------------------------

--
-- Table structure for table `gz_gazette_status`
--

CREATE TABLE `gz_gazette_status` (
  `id` int(11) NOT NULL,
  `gazette_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `remarks` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store gazette status transaction';

-- --------------------------------------------------------

--
-- Table structure for table `gz_gazette_text`
--

CREATE TABLE `gz_gazette_text` (
  `id` int(11) NOT NULL,
  `gazette_id` int(11) NOT NULL,
  `dept_text` longtext COLLATE utf8_unicode_ci NOT NULL,
  `press_text` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store gazette text contents';

-- --------------------------------------------------------

--
-- Table structure for table `gz_gazette_type`
--

CREATE TABLE `gz_gazette_type` (
  `id` int(11) NOT NULL,
  `gazette_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gz_gazette_type`
--

INSERT INTO `gz_gazette_type` (`id`, `gazette_type`) VALUES
(1, 'Extraordinary'),
(2, 'Weekly');

-- --------------------------------------------------------

--
-- Table structure for table `gz_hod`
--

CREATE TABLE `gz_hod` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(95) NOT NULL,
  `email` varchar(96) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_hod`
--

INSERT INTO `gz_hod` (`id`, `name`, `mobile`, `email`, `dept_id`, `created_at`, `created_by`, `modified_at`, `modified_by`, `status`, `deleted`) VALUES
(4, 'ashwini', '2321323234', 'a@gmail.com', 79, '2020-03-03 10:08:06', 1, '2020-03-03 12:48:50', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_igr_password_history`
--

CREATE TABLE `gz_igr_password_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `password` varchar(500) NOT NULL,
  `force_password` varchar(255) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table to store user password history';

-- --------------------------------------------------------

--
-- Table structure for table `gz_igr_users`
--

CREATE TABLE `gz_igr_users` (
  `id` int(55) NOT NULL,
  `session_id` varchar(255) NOT NULL COMMENT '0="Not Login", 1="Login"',
  `verify_approve` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `date_of_birth` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `login_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `force_password` varchar(255) DEFAULT '0',
  `sms_request_count` int(200) NOT NULL,
  `last_sms_request_time` datetime NOT NULL,
  `blocked_until` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(55) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(55) NOT NULL,
  `modified_at` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `is_logged` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_igr_users`
--

INSERT INTO `gz_igr_users` (`id`, `session_id`, `verify_approve`, `user_name`, `date_of_birth`, `mobile`, `email`, `login_id`, `password`, `force_password`, `sms_request_count`, `last_sms_request_time`, `blocked_until`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`, `deleted`, `is_logged`) VALUES
(1, '0', 'Processor', 'eg_IGR_Pro', '1995-05-14', '3366998855', 'test2024@gmail.com', 112233, '$2y$10$QY3fHOlQP5opZB17eCnDX.M5Rb63AplAWwhw/YdeRylaQl6YK0QJm', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 1, '2019-11-11 12:57:32', 1, '2019-11-11 12:57:32', 0, 0),
(2, '0', 'Verifier', 'eg_IGR_Ver', '1995-05-14', '4466998855', 'test2025@gmail.com', 112244, '$2y$10$4VkLjJLdN4Xg0kQ3Wr9ZzuYKBcJgBaFwff9BDoSWz9IgTrdUEfNfe', '1', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 1, '2019-11-11 12:57:32', 1, '2019-11-11 12:57:32', 0, 0),
(3, '0', 'Approver', 'eg_IGR_App', '1995-05-14', '7008257467', 'test2026@gmail.com', 112255, '$2y$10$4VkLjJLdN4Xg0kQ3Wr9ZzuYKBcJgBaFwff9BDoSWz9IgTrdUEfNfe', '1', 4, '2024-05-22 10:29:26', '2024-05-22 11:29:26', 1, 1, '2019-11-11 12:57:32', 1, '2019-11-11 12:57:32', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_modules`
--

CREATE TABLE `gz_modules` (
  `id` int(55) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(55) NOT NULL,
  `deleted` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_modules`
--

INSERT INTO `gz_modules` (`id`, `module_name`, `status`, `created_at`, `created_by`, `deleted`) VALUES
(1, 'Change of Partnership Module', 1, '2020-03-02 16:26:16', 1, 0),
(2, 'Change of Name/Surname Module', 1, '2020-03-02 16:27:26', 1, 0),
(6, 'Change of Gender Module', 1, '2024-06-24 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_modules_wise_pricing`
--

CREATE TABLE `gz_modules_wise_pricing` (
  `id` int(55) NOT NULL,
  `module_id` int(55) NOT NULL,
  `pricing` int(55) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(55) NOT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` int(55) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_modules_wise_pricing`
--

INSERT INTO `gz_modules_wise_pricing` (`id`, `module_id`, `pricing`, `status`, `created_at`, `created_by`, `modified_at`, `modified_by`, `deleted`) VALUES
(6, 3, 10, 1, '2020-03-02 19:05:49', 1, NULL, NULL, 0),
(7, 1, 529, 1, '2020-03-02 19:05:55', 1, NULL, NULL, 0),
(10, 2, 529, 1, '2020-03-03 11:08:12', 1, '2020-03-03 15:14:06', 1, 0),
(12, 4, 13, 1, '2020-03-03 11:09:36', 1, '2020-03-03 12:49:04', 1, 0),
(13, 6, 529, 1, '2024-06-29 00:00:00', 1, '2024-06-29 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_notification`
--

CREATE TABLE `gz_notification` (
  `id` int(11) NOT NULL,
  `gazette_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `responsible_user_id` int(11) NOT NULL COMMENT 'User Id, To whom the notification will visible.',
  `dept_type` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Dept Name',
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store gazette notifications';

-- --------------------------------------------------------

--
-- Table structure for table `gz_notification_applicant`
--

CREATE TABLE `gz_notification_applicant` (
  `id` int(11) NOT NULL,
  `master_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL COMMENT '1=partnership, 2=namesurname, 3=paid gazette submitted by department',
  `user_id` int(11) NOT NULL,
  `responsible_user_id` int(11) NOT NULL COMMENT 'To whom the notification will visible.',
  `text` varchar(255) NOT NULL,
  `is_viewed` tinyint(1) NOT NULL,
  `applicant_user_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` date NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_notification_ct`
--

CREATE TABLE `gz_notification_ct` (
  `id` int(11) NOT NULL,
  `master_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `responsible_user_id` int(11) NOT NULL COMMENT 'To whom the notification will visible.',
  `text` varchar(255) NOT NULL,
  `is_viewed` tinyint(1) NOT NULL,
  `pro_ver_app` tinyint(1) NOT NULL COMMENT '1:- processor , 2:- Verifier, 3:- Approver',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` date NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_notification_govt`
--

CREATE TABLE `gz_notification_govt` (
  `id` int(11) NOT NULL,
  `master_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `responsible_user_id` int(11) NOT NULL COMMENT 'To whom the notification will visible',
  `text` varchar(255) NOT NULL,
  `is_viewed` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` date NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_notification_igr`
--

CREATE TABLE `gz_notification_igr` (
  `id` int(11) NOT NULL,
  `master_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `responsible_user_id` int(11) NOT NULL COMMENT 'To whom the notification will visible',
  `text` varchar(255) NOT NULL,
  `is_viewed` tinyint(1) NOT NULL DEFAULT 0,
  `ver_app` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` date NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_notification_type`
--

CREATE TABLE `gz_notification_type` (
  `id` int(11) NOT NULL,
  `notification_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store notification type';

--
-- Dumping data for table `gz_notification_type`
--

INSERT INTO `gz_notification_type` (`id`, `notification_type`) VALUES
(1, 'Notification'),
(2, 'Resolution'),
(3, 'Order Number'),
(4, 'Act'),
(5, 'Amendment Act'),
(6, 'Amendment Rules'),
(7, 'Bill'),
(8, 'Notice'),
(9, 'Addendum'),
(10, 'Amendment Order'),
(11, 'Amendment Rule'),
(12, 'Amendment Statutes'),
(13, 'Appointment'),
(14, 'Award'),
(15, 'Charging'),
(16, 'Circular'),
(17, 'Committee'),
(18, 'Corrigendum'),
(19, 'Court'),
(20, 'Crops'),
(21, 'Declaration'),
(22, 'Disabilities Rules'),
(23, 'Election'),
(24, 'Energization'),
(25, 'Erratum'),
(26, 'GST'),
(27, 'Land Acquisition'),
(28, 'Land Schedule'),
(29, 'Loan'),
(30, 'Lokayukta'),
(31, 'Memorandum'),
(32, 'Minimum Wages'),
(33, 'Miscellaneous'),
(34, 'Office Memorandum'),
(35, 'Office Order'),
(36, 'Ordinance'),
(37, 'Policy'),
(38, 'Press Communique'),
(39, 'Project Work'),
(40, 'Public Notice'),
(41, 'Regulations'),
(42, 'Reports'),
(43, 'Reservation'),
(45, 'Retirement'),
(46, 'Sales Tax'),
(47, 'Schemes'),
(48, 'Surname Change'),
(49, 'Orders'),
(50, 'Rules'),
(51, 'Bills & Acts'),
(52, 'Appointments'),
(53, 'Partnership Deed Change ');

-- --------------------------------------------------------

--
-- Table structure for table `gz_offline_approver_users`
--

CREATE TABLE `gz_offline_approver_users` (
  `id` int(55) NOT NULL,
  `verify_approve` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `date_of_birth` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `login_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(55) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(55) NOT NULL,
  `modified_at` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `is_logged` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_offline_approver_users`
--

INSERT INTO `gz_offline_approver_users` (`id`, `verify_approve`, `user_name`, `date_of_birth`, `mobile`, `email`, `login_id`, `password`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`, `deleted`, `is_logged`) VALUES
(1, 'Approver', 'Subha', '1987-07-10', '9658129014', 'subhanarayan.mohapatra@gmail.com', 458892, '$2y$10$DPpx5LeudIIrLqq1HYQYpuHDThqNm97Jz0rfbG/mkU2yi7O21k/DO', 1, 1, '2023-11-21 06:36:19', 0, '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_partnership_docu_det_master`
--

CREATE TABLE `gz_partnership_docu_det_master` (
  `id` int(11) NOT NULL,
  `document_name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_partnership_docu_det_master`
--

INSERT INTO `gz_partnership_docu_det_master` (`id`, `document_name`, `created_by`, `created_at`, `modified_by`, `modified_at`, `status`, `deleted`) VALUES
(1, 'Previous Partnership Deed', 1, '2019-08-09 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(2, 'Deed of Reconstitution of Partnership', 1, '2019-08-09 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(3, 'IGR Certificate ', 1, '2019-08-09 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(4, 'PAN Card of Incoming_Outgoing Partners', 1, '2019-08-09 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(5, 'Aadhaar Card of Incoming_Outgoing Partners', 1, '2019-08-09 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(6, 'Original Newspaper Advertisement', 1, '2019-08-09 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(7, 'Notice in Softcopy', 1, '2019-08-09 00:00:00', 0, '0000-00-00 00:00:00', 1, 0),
(8, 'NOC_Notice of Outgoing_Retiring Partners', 1, '2021-12-20 10:34:11', 0, '2021-12-20 10:34:11', 1, 0),
(9, 'Challan / Form V', 1, '2021-12-20 10:34:11', 0, '2021-12-20 10:34:11', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_par_partnership_chang_remark`
--

CREATE TABLE `gz_par_partnership_chang_remark` (
  `id` int(11) NOT NULL,
  `gz_master_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL COMMENT 'gz_change_of_par_status_his  id of this table',
  `remark` varchar(1000) NOT NULL,
  `status_history_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_par_sur_status_master`
--

CREATE TABLE `gz_par_sur_status_master` (
  `id` int(11) NOT NULL,
  `status_det` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_par_sur_status_master`
--

INSERT INTO `gz_par_sur_status_master` (`id`, `status_det`, `created_at`, `created_by`, `modified_at`, `modified_by`, `status`, `deleted`) VALUES
(1, 'Applicant Submmited', '2020-03-11 16:05:22', 1, '2019-08-16 14:52:47', 1, 1, 0),
(2, 'C & T Verified', '2020-03-11 16:05:22', 1, '0000-00-00 00:00:00', 0, 1, 0),
(3, 'C & T Approved', '2020-03-11 16:05:22', 1, '0000-00-00 00:00:00', 0, 1, 0),
(4, 'IGR Verified', '2020-03-11 16:05:22', 1, '0000-00-00 00:00:00', 0, 1, 0),
(5, 'IGR Approved', '2020-03-11 16:05:23', 1, '0000-00-00 00:00:00', 0, 1, 0),
(6, 'Forward to Pay', '2020-03-11 16:05:23', 1, '0000-00-00 00:00:00', 0, 1, 0),
(7, 'Payment Completed', '2020-03-11 16:05:23', 1, '0000-00-00 00:00:00', 0, 1, 0),
(8, 'Govt Press Signed PDF', '2020-03-11 16:05:23', 1, '0000-00-00 00:00:00', 0, 1, 0),
(9, 'C & T Verifier Returned', '2020-03-11 16:05:23', 1, '0000-00-00 00:00:00', 0, 1, 0),
(10, 'Applicant Resubmitted', '2020-03-11 16:05:23', 1, '0000-00-00 00:00:00', 0, 1, 0),
(11, 'IGR Verifier Returned', '2020-03-11 16:05:23', 1, '0000-00-00 00:00:00', 0, 1, 0),
(12, 'C&T Verifier Return Aprroved', '2020-03-13 00:00:00', 1, '0000-00-00 00:00:00', 0, 1, 0),
(13, 'C&T Verifier Return to Applicant', '2020-03-13 00:00:00', 1, '0000-00-00 00:00:00', 0, 1, 0),
(14, 'IGR Approver Return Approved', '2020-03-13 00:00:00', 1, '0000-00-00 00:00:00', 0, 1, 0),
(15, 'IGR Verifier Return to C&T Verifier', '2020-03-13 00:00:00', 1, '0000-00-00 00:00:00', 0, 1, 0),
(16, 'C&T Approver Forward to Publish', '2020-03-02 00:00:00', 1, '0000-00-00 00:00:00', 0, 1, 0),
(17, 'Govt Press Published', '2020-03-02 00:00:00', 1, '0000-00-00 00:00:00', 0, 1, 0),
(18, 'View PDF', '2020-03-02 00:00:00', 1, '0000-00-00 00:00:00', 0, 1, 0),
(19, 'Returned to Applicant From C & T Processor', '2022-01-14 06:50:33', 1, '2022-01-14 06:50:33', 0, 1, 0),
(20, 'Application Resubmitted(C & T Processor)', '2022-01-14 06:50:33', 1, '2022-01-14 06:50:33', 0, 1, 0),
(21, 'Application Forwarded to IGR Verifier', '2022-01-18 11:29:38', 1, '2022-01-18 11:29:38', 0, 1, 0),
(22, 'Returned to Applicant from IGR Verifier', '2022-01-18 12:25:59', 1, '2022-01-18 12:25:59', 0, 1, 0),
(23, 'Application Resubmitted(IGR Verifier)', '2022-01-18 12:25:59', 1, '2022-01-18 12:25:59', 0, 1, 0),
(24, 'Application Rejected By IGR Approver', '2022-01-19 06:25:36', 1, '2022-01-19 06:25:36', 0, 1, 0),
(25, 'Returned to Applicant from IGR Approver', '2022-01-19 06:48:27', 1, '2022-01-19 06:48:27', 0, 1, 0),
(26, 'Application Resubmitted(IGR Approver)', '2022-01-19 06:48:27', 1, '2022-01-19 06:48:27', 0, 1, 0),
(27, 'Application Forwarded to C&T Verifier', '2022-01-19 07:32:15', 1, '2022-01-19 07:32:15', 0, 1, 0),
(28, 'Application Resubmitted(C&T Verifier)', '2022-01-19 07:51:35', 1, '2022-01-19 07:51:35', 0, 1, 0),
(29, 'Application Forwarded to C&T Approver', '2022-01-19 08:09:31', 1, '2022-01-19 08:09:31', 0, 1, 0),
(30, 'Returned to Applicant From C&T Approver', '2022-01-19 08:43:28', 1, '2022-01-19 08:43:28', 0, 1, 0),
(31, 'Application Resubmitted(C&T Approver)', '2022-01-19 08:43:28', 1, '2022-01-19 08:43:28', 0, 1, 0),
(32, 'Application Rejected By C&T Approver', '2023-04-05 12:01:42', 1, '2023-04-05 12:01:51', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_payment_of_cost_payment_details`
--

CREATE TABLE `gz_payment_of_cost_payment_details` (
  `id` int(11) NOT NULL,
  `gazette_id` int(11) DEFAULT NULL,
  `gazette_type_id` int(11) DEFAULT NULL,
  `dept_ref_id` varchar(255) DEFAULT NULL,
  `challan_ref_id` varchar(255) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `pay_mode` varchar(255) DEFAULT NULL,
  `bank_trans_id` varchar(255) DEFAULT NULL,
  `bank_name` tinytext DEFAULT NULL,
  `bank_trans_msg` tinytext DEFAULT NULL,
  `bank_trans_time` datetime DEFAULT NULL,
  `trans_status` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gz_payment_of_cost_payment_status_history`
--

CREATE TABLE `gz_payment_of_cost_payment_status_history` (
  `id` int(55) NOT NULL,
  `payment_id` int(55) DEFAULT NULL,
  `payment_status` varchar(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gz_payment_status_history`
--

CREATE TABLE `gz_payment_status_history` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `payment_type` enum('COP','COS') DEFAULT NULL,
  `payment_status` varchar(55) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gz_police_station_master`
--

CREATE TABLE `gz_police_station_master` (
  `id` int(55) NOT NULL,
  `district_id` int(55) DEFAULT NULL,
  `police_station_name` varchar(255) DEFAULT NULL,
  `police_station_odia_name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(55) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` int(55) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gz_police_station_master`
--

INSERT INTO `gz_police_station_master` (`id`, `district_id`, `police_station_name`, `police_station_odia_name`, `status`, `created_at`, `created_by`, `modified_at`, `modified_by`, `deleted`) VALUES
(1, 1, 'Talcher', 'ତାଳଚେର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(2, 1, 'Kaliyari', 'କଲିୟରୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(3, 1, 'Rengali Dyam Site', 'ରେ଼ଙ୍ଗାଳି ଡ୍ୟାମ୍ ସାଇଟ୍', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(4, 1, 'Kaniha', 'କଣିହାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(5, 1, 'Rengali', 'ରେଙ୍ଗାଳି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(6, 1, 'Athmallick', 'ଆଠମଲ୍ଲିକ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(7, 1, 'Handapa', 'ହଣ୍ଡପା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(8, 1, 'Kishor Nagar', 'କିଶୋର ନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(9, 1, 'Thakurgarh', 'ଠାକୁରଗଡ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(10, 1, 'ANGUL', 'ଅନୁଗୋଳ.', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(11, 1, 'Bantala', 'ବନ୍ତଳା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(12, 1, 'Jarapada', 'ଜର଼ପଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(13, 1, 'Purunakot', 'ପୁରୁଣାକୋଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(14, 1, 'Chhendipada', 'ଛେଣ୍ଡିପଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(15, 1, 'Jarapada', 'ଜରପଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(16, 1, 'Pallahara', 'ପାଳଲହଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(17, 1, 'Khamar', 'ଖମାର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(18, 2, 'Bolangir', 'ବଲାଙ୍ଗିର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(19, 2, 'Loisingha', 'ଲୋଇସିଂହା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(20, 2, 'Tusura', 'ତୁଷରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(21, 2, 'Bangomunda', 'ବଙ୍ଗୋମୁଣ୍ଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(22, 2, 'Kantabanji', 'କଣ୍ଟାବାଞ୍ଜି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(23, 2, 'Sindhekela', 'ସିନ୍ଧେକେଲା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(24, 2, 'Turekela', 'ତୁରେକେଲା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(25, 2, 'Saintala', 'ସଇଁତଲା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(26, 2, 'Titilagarh', 'ଟିଟିଲାଗଡ଼', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(27, 2, 'Patanagarh', 'ପାଟଣାଗଡ଼', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(28, 2, 'Khaparaklhol', 'ଖପରାଖୋଲ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(29, 2, 'Belapara', 'ବେଲପଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(30, 3, 'Balasore Sadar', 'ବାଲେଶ୍ଵର ସଦର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(31, 3, 'Balasore Town', 'ବାଲେଶ୍ଵର ଟାଉନ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(32, 3, 'Remuna', 'ରେମୁଣା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(33, 3, 'Raibania', 'ରାଇବଣିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(34, 3, 'Jaleswar', 'ଜଳେଶ୍ଵର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(35, 3, 'Bhograi', 'ଭୋଗରାଇ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(36, 3, 'Basta', 'ବସ୍ତା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(37, 3, 'Singala', 'ସିଙ୍ଗଲା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(38, 3, 'Baliapal', 'ବାଲିଆପାଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(39, 3, 'Khantapada', 'ଖନ୍ତାପଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(40, 3, 'Soro', 'ସୋରୋ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(41, 3, 'Khaira', 'ଖଇରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(42, 3, 'Similia', 'ସିମିଳିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(43, 3, 'Berhampur', 'ବରହମପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(44, 3, 'Nilagiri', 'ନୀଳଗିରି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(45, 3, 'Oupada', 'ଔପଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(46, 4, 'Bargarh', 'ବରଗଡ଼', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(47, 4, 'Padampur', 'ପଦମପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(48, 4, 'Gaisilate', 'ଗାଇସିଲାଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(49, 4, 'Jharbandh', 'ଝାରବନ୍ଧ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(50, 4, 'Barpali', 'ବରପାଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(51, 4, 'Sohela', 'ସୋହେଲା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(52, 4, 'Bijepur', 'ବିଜେପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(53, 4, 'Melachamunda', 'ମେଲଛାମୁଣ୍ଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(54, 4, 'Bheden', 'ଭେଡ଼େନ.', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(55, 4, 'Atabira', 'ଅତାବିରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(56, 4, 'Bhatli', 'ଭଟଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(57, 4, 'Ambabhona', 'ଅମ୍ବାଭୋନା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(58, 4, 'Paikmal', 'ପାଇକମାଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(59, 4, 'Jagadalpur', 'ଜଗଦଲପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(60, 5, 'Bhadrak', 'ଭଦ୍ରକ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(61, 5, 'Chandbali', 'ଚାନ୍ଦବାଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(62, 5, 'Bansada', 'ବାଁଶଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(63, 5, 'Tihidi', 'ତିହିଡି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(64, 5, 'Dhamnagar', 'ଧାମନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(65, 5, 'Bhandari Pokhari', 'ଭଣ୍ଡାରି ପୋଖରୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(66, 5, 'Bonth', 'ବନ୍ତ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(67, 5, 'Basudebpur', 'ବାସୁଦେବ ପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(68, 6, 'BAUNSUNI', 'ବାଉଁଶୁଣୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(69, 6, 'BOUDH', 'ବ଼ୌଦ୍ଧ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(70, 6, 'Manmunda', 'ମନମୁଣ୍ଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(71, 6, 'Baghiapada', 'ବଘିଆପଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(72, 6, 'PURUNA KATAK', 'ପୁରୁଣା କଟକ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(73, 6, 'HARABHANGA', 'ହରଭଙ୍ଗା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(74, 6, 'Kantamal', 'କଣ୍ଟାମାଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(75, 7, 'Cuttack Sadar', 'କଟକ ସଦର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(76, 7, 'Bidanasi', 'ବିଡ଼ାନାସି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(77, 7, 'Purighat', 'ପୁରୀଘାଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(78, 7, 'Mangalabag', 'ମଙ୍ଗଳାବାଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(79, 7, 'Cantonment', 'କାଣ୍ଟନମେଣ୍ଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(80, 7, 'Lalbagh', 'ଲାଲବାଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(81, 7, 'Malgodam', 'ମାଲଗୋଦାମ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(82, 7, 'Chauliaganj', 'ଚାଉଳିଆଗଞ୍ଜ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(83, 7, 'Madhupatna', 'ମଧୁପାଟଣା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(84, 7, 'Banki', 'ବାଙ୍କୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(85, 7, 'Baideswar', 'ବୈଦେଶ୍ଵର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(86, 7, 'Badamba', 'ବଡ଼ମ୍ବା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(87, 7, 'Mahanga', 'ମାହାଙ୍ଗା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(88, 7, 'Salepur', 'ସାଲେପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(89, 7, 'Choudwar', 'ଚୌଦ୍ଵାର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(90, 7, 'Tangi', 'ଟାଙ୍ଗି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(91, 7, 'Kanpur', 'କାନପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(92, 7, 'Narsinghpur', 'ନରସିଂହପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(93, 7, 'Govindpur', 'ଗୋବିନ୍ଦପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(94, 7, 'Tigiria', 'ତିଗିରିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(95, 7, 'Kishan Nagar', 'କିଶନନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(96, 7, 'Olatpur', 'ଓଲଟପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(97, 7, 'Nischintakoili', 'ନିଶ୍ଚିନ୍ତକୋଇଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(98, 7, 'Nemal', 'ନେମାଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(99, 7, 'Athagarh', 'ଆଠଗଡ଼', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(100, 7, 'Gurudijhatia', 'ଗୁରୁଡ଼ି ଝାଟିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(101, 8, 'Deogarh', 'ଦେବଗଡ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(102, 8, 'Reamal', 'ରିଆମାଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(103, 8, 'Barkote', 'ବାରକୋଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(104, 8, '(Naikul) Kundheigola', '(ନଈକୂଳ) କୁଣ୍ଢାଇଗୋଳା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(105, 8, 'Kala', 'କଲା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(106, 9, 'Dhenkanal Sadar', 'ଢ଼େଙ୍କାନାଳ ସଦର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(107, 9, 'Motanga', 'ମୋଟଙ୍ଗା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(108, 9, 'Gondia', 'ଗଁଦିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(109, 9, 'Kamakyanagar', 'କାମାକ୍ଷାନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(110, 9, 'Parjang', 'ପରଜଙ୍ଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(111, 9, 'Hindol', 'ହିନ୍ଦୋଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(112, 9, 'Rasol', 'ରାସୋଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(113, 9, 'Balimi', 'ବାଲିମି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(114, 9, 'Anugola', 'ଅନୁଗୋଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(115, 9, 'Bhuban', 'ଭୁବନ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(116, 10, 'Ramgiri Udayagiri', 'ରାମଗିରି ଉଦୟଗିରି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(117, 10, 'Ramgiri', 'ରାମଗିରି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(118, 10, 'Mohana', 'ମୋହନା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(119, 10, 'Adaba', 'ଅଡ଼ବା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(120, 10, 'Kasinagar', 'କାଶୀନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(121, 10, 'Paralakhemundi', 'ପାରଳାଖେମୁଣ୍ଡି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(122, 10, 'Garabandha', 'ଗାରାବନ୍ଧ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(123, 10, 'Seranga', 'ସେରଙ୍ଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(124, 10, 'Nalaghata', 'ନଳାଘାଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(125, 10, 'Gurandi', 'ଗୁରାଣ୍ଡି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(126, 10, 'Rayagada', 'ରାୟଗଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(127, 10, 'Kasinagar', 'କାଶିନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(128, 11, 'Chhatrapur', 'ଛତ୍ରପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(129, 11, 'Rambha', 'ରମ୍ଭା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(130, 11, 'Berhampur', 'ବ୍ରହ୍ମପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(131, 11, 'Nuagaon', 'ନୁଆଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(132, 11, 'Hinjili', 'ହିଞ୍ଜିଳି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(133, 11, 'Gopalpur', 'ଗୋପାଳପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(134, 11, 'Purushotampur', 'ପୁରୁଷୋତ୍ତମପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(135, 11, 'Kabisurya Nagar', 'କବିସୂର୍ଯ୍ୟନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(136, 11, 'Khalikot', 'ଖଲିକୋଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(137, 11, 'Kodala', 'କୋଦଳା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(138, 11, 'Patapur', 'ପାଟପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(139, 11, 'Golanthara', 'ଗୋଳନ୍ଥରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(140, 11, 'Jarada', 'ଜରଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(141, 11, 'Aska', 'ଆସିକା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(142, 11, 'Gangapur', 'ଗାଙ୍ଗପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(143, 11, 'Badagada', 'ବଡ଼ଗଡ଼', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(144, 11, 'Digapahandi', 'ଦିଗପହଣ୍ଡି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(145, 11, 'Ramagiri', 'ରାମଗିରୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(146, 11, 'Sorada', 'ସୋରଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(147, 11, 'Bhanjanagar', 'ଭଞ୍ଜନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(148, 11, 'Tarasing', 'ତାରସିଙ୍ଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(149, 11, 'Buguda', 'ବୁଗୁଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(150, 11, 'Baidyanathpur (Berhempur)', 'ବୈଦ୍ୟନାଥପୁର (ବ୍ରହ୍ମପୁର)', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(151, 11, 'Dharakota', 'ଧରାକୋଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(152, 11, 'Seragada', 'ଶେରଗଡ଼', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(153, 11, 'Jagannatha Prasad', 'ଜଗନାଥ ପ୍ରସାଦ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(154, 11, 'Polasara', 'ପୋଲସରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(155, 12, 'Jagatsinghpur', 'ଜଗତସିଂହପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(156, 12, 'Kujanga', 'କୁଜଙ୍ଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(157, 12, 'Balikuda', 'ବାଲିକୁଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(158, 12, 'Tirtol', 'ତିର୍ତ୍ତୋଲ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(159, 12, 'Naugaon', 'ନାଉଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(160, 12, 'Ersama', 'ଏରସମା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(161, 12, 'Paradeep', 'ପାରାଦ୍ଵୀପ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(162, 12, 'Biridi', 'ବିରିଡି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(163, 12, 'Raghunathpur', 'ରଘୁନାଥପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(164, 13, 'Sukinda', 'ସୁକିନ୍ଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(165, 13, 'Jajpur Road', 'ଯାଜପୁର ରୋଡ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(166, 13, 'Korei', 'କୋରେଇ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(167, 13, 'Badachana', 'ବଡ଼ଚଣା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(168, 13, 'Dharmasala', 'ଧର୍ମଶାଳା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(169, 13, 'Jajpur', 'ଯାଜପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(170, 13, 'Mangalpur', 'ମଙ୍ଗଳପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(171, 13, 'Binjharpur', 'ବିଂଝାରପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(172, 13, 'Bari ', 'ବରୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(173, 13, 'Panikoili', 'ପାଣିକ଼ୋଇଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(174, 13, 'Jakhapura', 'ଯଖପୁରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(175, 13, 'Kalinganagar', 'କଳିଙ୍ଗନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(176, 13, 'Tamaka', 'ଟମକା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(177, 14, 'Jharsuguda', 'ଝାରସୁଗୁଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(178, 14, 'Brajarajnagar', 'ବ୍ରଜରାଜନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(179, 14, 'Laikera', 'ଲଇକେରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(180, 14, 'Katarbaga ', 'କତରବଗା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(181, 14, 'Lakhanpur', 'ଲଖନପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(182, 14, 'Rengali', 'ରେଙ୍ଗାଲୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(183, 14, 'Kolabira', 'କୋଲାବିରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(184, 14, 'Banharpali', 'ବନହରପାଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(185, 15, 'Bhawanipatana', 'ଭବାନୀପାଟଣା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(186, 15, 'Lanjigarh', 'ଲାଞ୍ଜିଗଡ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(187, 15, 'Kegaon', 'କେଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(188, 15, 'Kesinga', 'କେସିଙ୍ଗା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(189, 15, 'Madanpur Rampur', 'ମଦନପୁର ରାମପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(190, 15, 'Narla', 'ନର୍ଲା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(191, 15, 'Thuamul Rampur', 'ଥୁଆମୁଳ ରାମପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(192, 15, 'Junagarh', 'ଜୁନାଗଡ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(193, 15, 'Dharmagarh', 'ଧର୍ମଗଡ଼', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(194, 15, 'Jaipatana', 'ଜୟପାଟଣା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(195, 15, 'Kokasara', 'କୋକସରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(196, 15, 'Golamunda', 'ଗୋଲାମୁଣ୍ଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(197, 16, 'Daringbadi', 'ଦାରିଙ୍ଗବାଡ଼ି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(198, 16, 'Kotagarh', 'କୋଟଗଡ଼', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(199, 16, 'Belghar', 'ବେଲଘର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(200, 16, 'G.Udayagiri', 'ଘୁମୁସର ଉଦୟଗିରି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(201, 16, 'Raikia', 'ରାଇକିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(202, 16, 'Bamunigaon', 'ବାମୁଣିଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(203, 16, 'Balliguda', 'ବାଲିଗୁଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(204, 16, 'Phulbani', 'ଫୁଲବାଣୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(205, 16, 'Khajuriapada', 'ଖଜୁରୀପଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(206, 16, 'Phiringia', 'ଫିରିଙ୍ଗିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(207, 16, 'Gochhapada', 'ଗୋଛାପଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(208, 16, 'Phulbani Sadar', 'ଫୁଲବାଣୀ  ସଦର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(209, 16, 'Chakpada                                                    ', 'ଚକାପାଦ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(210, 16, 'K. Nuagaon                                                  ', 'କ. ନୂଆଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(211, 16, 'Sarangagada', 'ସାରଙ୍ଗଗଡ଼', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(212, 16, 'Tikabali                                                    ', 'ଟିକାବାଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(213, 16, 'Tumudibandha                                                ', 'ତୁମୁଡ଼ିବନ୍ଧ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(214, 17, 'Aul', 'ଆଳି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(215, 17, 'Patamundai', 'ପଟ୍ଟାମୁଣ୍ଡାଇ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(216, 17, 'Rajkanika', 'ରାଜକନିକା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(217, 17, 'Kendrapada', 'କେନ୍ଦ୍ରାପଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(218, 17, 'Mahakala Pada', 'ମହାକାଳ ପଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(219, 17, 'Patakura', 'ପାଟକୁରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(220, 17, 'Rajnagar', 'ରାଜନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(221, 17, 'Kendrapada Town', 'କେନ୍ଦ୍ରାପଡା ଟାଉନ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(222, 17, 'Derabis', 'ଡେରାବିଶ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(223, 17, 'Nikirai', 'ନିକିରାଇ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(224, 17, 'Marsaghai', 'ମାର୍ଶାଘାଇ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(225, 17, 'Jambu marain', 'ଜମ୍ବୁ ମରାଇନ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(226, 18, 'Keonjhar Sadar', 'କେନ୍ଦୁଝର ସଦର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(227, 18, 'Patna', 'ପାଟଣା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(228, 18, 'Champua', 'ଚମ୍ପୁଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(229, 18, 'Baria', 'ବାରିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(230, 18, 'Ghatagaon', 'ଘଟଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(231, 18, 'Pandapada', 'ପଣ୍ଡାପଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(232, 18, 'Harichandanpur', 'ହରିଚନ୍ଦନପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(233, 18, 'Daitari', 'ଦୈତାରୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(234, 18, 'BARBIL', 'ବଡବିଲ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(235, 18, 'JODA', 'ଯୋଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(236, 18, 'Champua', 'ଚମ୍ପୁଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(237, 18, 'Telkoi', 'ତେଲକୋଇ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(238, 18, 'Kanjipani', 'କାଞ୍ଜୁପାଣି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(239, 18, 'Patna', 'ପାଟଣା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(240, 18, 'Anandpur', 'ଆନନ୍ଦପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(241, 18, 'Ghasipura', 'ଘସିପୁରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(242, 18, 'Ramachandrapur', 'ରାମଚନ୍ଦ୍ର ପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(243, 18, 'Soso', 'ସୋସୋ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(244, 18, 'Turumunga', 'ତୁରୁମୁଙ୍ଗା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(245, 18, 'Nayakote', 'ନୟାକୋଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(246, 18, 'Nandipada', 'ନନ୍ଦୀପଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(247, 18, 'Keonjhar Town', 'କେନ୍ଦୁଝର ଟାଉନ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(248, 18, 'Rugudi', 'ରୁଗୁଡ଼ି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(249, 18, 'Jhumpura', 'ଝୁମ୍ପୁରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(250, 18, 'Bolani', 'ବଲାଣୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(251, 18, 'Bamebari', 'ବାମେବାରୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(252, 19, 'Bolagada', 'ବୋଲଗଡ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(253, 19, 'Begunia', 'ବେଗୁନିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(254, 19, 'Tangi', 'ଟାଙ୍ଗି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(255, 19, 'Banapur', 'ବାଣପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(256, 19, 'New Capital', 'ନିଉକ୍ୟାପିଟାଲ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(257, 19, 'Bhubaneswar', 'ଭୂବନେଶ୍ଵର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(258, 19, 'Baliyanta', 'ବାଲିଅନ୍ତା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(259, 19, 'Saheed Nagar', 'ସହିଦନଗର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(260, 19, 'Chandaka', 'ଚନ୍ଦକା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(261, 19, 'Balipatna', 'ବାଲିପାଟଣା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(262, 19, 'Jatani', 'ଜଟଣୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(263, 19, 'Khurdha', 'ଖୋର୍ଦ୍ଧା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(264, 19, 'Jankia', 'ଜଙ୍କିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(265, 20, 'NANDAPUR', 'ନନ୍ଦପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(266, 20, 'POTTANGI', 'ପଟାଙ୍ଗି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(267, 20, 'PADUA', 'ପାଡୁଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(268, 20, 'SEMILIGUDA', 'ସେମିଳିଗୁଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(269, 20, 'Kakirigumma', 'କାକିରିଗୁମା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(270, 20, 'Bhairab Singhpur', 'ଭୈରବସିଂପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(271, 20, 'BORIGUMMA', 'ବୋରିଗୁମା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(272, 20, 'JEYPORE', 'ଜୟପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(273, 20, 'BOIPARIGUDA', 'ବଇପାରିଗୁଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(274, 20, 'KUNDURA', 'କୁନ୍ଦୁରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(275, 20, 'DASMANTHPUR', 'ଦଶମନ୍ତପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(276, 20, 'Narayanpatna', 'ନାରାୟଣପାଟଣା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(277, 20, 'KORAPUT', 'କୋରାପୁଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(278, 20, 'LAXMIPUR', 'ଲକ୍ଷ୍ମୀପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(279, 20, 'Kashipur', 'କାଶୀପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(280, 20, 'KOTPAD', 'କୋଟପାଡ଼', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(281, 20, 'MACHKUND', 'ମାଛକୁଣ୍ଡ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(282, 20, 'BANDHUGAM', 'ବନ୍ଧୁଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(283, 21, 'Malkangiri', 'ମାଲକାନଗିରି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(284, 21, 'Mathili', 'ମାଥିଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(285, 21, 'Orkel', 'ଓରକେଲ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(286, 21, 'Chitrakonda', 'ଚିତ୍ରକୋଣ୍ଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(287, 21, 'Mudulipoda', 'ମୁଦୁଲିପଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(288, 21, 'Motu', 'ମୋଟୁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(289, 21, 'Venketpallam', 'ଭେଙ୍କଟପଲମ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(290, 22, 'Udala', 'ଉଦଳା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(291, 22, 'Khunta', 'ଖୁଣ୍ଟା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(292, 22, 'Sarat', 'ଶରତ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(293, 22, 'Karanjia', 'କରଞ୍ଜିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(294, 22, 'Thakurmunda', 'ଠାକୁର ମୁଣ୍ଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(295, 22, 'Jasipur', 'ଯଶିପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(296, 22, 'Raruan', 'ରରୁଆଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(297, 22, 'Satakosia', 'ଶାତକୋଶିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(298, 22, 'Tiring', 'ତିରିଙ୍ଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(299, 22, 'Bahalda', 'ବହଳଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(300, 22, 'Kuliana', 'କୁଳିଅଣା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(301, 22, 'Badasahi', 'ବଡସାହି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(302, 22, 'Bangiriposi', 'ବାଙ୍ଗିରି ପୋଷି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(303, 22, 'Baripada', 'ବାରିପଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(304, 22, 'Betanati', 'ବେତନଟୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(305, 22, 'Morada', 'ମୋରଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(306, 22, 'Suliapada', 'ଶୁଳିଆ ପଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(307, 22, 'Baisinga', 'ବଇଶିଙ୍ଗା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(308, 22, 'Goru Mahisani', 'ଗୋରୁ ମହିଷାଣି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(309, 22, 'Badampahad', 'ବାଦାମ ପାହାଡ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(310, 22, 'Bisoi', 'ବିଶୋଇ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(311, 22, 'Rairangpur', 'ରାଇରଙ୍ଗପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(312, 22, 'Jamda                                                      ', 'ଯାମଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(313, 22, 'Kaptipada', 'କପ୍ତିପଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(314, 23, 'Nabarangpur', 'ନବରଂଗପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(315, 23, 'Tentulikhunti', 'ତେନ୍ତୁଳିଖୁଣ୍ଟି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(316, 23, 'Khatiguda', 'ଖାତିଗୁଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(317, 23, 'Papadahandi', 'ପାପଡାହାଣ୍ଡି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(318, 23, 'Dabugaon', 'ଡାବୁଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(319, 23, 'Kodinga', 'କୋଡିଙ୍ଗା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(320, 23, 'Umerkote', 'ଉମରକୋଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(321, 23, 'Chandahandi', 'ଚନ୍ଦାହାଣ୍ଡି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(322, 23, 'Jharigam', 'ଝରିଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(323, 23, 'Raighar', 'ରାଇଘର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(324, 23, 'Kundei', 'କୁନ୍ଦେଇ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(325, 23, 'Kosagumuda', 'କୋଷାଗୁମୁଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(326, 24, 'Sarankul', 'ଶରଣକୁଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(327, 24, 'Nuagaon', 'ନୂଆଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(328, 24, 'Nayagarh', 'ନୟାଗଡ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(329, 24, 'Odagaon', 'ଓଡଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(330, 24, 'Dasapalla', 'ଦଶପଲ୍ଲା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(331, 24, 'Gania', 'ଗଣିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(332, 24, 'Khandapada', 'ଖଣ୍ଡପଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(333, 24, 'Phategarh', 'ଫତେଗଡ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(334, 24, 'RANAPUR', 'ରଣପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(335, 25, 'BODEN', 'ବୋଡ଼େନ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(336, 25, 'KHARIAR', 'ଖଡ଼ିଆଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(337, 25, 'SINAPALI', 'ସିନାପାଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(338, 25, 'Jonk', 'ଜୋଙ୍କ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(339, 25, 'Nuapada', 'ନୂଆପଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(340, 25, 'Komna', 'କୋମନା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(341, 26, 'Nimapada', 'ନିମାପଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(342, 26, 'Gop', 'ଗୋପ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(343, 26, 'Kakatpur', 'କାକଟପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(344, 26, 'Satyabadi', 'ସତ୍ୟବାଦୀ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(345, 26, 'Brahmagiri', 'ବ୍ରହ୍ମଗିରି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(346, 26, 'Puri Sadar', 'ପୁରୀସଦର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(347, 26, 'Chandanpur', 'ଚନ୍ଦନପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(348, 26, 'Puri Town', 'ପୁରୀଟାଉନ୍', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(349, 26, 'Krushnaprasad', 'କୃଷ୍ଣପ୍ରସାଦ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(350, 26, 'Delang', 'ଡେଲାଙ୍ଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(351, 26, 'PIPILI', 'ପିପିଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(352, 26, 'Astaranga                                                   ', 'ଅସ୍ତରଙ୍ଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(353, 26, 'Gadisagoda', 'ଗଡିଶା ଗୋଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(354, 26, 'Kanasa                                                  ', 'କଣାସ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(355, 27, 'KALYANSINGPUR', 'କଲ୍ୟାଣସିଙ୍ଗପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(356, 27, 'Chandili', 'ଚାନ୍ଦୀଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(357, 27, 'RAYAGADA', 'ରାୟଗଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(358, 27, 'Muniguda', 'ମୁନିଗୁଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(359, 27, 'Ambadala', 'ଆମ୍ବାଦଲା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(360, 27, 'Bisamkatak', 'ବିଷମକଟକ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(361, 27, 'Kashipur', 'କାଶୀପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(362, 27, 'Tikiri', 'ଟିକିରି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(363, 27, 'Gunupur', 'ଗୁଣପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(364, 27, 'GUDARI', 'ଗୁଡ଼ାରି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(365, 27, 'Padmapur', 'ପଦ୍ମପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(366, 27, 'Putasing', 'ପୁଟାସିଙ୍ଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(367, 28, 'Dhama', 'ଧମା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(368, 28, 'Sambalpur', 'ସମ୍ବଲପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(369, 28, 'Hirakud', 'ହିରାକୁଦ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(370, 28, 'Burla', 'ବୁର୍ଲା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(371, 28, 'Sasan', 'ଶାସନ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(372, 28, 'Jujomura', 'ଯୁଯୋମୁରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(373, 28, 'Sambalpur Sadar', 'ସମ୍ବଲପୁର ସଦର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(374, 28, 'Naktideul', 'ନାକଟିଦେଉଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(375, 28, 'Charmal', 'ଚାରମାଳ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(376, 28, 'Rampur', 'ରାମପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(377, 28, 'Rairakhol', 'ରେଢ଼ାଖୋଲ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(378, 28, 'Katarabaga', 'କତରବଗା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(379, 28, 'Gobindapur', 'ଗୋବିନ୍ଦପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(380, 28, 'Jamankira', 'ଜମନକିରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(381, 28, 'Kuchinda', 'କୋଚିଣ୍ଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(382, 28, 'Mahulpali', 'ମହୁଲପାଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(383, 28, 'Dhanupali', 'ଧନୁପାଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(384, 29, 'Sonepur', 'ସୋନପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(385, 29, 'Dunguripali', 'ଡୁ଼ଙ୍ଗୁରି ପାଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(386, 29, 'Tarbha', 'ତରଭା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(387, 29, 'Binka', 'ବିନିକା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(388, 29, 'Biramaharajpur', 'ବୀରମହାରାଜପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(389, 29, 'Sindol', 'ଶିଣ୍ଢୋଲ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(390, 30, 'Sundergarh', 'ସୁନ୍ଦରଗଡ଼', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(391, 30, 'Bhasma', 'ଭଷ୍ମା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(392, 30, 'Talsara', 'ତଲସରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(393, 30, 'Badagaon', 'ବଡ଼ଗାଁ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(394, 30, 'Rajgangpur', 'ରାଜଗାଙ୍ଗପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(395, 30, 'Kulunga', 'କୁଲୁଙ୍ଗା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(396, 30, 'Birmitrapur', 'ବିରମିତ୍ର ପୁର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(397, 30, 'Bisra', 'ବିଶ୍ରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(398, 30, 'Ragunathpali', 'ରଘୁନାଥ ପାଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(399, 30, 'Plant Site', 'ପ୍ଲାଣ୍ଟ ସାଇଟ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(400, 30, 'Township', 'ଟାଉନ ସିପ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(401, 30, 'Tangarpali', 'ଟାଙ୍ଗର ପାଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(402, 30, 'Hemgiri', 'ହେମଗିର', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(403, 30, 'Lephripada', 'ଲେଫ୍ରିପଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(404, 30, 'Banki', 'ବାଙ୍କି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(405, 30, 'K. Balang', 'କ ବଲାଙ୍ଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(406, 30, 'Gurundia', 'ଗୁରୁଣ୍ଡିଆ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(407, 30, 'Koira', 'କୋଇଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(408, 30, 'Bonai', 'ବଣାଇ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(409, 30, 'Tikayatpali', 'ଟିକାଏତପାଲି', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(410, 30, 'Mahulpada', 'ମହୁଲପଦା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(411, 30, 'Raibaga', 'ରାଏବଗା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(412, 30, 'Lathikata', 'ଲାଠିକଟା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(413, 30, 'Kutra', 'କୁତ୍ରା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(414, 30, 'Bondamunda', 'ବଣ୍ଡୋମୁଣ୍ଡା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(415, 30, 'Brahmani Tarang', 'ବ୍ରାହ୍ମଣୀତରଙ୍ଗ', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(416, 30, 'Lahunipara', 'ଲହୁଣିପଡ଼ା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0),
(417, 30, 'Kinjirkela', 'କିଞ୍ଜିରକେଲା', 1, '2021-12-21 07:37:06', 1, '2021-12-21 07:37:06', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_relation_master`
--

CREATE TABLE `gz_relation_master` (
  `id` int(11) NOT NULL,
  `relation_name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `gz_relation_master`
--

INSERT INTO `gz_relation_master` (`id`, `relation_name`, `status`, `created_at`, `created_by`, `modified_at`, `modified_by`, `deleted`) VALUES
(1, 'Father', 1, '2021-02-08 12:44:04', 1, NULL, NULL, 0),
(2, 'Mother', 1, '2021-02-08 12:44:04', 1, NULL, NULL, 0),
(3, 'Husband', 1, '2021-02-08 12:44:04', 1, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_settings`
--

CREATE TABLE `gz_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store application settings';

--
-- Dumping data for table `gz_settings`
--

INSERT INTO `gz_settings` (`id`, `setting_key`, `action_key`, `action_value`, `created_at`) VALUES
(1, 'smtp', 'host', 'smtp.gmail.com', '2019-06-19 12:02:06'),
(2, 'smtp', 'username', 'ntspl.demo5@gmail.com', '2019-06-19 12:02:06'),
(3, 'smtp', 'password', 'ntspl.demo5.com', '2019-06-19 12:04:17'),
(4, 'smtp', 'port', '465', '2019-06-19 12:04:19'),
(5, 'smtp', 'protocol', 'ssl', '2019-06-19 12:04:17'),
(6, 'sms', 'api_key', 'aksdjfhkjdfhkdjfhsdf11111', '2019-06-26 13:21:59'),
(7, 'sms', 'endpoint_url', 'http://jj.com', '2019-06-26 13:21:59'),
(8, 'sms', 'sender_id', 'ABCD', '2019-06-26 13:21:59'),
(9, 'payget', 'api_key', '1236547890', '2019-06-26 14:56:54'),
(10, 'payget', 'pay_token', '789654123', '2019-06-26 14:56:54'),
(11, 'payget', 'pay_salt', '741258963', '2019-06-26 14:56:54');

-- --------------------------------------------------------

--
-- Table structure for table `gz_site_launch`
--

CREATE TABLE `gz_site_launch` (
  `id` int(11) NOT NULL,
  `site_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table to store site launching event';

--
-- Dumping data for table `gz_site_launch`
--

INSERT INTO `gz_site_launch` (`id`, `site_active`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `gz_states_master`
--

CREATE TABLE `gz_states_master` (
  `id` int(55) NOT NULL,
  `state_name` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(55) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `modified_by` int(55) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gz_states_master`
--

INSERT INTO `gz_states_master` (`id`, `state_name`, `created_at`, `created_by`, `modified_at`, `modified_by`, `status`, `deleted`) VALUES
(1, 'Andaman and Nicobar Islands', NULL, NULL, NULL, NULL, 1, 0),
(2, 'Andhra Pradesh', NULL, NULL, NULL, NULL, 1, 0),
(3, 'Arunachal Pradesh', NULL, NULL, NULL, NULL, 1, 0),
(4, 'Assam', NULL, NULL, NULL, NULL, 1, 0),
(5, 'Bihar', NULL, NULL, NULL, NULL, 1, 0),
(6, 'Chandigarh', NULL, NULL, NULL, NULL, 1, 0),
(7, 'Chhattisgarh', NULL, NULL, NULL, NULL, 1, 0),
(8, 'Dadra and Nagar Haveli', NULL, NULL, NULL, NULL, 1, 0),
(9, 'Daman and Diu', NULL, NULL, NULL, NULL, 1, 0),
(10, 'Delhi', NULL, NULL, NULL, NULL, 1, 0),
(11, 'Goa', NULL, NULL, NULL, NULL, 1, 0),
(12, 'Gujarat', NULL, NULL, NULL, NULL, 1, 0),
(13, 'Haryana', NULL, NULL, NULL, NULL, 1, 0),
(14, 'Himachal Pradesh', NULL, NULL, NULL, NULL, 1, 0),
(15, 'Jammu and Kashmir', NULL, NULL, NULL, NULL, 1, 0),
(16, 'Jharkhand', NULL, NULL, NULL, NULL, 1, 0),
(17, 'Karnataka', NULL, NULL, NULL, NULL, 1, 0),
(18, 'Kerala', NULL, NULL, NULL, NULL, 1, 0),
(19, 'Lakshadweep', NULL, NULL, NULL, NULL, 1, 0),
(20, 'Madhya Pradesh', NULL, NULL, NULL, NULL, 1, 0),
(21, 'Maharashtra', NULL, NULL, NULL, NULL, 1, 0),
(22, 'Manipur', NULL, NULL, NULL, NULL, 1, 0),
(23, 'Meghalaya', NULL, NULL, NULL, NULL, 1, 0),
(24, 'Mizoram', NULL, NULL, NULL, NULL, 1, 0),
(25, 'Nagaland', NULL, NULL, NULL, NULL, 1, 0),
(26, 'Odisha', NULL, NULL, NULL, NULL, 1, 0),
(27, 'Puducherry', NULL, NULL, NULL, NULL, 1, 0),
(28, 'Punjab', NULL, NULL, NULL, NULL, 1, 0),
(29, 'Rajasthan', NULL, NULL, NULL, NULL, 1, 0),
(30, 'Sikkim', NULL, NULL, NULL, NULL, 1, 0),
(31, 'Tamil Nadu', NULL, NULL, NULL, NULL, 1, 0),
(32, 'Tripura', NULL, NULL, NULL, NULL, 1, 0),
(33, 'Uttar Pradesh', NULL, NULL, NULL, NULL, 1, 0),
(34, 'Uttarakhand', NULL, NULL, NULL, NULL, 1, 0),
(35, 'West Bengal', NULL, NULL, NULL, NULL, 1, 0),
(38, 'LADAKH', '2020-07-07 02:19:36', 1, '2021-01-21 15:22:53', 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_status`
--

CREATE TABLE `gz_status` (
  `id` int(11) NOT NULL,
  `status_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store gazette status master';

--
-- Dumping data for table `gz_status`
--

INSERT INTO `gz_status` (`id`, `status_name`) VALUES
(1, 'Dept. Submitted'),
(2, 'Dept. Saved'),
(3, 'Press Returned'),
(4, 'Dept. Resubmitted'),
(5, 'Press Published'),
(6, 'Press Approved'),
(7, 'Dept. Resubmitted Saved'),
(8, 'Processor Approved'),
(9, 'Verifier Approved'),
(10, 'Approver Approved'),
(11, 'Processor Returned'),
(12, 'Verifier Returned'),
(13, 'Approver Returned'),
(14, 'Approver Rejected'),
(15, 'Verifier Reject Request Approved'),
(16, 'Returned To Dept.'),
(17, 'Forward To Pay'),
(18, 'Payment Completed'),
(19, 'Payment Pending'),
(20, 'Payment Failed'),
(21, 'Payment Cancel'),
(22, 'Dept. Reupload Saved');

-- --------------------------------------------------------

--
-- Table structure for table `gz_ulb_master`
--

CREATE TABLE `gz_ulb_master` (
  `id` int(11) NOT NULL,
  `unique_id` int(55) NOT NULL,
  `ulb_name` varchar(400) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district_id` int(11) DEFAULT NULL,
  `district_unique_id` int(55) DEFAULT NULL,
  `ulb_code` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL,
  `district_code` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `change_request` int(55) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store ULB';

--
-- Dumping data for table `gz_ulb_master`
--

INSERT INTO `gz_ulb_master` (`id`, `unique_id`, `ulb_name`, `district_id`, `district_unique_id`, `ulb_code`, `status`, `created_by`, `created_at`, `modified_by`, `modified_at`, `deleted`, `district_code`, `change_request`) VALUES
(1, 1, 'Anugul', 1, 1, '2400101', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2401', 0),
(2, 2, 'Athamallik', 1, 1, '2400102', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2401', 0),
(3, 3, 'Talcher', 1, 1, '2400103', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2401', 0),
(4, 4, 'Balangir', 2, 2, '2400104', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2402', 0),
(5, 5, 'Kantabanji', 2, 2, '2400105', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2402', 0),
(6, 6, 'Patnagarh', 2, 2, '2400106', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2402', 0),
(7, 7, 'Titlagarh', 2, 2, '2400107', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2402', 0),
(8, 8, 'Tusura', 2, 2, '2400108', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2402', 0),
(9, 9, 'Baleshwar', 3, 3, '2400109', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2403', 0),
(10, 10, 'Jaleswar', 3, 3, '2400110', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2403', 0),
(11, 11, 'Nilagiri', 3, 3, '2400111', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2403', 0),
(12, 12, 'Remuna', 3, 3, '2400112', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2403', 0),
(13, 13, 'Soro', 3, 3, '2400113', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2403', 0),
(14, 14, 'Attabira', 4, 4, '2400114', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2404', 0),
(15, 15, 'Barapali', 4, 4, '2400115', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2404', 0),
(16, 16, 'Bargarh', 4, 4, '2400116', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2404', 0),
(17, 17, 'Bijepur', 4, 4, '2400117', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2404', 0),
(18, 18, 'Padmapur', 4, 4, '2400118', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2404', 0),
(19, 19, 'Basudebpur', 5, 5, '2400119', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2405', 0),
(20, 20, 'Bhadrak', 5, 5, '2400120', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2405', 0),
(21, 21, 'Chandabali', 5, 5, '2400121', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2405', 0),
(22, 22, 'Dhamanagar', 5, 5, '2400122', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2405', 0),
(23, 23, 'Boudhgarh', 6, 6, '2400123', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2406', 0),
(24, 24, 'Athagad', 7, 7, '2400124', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2407', 0),
(25, 25, 'Banki', 7, 7, '2400125', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2407', 0),
(26, 26, 'Choudwar', 7, 7, '2400126', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2407', 0),
(27, 27, 'Cuttack', 7, 7, '2400127', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2407', 0),
(28, 28, 'Debagarh', 8, 8, '2400128', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2408', 0),
(29, 29, 'Bhuban', 9, 9, '2400129', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2409', 0),
(30, 30, 'Dhenkanal', 9, 9, '2400130', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2409', 0),
(31, 31, 'Hindol', 9, 9, '2400131', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2409', 0),
(32, 32, 'Kamakhyanagar', 9, 9, '2400132', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2409', 0),
(33, 33, 'Kasinagar', 10, 10, '2400133', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2410', 0),
(34, 34, 'Paralakhemundi', 10, 10, '2400134', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2410', 0),
(35, 35, 'Asika', 11, 11, '2400135', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(36, 36, 'Bellaguntha', 11, 11, '2400136', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(37, 37, 'Berhampur', 11, 11, '2400137', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(38, 38, 'Bhanjanagar', 11, 11, '2400138', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(39, 39, 'Buguda', 11, 11, '2400139', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(40, 40, 'Chhatrapur', 11, 11, '2400140', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(41, 41, 'Chikiti', 11, 11, '2400141', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(42, 42, 'Digapahandi', 11, 11, '2400142', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(43, 43, 'Ganjam', 11, 11, '2400143', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(44, 44, 'Gopalpur', 11, 11, '2400144', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(45, 45, 'Hinjilicut', 11, 11, '2400145', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(46, 46, 'Kavisurjyanagar', 11, 11, '2400146', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(47, 47, 'Khalikote', 11, 11, '2400147', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(48, 48, 'Kodala', 11, 11, '2400148', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(49, 49, 'Polasara', 11, 11, '2400149', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(50, 50, 'Purusottampur', 11, 11, '2400150', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(51, 51, 'Rambha', 11, 11, '2400151', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(52, 52, 'Surada', 11, 11, '2400152', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2411', 0),
(53, 53, 'Jagatsinghpur', 12, 12, '2400153', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2412', 0),
(54, 54, 'Paradeep', 12, 12, '2400154', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2412', 0),
(55, 55, 'Jajapur', 13, 13, '2400155', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2413', 0),
(56, 56, 'Vyasanagar', 13, 13, '2400156', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2413', 0),
(57, 57, 'Belpahar', 14, 14, '2400157', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2414', 0),
(58, 58, 'Brajarajnagar', 14, 14, '2400158', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2414', 0),
(59, 59, 'Jharsuguda', 14, 14, '2400159', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2414', 0),
(60, 60, 'Bhawanipatna', 15, 15, '2400160', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2415', 0),
(61, 61, 'Dharmagarh', 15, 15, '2400161', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2415', 0),
(62, 62, 'Junagarh', 15, 15, '2400162', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2415', 0),
(63, 63, 'Kesinga', 15, 15, '2400163', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2415', 0),
(64, 64, 'Baliguda', 16, 16, '2400164', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2416', 0),
(65, 65, 'G. Udayagiri', 16, 16, '2400165', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2416', 0),
(66, 66, 'Phulabani', 16, 16, '2400166', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2416', 0),
(67, 67, 'Kendrapara', 17, 17, '2400167', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2417', 0),
(68, 68, 'Pattamundai', 17, 17, '2400168', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2417', 0),
(69, 69, 'Anandapur', 18, 18, '2400169', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2418', 0),
(70, 70, 'Barbil', 18, 18, '2400170', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2418', 0),
(71, 71, 'Champua', 18, 18, '2400171', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2418', 0),
(72, 72, 'Joda', 18, 18, '2400172', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2418', 0),
(73, 73, 'Kendujhar', 18, 18, '2400173', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2418', 0),
(74, 74, 'Balugaon', 19, 19, '2400174', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2419', 0),
(75, 75, 'Banapur', 19, 19, '2400175', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2419', 0),
(76, 76, 'Bhubaneswar', 19, 19, '2400176', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2419', 0),
(77, 77, 'Jatani', 19, 19, '2400177', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2419', 0),
(78, 78, 'Khordha', 19, 19, '2400178', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2419', 0),
(79, 79, 'Jeypur', 20, 20, '2400179', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2420', 0),
(80, 80, 'Koraput', 20, 20, '2400180', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2420', 0),
(81, 81, 'Kotpad', 20, 20, '2400181', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2420', 0),
(82, 82, 'Sunabeda', 20, 20, '2400182', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2420', 0),
(83, 83, 'Balimela', 21, 21, '2400183', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2421', 0),
(84, 84, 'Malkangiri', 21, 21, '2400184', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2421', 0),
(85, 85, 'Baripada', 22, 22, '2400185', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2422', 0),
(86, 86, 'Karanjia', 22, 22, '2400186', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2422', 0),
(87, 87, 'Rairangpur', 22, 22, '2400187', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2422', 0),
(88, 88, 'Udala', 22, 22, '2400188', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2422', 0),
(89, 89, 'Nabarangapur', 23, 23, '2400189', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2423', 0),
(90, 90, 'Umarkote', 23, 23, '2400190', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2423', 0),
(91, 91, 'Dasapalla', 24, 24, '2400191', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2424', 0),
(92, 92, 'Khandapada', 24, 24, '2400192', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2424', 0),
(93, 93, 'Nayagarh', 24, 24, '2400193', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2424', 0),
(94, 94, 'Odogaon', 24, 24, '2400194', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2424', 0),
(95, 95, 'Ranapur', 24, 24, '2400195', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2424', 0),
(96, 96, 'Khariar', 25, 25, '2400196', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2425', 0),
(97, 97, 'Khariar Road', 25, 25, '2400197', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2425', 0),
(98, 98, 'Nuapada', 25, 25, '2400198', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2425', 0),
(99, 99, 'Konark', 26, 26, '2400199', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2426', 0),
(100, 100, 'Nimapada', 26, 26, '2400200', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2426', 0),
(101, 101, 'Pipili', 26, 26, '2400201', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2426', 0),
(102, 102, 'Puri', 26, 26, '2400202', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2426', 0),
(103, 103, 'Gudari', 27, 27, '2400203', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2427', 0),
(104, 104, 'Gunupur', 27, 27, '2400204', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2427', 0),
(105, 105, 'Rayagada', 27, 27, '2400205', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2427', 0),
(106, 106, 'Kochinda', 28, 28, '2400206', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2428', 0),
(107, 107, 'Redhakhol', 28, 28, '2400207', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2428', 0),
(108, 108, 'Sambalpur', 28, 28, '2400208', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2428', 0),
(109, 109, 'Binika', 29, 29, '2400209', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2429', 0),
(110, 110, 'Sonapur', 29, 29, '2400210', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2429', 0),
(111, 111, 'Tarbha', 29, 29, '2400211', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2429', 0),
(113, 112, 'Biramitrapur', 30, 30, '2400212', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2430', 0),
(114, 113, 'Rajagangapur', 30, 30, '2400213', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2430', 0),
(115, 114, 'Raurkela', 30, 30, '2400214', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2430', 0),
(121, 115, 'Sundargarh', 30, 30, '2400215', 1, 1, '0000-00-00 00:00:00', 1, '0000-00-00 00:00:00', 0, '2430', 0),
(122, 0, '', 0, 0, '', 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '', 0),
(123, 0, '', 0, 0, '', 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '', 0),
(124, 0, '', 0, 0, '', 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '', 0),
(125, 0, '', 0, 0, '', 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '', 0),
(126, 0, '', 0, 0, '', 0, 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_users`
--

CREATE TABLE `gz_users` (
  `id` int(11) NOT NULL,
  `login_ID` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `session_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '0="Not Login", 1="Login"',
  `user_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '1="admin", 2="dept"',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `designation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dept_id` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `force_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT '0',
  `email` varchar(96) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `gpf_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `sms_request_count` int(200) NOT NULL,
  `last_sms_request_time` datetime NOT NULL,
  `blocked_until` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `reject_remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `is_logged` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store users';

--
-- Dumping data for table `gz_users`
--

INSERT INTO `gz_users` (`id`, `login_ID`, `session_id`, `user_type`, `name`, `designation`, `username`, `dept_id`, `password`, `force_password`, `email`, `mobile`, `gpf_no`, `is_admin`, `status`, `is_verified`, `sms_request_count`, `last_sms_request_time`, `blocked_until`, `created_at`, `modified_at`, `reject_remarks`, `is_logged`) VALUES
(1, '657647', '0', '1', 'Director', 'PRTG, STY and PUBN', 'admin', 0, '$2y$10$BboLpmNXeCYFGSiwbxNff.vN31c/KMBGKB.p1VgjsE8qGuypxR86y', '0', 'test@gmail.com', '1111122222', '', 1, 1, 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2020-03-12 22:15:28', '0000-00-00 00:00:00', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `gz_user_password_history`
--

CREATE TABLE `gz_user_password_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `password` varchar(500) NOT NULL,
  `force_password` varchar(255) DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='table to store user password history';

-- --------------------------------------------------------

--
-- Table structure for table `gz_visitor_counter`
--

CREATE TABLE `gz_visitor_counter` (
  `id` int(11) NOT NULL,
  `visit_counter` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `gz_visitor_counter`
--

INSERT INTO `gz_visitor_counter` (`id`, `visit_counter`, `updated_at`) VALUES
(1, 0, '2025-09-10 16:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `gz_weekly_gazette`
--

CREATE TABLE `gz_weekly_gazette` (
  `id` int(11) NOT NULL,
  `gazette_type_id` int(11) NOT NULL,
  `status_id` int(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store weekly gazette from different dept.';

-- --------------------------------------------------------

--
-- Table structure for table `gz_weekly_gazette_dept_parts`
--

CREATE TABLE `gz_weekly_gazette_dept_parts` (
  `id` int(11) NOT NULL,
  `gazette_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `year` int(4) DEFAULT NULL,
  `week` int(2) NOT NULL,
  `subject` text COLLATE utf8_unicode_ci NOT NULL,
  `notification_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notification_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reject_remarks` text COLLATE utf8_unicode_ci NOT NULL,
  `tags` text COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store weekly gazette parts ';

-- --------------------------------------------------------

--
-- Table structure for table `gz_weekly_gazette_documents`
--

CREATE TABLE `gz_weekly_gazette_documents` (
  `id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `gazette_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `dept_word_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `dept_pdf_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `dept_signed_pdf_path` text COLLATE utf8_unicode_ci NOT NULL,
  `press_pdf_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `press_signed_pdf_path` text COLLATE utf8_unicode_ci NOT NULL,
  `press_signed_pdf_file_size` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store weekly gazette documents';

-- --------------------------------------------------------

--
-- Table structure for table `gz_weekly_gazette_documents_history`
--

CREATE TABLE `gz_weekly_gazette_documents_history` (
  `id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `gazette_id` int(11) NOT NULL,
  `gz_weekly_document_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `word_file_path` text NOT NULL,
  `pdf_file_path` text NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `gz_weekly_gazette_notification`
--

CREATE TABLE `gz_weekly_gazette_notification` (
  `id` int(11) NOT NULL,
  `gazette_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `responsible_user_id` int(11) NOT NULL COMMENT 'To whom, Notification will visible.',
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `is_read` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store weekly gazette notification';

-- --------------------------------------------------------

--
-- Table structure for table `gz_weekly_gazette_part_wise_press_approved`
--

CREATE TABLE `gz_weekly_gazette_part_wise_press_approved` (
  `id` int(11) NOT NULL,
  `dept_part_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='store part wise weekly gazette saved by press';

-- --------------------------------------------------------

--
-- Table structure for table `gz_weekly_gazette_publish_date`
--

CREATE TABLE `gz_weekly_gazette_publish_date` (
  `id` int(11) NOT NULL,
  `day_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `day_value` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store weekly gazette publish day';

--
-- Dumping data for table `gz_weekly_gazette_publish_date`
--

INSERT INTO `gz_weekly_gazette_publish_date` (`id`, `day_name`, `day_value`) VALUES
(1, 'Friday', 4);

-- --------------------------------------------------------

--
-- Table structure for table `gz_weekly_gazette_status`
--

CREATE TABLE `gz_weekly_gazette_status` (
  `id` int(11) NOT NULL,
  `gazette_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store weekly gazette status';

-- --------------------------------------------------------

--
-- Table structure for table `gz_weekly_part_wise_approved_merged_documents`
--

CREATE TABLE `gz_weekly_part_wise_approved_merged_documents` (
  `id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `month` int(2) NOT NULL,
  `week` int(2) NOT NULL,
  `sl_no` int(11) NOT NULL,
  `issue_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sakabda_date` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `saka_month` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `saka_date` tinyint(2) DEFAULT NULL,
  `saka_year` year(4) DEFAULT NULL,
  `word_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `dept_merged_pdf` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `pdf_file_path` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store part wise converted approved documents';

-- --------------------------------------------------------

--
-- Table structure for table `gz_weekly_part_wise_page`
--

CREATE TABLE `gz_weekly_part_wise_page` (
  `id` int(55) NOT NULL,
  `year` year(4) DEFAULT NULL,
  `week` int(11) DEFAULT NULL,
  `part_id` int(11) DEFAULT NULL,
  `page_start` int(55) DEFAULT NULL,
  `page_end` int(55) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ms_blf_block_details`
--

CREATE TABLE `ms_blf_block_details` (
  `id` int(55) NOT NULL,
  `blf_id` int(55) NOT NULL,
  `block_id` int(55) NOT NULL,
  `block_unique_id` int(55) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_at` date NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ms_temp_block_shifting`
--

CREATE TABLE `ms_temp_block_shifting` (
  `id` int(55) NOT NULL,
  `user_id` varchar(100) DEFAULT NULL,
  `district_id` int(55) DEFAULT NULL,
  `requested_date` datetime NOT NULL,
  `block_id` int(55) NOT NULL,
  `src_district_id` int(55) NOT NULL,
  `dest_district_id` int(55) NOT NULL,
  `is_approved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `test_ta`
--

CREATE TABLE `test_ta` (
  `id` int(55) NOT NULL,
  `state_id` int(55) NOT NULL,
  `district_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district_code` varchar(55) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table to store districts';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gz_activity_log`
--
ALTER TABLE `gz_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `gz_applicants_details`
--
ALTER TABLE `gz_applicants_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `mobile` (`mobile`),
  ADD KEY `email` (`email`),
  ADD KEY `is_logged` (`is_logged`),
  ADD KEY `otp_verified` (`otp_verified`),
  ADD KEY `status` (`status`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `relation_id` (`relation_id`);

--
-- Indexes for table `gz_applicant_otp`
--
ALTER TABLE `gz_applicant_otp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otp` (`otp`),
  ADD KEY `expired_at` (`expired_at`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `gz_applicant_password_history`
--
ALTER TABLE `gz_applicant_password_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_archival_extraordinary_gazettes`
--
ALTER TABLE `gz_archival_extraordinary_gazettes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `notification_type_id` (`notification_type_id`),
  ADD KEY `status` (`status`),
  ADD KEY `deleted` (`deleted`);

--
-- Indexes for table `gz_archival_weekly_gazettes`
--
ALTER TABLE `gz_archival_weekly_gazettes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `week_id` (`week_id`),
  ADD KEY `status` (`status`),
  ADD KEY `deleted` (`deleted`);

--
-- Indexes for table `gz_block_master`
--
ALTER TABLE `gz_block_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dist_mst_blck_fks` (`district_id`),
  ADD KEY `uni_id` (`unique_id`),
  ADD KEY `dis_uni_id` (`district_unique_id`);

--
-- Indexes for table `gz_change_of_applicant_gender_notice_details`
--
ALTER TABLE `gz_change_of_applicant_gender_notice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_gender_document_det`
--
ALTER TABLE `gz_change_of_gender_document_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_gender_document_master`
--
ALTER TABLE `gz_change_of_gender_document_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_gender_history`
--
ALTER TABLE `gz_change_of_gender_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_gender_master`
--
ALTER TABLE `gz_change_of_gender_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_type_id` (`gazette_type_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `district_id` (`district_id`),
  ADD KEY `police_station_id` (`police_station_id`);

--
-- Indexes for table `gz_change_of_gender_payment_details`
--
ALTER TABLE `gz_change_of_gender_payment_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `change_gender_id` (`change_gender_id`);

--
-- Indexes for table `gz_change_of_gender_remarks_master`
--
ALTER TABLE `gz_change_of_gender_remarks_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_gender_status_his`
--
ALTER TABLE `gz_change_of_gender_status_his`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_gender_status_master`
--
ALTER TABLE `gz_change_of_gender_status_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_name_surname_document_master`
--
ALTER TABLE `gz_change_of_name_surname_document_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_name_surname_doument_det`
--
ALTER TABLE `gz_change_of_name_surname_doument_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_name_surname_history`
--
ALTER TABLE `gz_change_of_name_surname_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_name_surname_master`
--
ALTER TABLE `gz_change_of_name_surname_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_type_id` (`gazette_type_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `district_id` (`district_id`),
  ADD KEY `police_station_id` (`police_station_id`);

--
-- Indexes for table `gz_change_of_name_surname_payment_details`
--
ALTER TABLE `gz_change_of_name_surname_payment_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `change_name_surname_id` (`change_name_surname_id`);

--
-- Indexes for table `gz_change_of_name_surname_remarks_master`
--
ALTER TABLE `gz_change_of_name_surname_remarks_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_name_surname_status_his`
--
ALTER TABLE `gz_change_of_name_surname_status_his`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_name_surname_status_master`
--
ALTER TABLE `gz_change_of_name_surname_status_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_partnership_history`
--
ALTER TABLE `gz_change_of_partnership_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_partnership_make_pay`
--
ALTER TABLE `gz_change_of_partnership_make_pay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `par_id` (`par_id`),
  ADD KEY `file_no` (`file_no`),
  ADD KEY `deptRefId` (`deptRefId`),
  ADD KEY `challanRefId` (`challanRefId`),
  ADD KEY `pay_mode` (`pay_mode`),
  ADD KEY `bank_trans_id` (`bank_trans_id`),
  ADD KEY `bankTransactionStatus` (`bankTransactionStatus`),
  ADD KEY `status` (`status`),
  ADD KEY `deleted` (`deleted`);

--
-- Indexes for table `gz_change_of_partnership_master`
--
ALTER TABLE `gz_change_of_partnership_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_type_id` (`gazette_type_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `district_id` (`district_id`),
  ADD KEY `police_station_id` (`police_station_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `gz_change_of_partnership_pan_det`
--
ALTER TABLE `gz_change_of_partnership_pan_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_partnetship_doument_det`
--
ALTER TABLE `gz_change_of_partnetship_doument_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_par_aadhar_det`
--
ALTER TABLE `gz_change_of_par_aadhar_det`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_change_of_par_status_his`
--
ALTER TABLE `gz_change_of_par_status_his`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_cms_content`
--
ALTER TABLE `gz_cms_content`
  ADD PRIMARY KEY (`cms_id`),
  ADD KEY `cms_type` (`cms_type`);

--
-- Indexes for table `gz_con_surname_applicant_notice_details`
--
ALTER TABLE `gz_con_surname_applicant_notice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_c_and_t`
--
ALTER TABLE `gz_c_and_t`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_c_and_t_password_history`
--
ALTER TABLE `gz_c_and_t_password_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `gz_department`
--
ALTER TABLE `gz_department`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `gz_dep_data_table`
--
ALTER TABLE `gz_dep_data_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_designation`
--
ALTER TABLE `gz_designation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_district_master`
--
ALTER TABLE `gz_district_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_mst_fk` (`state_id`);

--
-- Indexes for table `gz_documents`
--
ALTER TABLE `gz_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_id` (`gazette_id`);
ALTER TABLE `gz_documents` ADD FULLTEXT KEY `press_signed_pdf_path` (`press_signed_pdf_path`);

--
-- Indexes for table `gz_documents_history`
--
ALTER TABLE `gz_documents_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_esign_transaction`
--
ALTER TABLE `gz_esign_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trns_id` (`trns_id`),
  ADD KEY `gazette_id` (`gazette_id`),
  ADD KEY `type` (`type`),
  ADD KEY `category` (`category`),
  ADD KEY `signed_name` (`signed_name`),
  ADD KEY `designation` (`designation`),
  ADD KEY `part_id` (`part_id`),
  ADD KEY `dept` (`dept`),
  ADD KEY `source_file_path` (`source_file_path`(255));

--
-- Indexes for table `gz_feedback`
--
ALTER TABLE `gz_feedback`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `gz_feedback` ADD FULLTEXT KEY `email` (`email`);
ALTER TABLE `gz_feedback` ADD FULLTEXT KEY `mobile` (`mobile`);
ALTER TABLE `gz_feedback` ADD FULLTEXT KEY `subject` (`subject`);

--
-- Indexes for table `gz_final_weekly_gazette`
--
ALTER TABLE `gz_final_weekly_gazette`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_gazette`
--
ALTER TABLE `gz_gazette`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `sro_available` (`sro_available`),
  ADD KEY `letter_no` (`letter_no`),
  ADD KEY `gazette_type_id` (`gazette_type_id`),
  ADD KEY `notification_type` (`notification_type`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `is_paid` (`is_paid`),
  ADD KEY `sl_no` (`sl_no`);
ALTER TABLE `gz_gazette` ADD FULLTEXT KEY `subject` (`subject`);
ALTER TABLE `gz_gazette` ADD FULLTEXT KEY `notification_number` (`notification_number`);
ALTER TABLE `gz_gazette` ADD FULLTEXT KEY `issue_date` (`issue_date`);
ALTER TABLE `gz_gazette` ADD FULLTEXT KEY `tags` (`tags`);

--
-- Indexes for table `gz_gazette_parts`
--
ALTER TABLE `gz_gazette_parts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_gazette_status`
--
ALTER TABLE `gz_gazette_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_id` (`gazette_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `gz_gazette_text`
--
ALTER TABLE `gz_gazette_text`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_gazette_type`
--
ALTER TABLE `gz_gazette_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_type` (`gazette_type`);

--
-- Indexes for table `gz_hod`
--
ALTER TABLE `gz_hod`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_igr_password_history`
--
ALTER TABLE `gz_igr_password_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `gz_igr_users`
--
ALTER TABLE `gz_igr_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_modules`
--
ALTER TABLE `gz_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_modules_wise_pricing`
--
ALTER TABLE `gz_modules_wise_pricing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_notification`
--
ALTER TABLE `gz_notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_id` (`gazette_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `is_read` (`is_read`);

--
-- Indexes for table `gz_notification_applicant`
--
ALTER TABLE `gz_notification_applicant`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_notification_ct`
--
ALTER TABLE `gz_notification_ct`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_notification_govt`
--
ALTER TABLE `gz_notification_govt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_notification_igr`
--
ALTER TABLE `gz_notification_igr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_notification_type`
--
ALTER TABLE `gz_notification_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_offline_approver_users`
--
ALTER TABLE `gz_offline_approver_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_partnership_docu_det_master`
--
ALTER TABLE `gz_partnership_docu_det_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_par_partnership_chang_remark`
--
ALTER TABLE `gz_par_partnership_chang_remark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_par_sur_status_master`
--
ALTER TABLE `gz_par_sur_status_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_payment_of_cost_payment_details`
--
ALTER TABLE `gz_payment_of_cost_payment_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_id` (`gazette_id`),
  ADD KEY `gazette_type_id` (`gazette_type_id`);

--
-- Indexes for table `gz_payment_of_cost_payment_status_history`
--
ALTER TABLE `gz_payment_of_cost_payment_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `gz_payment_status_history`
--
ALTER TABLE `gz_payment_status_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_police_station_master`
--
ALTER TABLE `gz_police_station_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `district_id` (`district_id`);

--
-- Indexes for table `gz_relation_master`
--
ALTER TABLE `gz_relation_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `deleted` (`deleted`);

--
-- Indexes for table `gz_settings`
--
ALTER TABLE `gz_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setting_key` (`setting_key`),
  ADD KEY `action_key` (`action_key`),
  ADD KEY `action_value` (`action_value`);

--
-- Indexes for table `gz_site_launch`
--
ALTER TABLE `gz_site_launch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_states_master`
--
ALTER TABLE `gz_states_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_status`
--
ALTER TABLE `gz_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_ulb_master`
--
ALTER TABLE `gz_ulb_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_users`
--
ALTER TABLE `gz_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_id` (`login_ID`),
  ADD KEY `username` (`username`),
  ADD KEY `email` (`email`),
  ADD KEY `mobile` (`mobile`),
  ADD KEY `gpf_no` (`gpf_no`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `is_admin` (`is_admin`),
  ADD KEY `is_verified` (`is_verified`),
  ADD KEY `is_logged` (`is_logged`);

--
-- Indexes for table `gz_user_password_history`
--
ALTER TABLE `gz_user_password_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `gz_visitor_counter`
--
ALTER TABLE `gz_visitor_counter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_weekly_gazette`
--
ALTER TABLE `gz_weekly_gazette`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_type_id` (`gazette_type_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `gz_weekly_gazette_dept_parts`
--
ALTER TABLE `gz_weekly_gazette_dept_parts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_id` (`gazette_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `part_id` (`part_id`),
  ADD KEY `week` (`week`),
  ADD KEY `notification_type` (`notification_type`);
ALTER TABLE `gz_weekly_gazette_dept_parts` ADD FULLTEXT KEY `subject` (`subject`);
ALTER TABLE `gz_weekly_gazette_dept_parts` ADD FULLTEXT KEY `notification_number` (`notification_number`);
ALTER TABLE `gz_weekly_gazette_dept_parts` ADD FULLTEXT KEY `tags` (`tags`);

--
-- Indexes for table `gz_weekly_gazette_documents`
--
ALTER TABLE `gz_weekly_gazette_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `part_id` (`part_id`),
  ADD KEY `gazette_id` (`gazette_id`),
  ADD KEY `dept_id` (`dept_id`);
ALTER TABLE `gz_weekly_gazette_documents` ADD FULLTEXT KEY `press_signed_pdf_path` (`press_signed_pdf_path`);

--
-- Indexes for table `gz_weekly_gazette_documents_history`
--
ALTER TABLE `gz_weekly_gazette_documents_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_weekly_gazette_notification`
--
ALTER TABLE `gz_weekly_gazette_notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_id` (`gazette_id`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `part_id` (`part_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `is_read` (`is_read`);

--
-- Indexes for table `gz_weekly_gazette_part_wise_press_approved`
--
ALTER TABLE `gz_weekly_gazette_part_wise_press_approved`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dept_part_id` (`dept_part_id`);

--
-- Indexes for table `gz_weekly_gazette_publish_date`
--
ALTER TABLE `gz_weekly_gazette_publish_date`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gz_weekly_gazette_status`
--
ALTER TABLE `gz_weekly_gazette_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gazette_id` (`gazette_id`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `part_id` (`part_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `gz_weekly_part_wise_approved_merged_documents`
--
ALTER TABLE `gz_weekly_part_wise_approved_merged_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `part_id` (`part_id`),
  ADD KEY `year` (`year`),
  ADD KEY `month` (`month`),
  ADD KEY `week` (`week`),
  ADD KEY `sl_no` (`sl_no`),
  ADD KEY `issue_date` (`issue_date`);

--
-- Indexes for table `gz_weekly_part_wise_page`
--
ALTER TABLE `gz_weekly_part_wise_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ms_blf_block_details`
--
ALTER TABLE `ms_blf_block_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blf_id` (`blf_id`),
  ADD KEY `block_id` (`block_id`),
  ADD KEY `block_unique_id` (`block_unique_id`);

--
-- Indexes for table `test_ta`
--
ALTER TABLE `test_ta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `state_id` (`state_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gz_activity_log`
--
ALTER TABLE `gz_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_applicants_details`
--
ALTER TABLE `gz_applicants_details`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_applicant_otp`
--
ALTER TABLE `gz_applicant_otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_applicant_password_history`
--
ALTER TABLE `gz_applicant_password_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_archival_extraordinary_gazettes`
--
ALTER TABLE `gz_archival_extraordinary_gazettes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_archival_weekly_gazettes`
--
ALTER TABLE `gz_archival_weekly_gazettes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_block_master`
--
ALTER TABLE `gz_block_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=315;

--
-- AUTO_INCREMENT for table `gz_change_of_applicant_gender_notice_details`
--
ALTER TABLE `gz_change_of_applicant_gender_notice_details`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_gender_document_det`
--
ALTER TABLE `gz_change_of_gender_document_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_gender_document_master`
--
ALTER TABLE `gz_change_of_gender_document_master`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `gz_change_of_gender_history`
--
ALTER TABLE `gz_change_of_gender_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_gender_master`
--
ALTER TABLE `gz_change_of_gender_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `gz_change_of_gender_payment_details`
--
ALTER TABLE `gz_change_of_gender_payment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_gender_remarks_master`
--
ALTER TABLE `gz_change_of_gender_remarks_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_gender_status_his`
--
ALTER TABLE `gz_change_of_gender_status_his`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_gender_status_master`
--
ALTER TABLE `gz_change_of_gender_status_master`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `gz_change_of_name_surname_document_master`
--
ALTER TABLE `gz_change_of_name_surname_document_master`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `gz_change_of_name_surname_doument_det`
--
ALTER TABLE `gz_change_of_name_surname_doument_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_name_surname_history`
--
ALTER TABLE `gz_change_of_name_surname_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_name_surname_master`
--
ALTER TABLE `gz_change_of_name_surname_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_name_surname_payment_details`
--
ALTER TABLE `gz_change_of_name_surname_payment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gz_change_of_name_surname_remarks_master`
--
ALTER TABLE `gz_change_of_name_surname_remarks_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_name_surname_status_his`
--
ALTER TABLE `gz_change_of_name_surname_status_his`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_name_surname_status_master`
--
ALTER TABLE `gz_change_of_name_surname_status_master`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `gz_change_of_partnership_history`
--
ALTER TABLE `gz_change_of_partnership_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `gz_change_of_partnership_make_pay`
--
ALTER TABLE `gz_change_of_partnership_make_pay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_change_of_partnership_master`
--
ALTER TABLE `gz_change_of_partnership_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gz_change_of_partnership_pan_det`
--
ALTER TABLE `gz_change_of_partnership_pan_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gz_change_of_partnetship_doument_det`
--
ALTER TABLE `gz_change_of_partnetship_doument_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gz_change_of_par_aadhar_det`
--
ALTER TABLE `gz_change_of_par_aadhar_det`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `gz_change_of_par_status_his`
--
ALTER TABLE `gz_change_of_par_status_his`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `gz_cms_content`
--
ALTER TABLE `gz_cms_content`
  MODIFY `cms_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gz_con_surname_applicant_notice_details`
--
ALTER TABLE `gz_con_surname_applicant_notice_details`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_c_and_t`
--
ALTER TABLE `gz_c_and_t`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `gz_c_and_t_password_history`
--
ALTER TABLE `gz_c_and_t_password_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_department`
--
ALTER TABLE `gz_department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `gz_dep_data_table`
--
ALTER TABLE `gz_dep_data_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_designation`
--
ALTER TABLE `gz_designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `gz_district_master`
--
ALTER TABLE `gz_district_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `gz_documents`
--
ALTER TABLE `gz_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gz_documents_history`
--
ALTER TABLE `gz_documents_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gz_esign_transaction`
--
ALTER TABLE `gz_esign_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_feedback`
--
ALTER TABLE `gz_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_final_weekly_gazette`
--
ALTER TABLE `gz_final_weekly_gazette`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_gazette`
--
ALTER TABLE `gz_gazette`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_gazette_parts`
--
ALTER TABLE `gz_gazette_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `gz_gazette_status`
--
ALTER TABLE `gz_gazette_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_gazette_text`
--
ALTER TABLE `gz_gazette_text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_gazette_type`
--
ALTER TABLE `gz_gazette_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gz_hod`
--
ALTER TABLE `gz_hod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gz_igr_password_history`
--
ALTER TABLE `gz_igr_password_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_igr_users`
--
ALTER TABLE `gz_igr_users`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gz_modules`
--
ALTER TABLE `gz_modules`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `gz_modules_wise_pricing`
--
ALTER TABLE `gz_modules_wise_pricing`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `gz_notification`
--
ALTER TABLE `gz_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_notification_applicant`
--
ALTER TABLE `gz_notification_applicant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_notification_ct`
--
ALTER TABLE `gz_notification_ct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_notification_govt`
--
ALTER TABLE `gz_notification_govt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_notification_igr`
--
ALTER TABLE `gz_notification_igr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_notification_type`
--
ALTER TABLE `gz_notification_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `gz_offline_approver_users`
--
ALTER TABLE `gz_offline_approver_users`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gz_partnership_docu_det_master`
--
ALTER TABLE `gz_partnership_docu_det_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gz_par_partnership_chang_remark`
--
ALTER TABLE `gz_par_partnership_chang_remark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_par_sur_status_master`
--
ALTER TABLE `gz_par_sur_status_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `gz_payment_of_cost_payment_details`
--
ALTER TABLE `gz_payment_of_cost_payment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_payment_of_cost_payment_status_history`
--
ALTER TABLE `gz_payment_of_cost_payment_status_history`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_payment_status_history`
--
ALTER TABLE `gz_payment_status_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_police_station_master`
--
ALTER TABLE `gz_police_station_master`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=418;

--
-- AUTO_INCREMENT for table `gz_relation_master`
--
ALTER TABLE `gz_relation_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gz_settings`
--
ALTER TABLE `gz_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `gz_site_launch`
--
ALTER TABLE `gz_site_launch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gz_states_master`
--
ALTER TABLE `gz_states_master`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `gz_status`
--
ALTER TABLE `gz_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `gz_ulb_master`
--
ALTER TABLE `gz_ulb_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `gz_users`
--
ALTER TABLE `gz_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `gz_user_password_history`
--
ALTER TABLE `gz_user_password_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_visitor_counter`
--
ALTER TABLE `gz_visitor_counter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gz_weekly_gazette`
--
ALTER TABLE `gz_weekly_gazette`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_weekly_gazette_dept_parts`
--
ALTER TABLE `gz_weekly_gazette_dept_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_weekly_gazette_documents`
--
ALTER TABLE `gz_weekly_gazette_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_weekly_gazette_documents_history`
--
ALTER TABLE `gz_weekly_gazette_documents_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_weekly_gazette_notification`
--
ALTER TABLE `gz_weekly_gazette_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_weekly_gazette_part_wise_press_approved`
--
ALTER TABLE `gz_weekly_gazette_part_wise_press_approved`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_weekly_gazette_publish_date`
--
ALTER TABLE `gz_weekly_gazette_publish_date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gz_weekly_gazette_status`
--
ALTER TABLE `gz_weekly_gazette_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_weekly_part_wise_approved_merged_documents`
--
ALTER TABLE `gz_weekly_part_wise_approved_merged_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gz_weekly_part_wise_page`
--
ALTER TABLE `gz_weekly_part_wise_page`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `test_ta`
--
ALTER TABLE `test_ta`
  MODIFY `id` int(55) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
