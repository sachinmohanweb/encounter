-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 15, 2025 at 12:24 PM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u931425719_holy_bible_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `book_images`
--

CREATE TABLE `book_images` (
  `id` int(11) NOT NULL,
  `bible_id` int(11) NOT NULL,
  `testament_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `image` varchar(256) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_images`
--

INSERT INTO `book_images` (`id`, `bible_id`, `testament_id`, `book_id`, `image`, `status`, `created_at`, `updated_at`) VALUES
(37, 1, 1, 2, 'storage/book_thumb/book_thumb_2_1733820363.jpg', 1, '2024-12-10 08:46:03', '2024-12-10 08:46:03'),
(38, 1, 1, 3, 'storage/book_thumb/book_thumb_3_1733820469.jpg', 1, '2024-12-10 08:47:49', '2024-12-10 08:47:49'),
(39, 1, 1, 4, 'storage/book_thumb/book_thumb_4_1733820481.jpg', 1, '2024-12-10 08:48:01', '2024-12-10 08:48:01'),
(40, 1, 1, 5, 'storage/book_thumb/book_thumb_5_1733820495.jpg', 1, '2024-12-10 08:48:15', '2024-12-10 08:48:15'),
(41, 1, 1, 6, 'storage/book_thumb/book_thumb_6_1733820508.jpg', 1, '2024-12-10 08:48:28', '2024-12-10 08:48:28'),
(43, 1, 1, 7, 'storage/book_thumb/book_thumb_7_1733820531.jpg', 1, '2024-12-10 08:48:51', '2024-12-10 08:48:51'),
(44, 1, 1, 8, 'storage/book_thumb/book_thumb_8_1733820544.jpg', 1, '2024-12-10 08:49:04', '2024-12-10 08:49:04'),
(45, 1, 1, 9, 'storage/book_thumb/book_thumb_9_1733820556.jpg', 1, '2024-12-10 08:49:16', '2024-12-10 08:49:16'),
(46, 1, 1, 10, 'storage/book_thumb/book_thumb_10_1733820565.jpg', 1, '2024-12-10 08:49:25', '2024-12-10 08:49:25'),
(47, 1, 1, 11, 'storage/book_thumb/book_thumb_11_1733820577.jpg', 1, '2024-12-10 08:49:37', '2024-12-10 08:49:37'),
(48, 1, 1, 12, 'storage/book_thumb/book_thumb_12_1733820589.jpg', 1, '2024-12-10 08:49:49', '2024-12-10 08:49:49'),
(49, 1, 1, 13, 'storage/book_thumb/book_thumb_13_1733820599.jpg', 1, '2024-12-10 08:49:59', '2024-12-10 08:49:59'),
(50, 1, 1, 14, 'storage/book_thumb/book_thumb_14_1733820614.jpg', 1, '2024-12-10 08:50:14', '2024-12-10 08:50:14'),
(51, 1, 1, 15, 'storage/book_thumb/book_thumb_15_1733820627.jpg', 1, '2024-12-10 08:50:27', '2024-12-10 08:50:27'),
(52, 1, 1, 16, 'storage/book_thumb/book_thumb_16_1733820640.jpg', 1, '2024-12-10 08:50:40', '2024-12-10 08:50:40'),
(53, 1, 1, 17, 'storage/book_thumb/book_thumb_17_1733907371.jpg', 1, '2024-12-11 08:56:11', '2024-12-11 08:56:11'),
(54, 1, 1, 18, 'storage/book_thumb/book_thumb_18_1733907613.jpg', 1, '2024-12-11 09:00:13', '2024-12-11 09:00:13'),
(55, 1, 1, 19, 'storage/book_thumb/book_thumb_19_1733907627.jpg', 1, '2024-12-11 09:00:27', '2024-12-11 09:00:27'),
(56, 1, 1, 20, 'storage/book_thumb/book_thumb_20_1733907638.jpg', 1, '2024-12-11 09:00:38', '2024-12-11 09:00:38'),
(57, 1, 1, 21, 'storage/book_thumb/book_thumb_21_1733907651.jpg', 1, '2024-12-11 09:00:51', '2024-12-11 09:00:51'),
(58, 1, 1, 22, 'storage/book_thumb/book_thumb_22_1733907663.jpg', 1, '2024-12-11 09:01:03', '2024-12-11 09:01:03'),
(59, 1, 1, 23, 'storage/book_thumb/book_thumb_23_1733907681.jpg', 1, '2024-12-11 09:01:21', '2024-12-11 09:01:21'),
(61, 1, 1, 24, 'storage/book_thumb/book_thumb_24_1733907697.jpg', 1, '2024-12-11 09:01:37', '2024-12-11 09:01:37'),
(63, 1, 1, 26, 'storage/book_thumb/book_thumb_26_1733907733.jpg', 1, '2024-12-11 09:02:13', '2024-12-11 09:02:13'),
(64, 1, 1, 27, 'storage/book_thumb/book_thumb_27_1733907745.jpg', 1, '2024-12-11 09:02:25', '2024-12-11 09:02:25'),
(65, 1, 1, 25, 'storage/book_thumb/book_thumb_25_1733907864.jpg', 1, '2024-12-11 09:04:24', '2024-12-11 09:04:24'),
(66, 1, 1, 28, 'storage/book_thumb/book_thumb_28_1733907882.jpg', 1, '2024-12-11 09:04:42', '2024-12-11 09:04:42'),
(67, 1, 1, 29, 'storage/book_thumb/book_thumb_29_1733907896.jpg', 1, '2024-12-11 09:04:56', '2024-12-11 09:04:56'),
(68, 1, 1, 30, 'storage/book_thumb/book_thumb_30_1734022946.jpg', 1, '2024-12-12 17:02:26', '2024-12-12 17:02:26'),
(69, 1, 1, 31, 'storage/book_thumb/book_thumb_31_1734022959.jpg', 1, '2024-12-12 17:02:39', '2024-12-12 17:02:39'),
(70, 1, 1, 32, 'storage/book_thumb/book_thumb_32_1734022979.jpg', 1, '2024-12-12 17:02:59', '2024-12-12 17:02:59'),
(71, 1, 1, 33, 'storage/book_thumb/book_thumb_33_1734023008.jpg', 1, '2024-12-12 17:03:28', '2024-12-12 17:03:28'),
(72, 1, 1, 34, 'storage/book_thumb/book_thumb_34_1734023023.jpg', 1, '2024-12-12 17:03:43', '2024-12-12 17:03:43'),
(73, 1, 1, 35, 'storage/book_thumb/book_thumb_35_1734023035.jpg', 1, '2024-12-12 17:03:55', '2024-12-12 17:03:55'),
(74, 1, 1, 36, 'storage/book_thumb/book_thumb_36_1734023049.jpg', 1, '2024-12-12 17:04:09', '2024-12-12 17:04:09'),
(75, 1, 1, 37, 'storage/book_thumb/book_thumb_37_1734023064.jpg', 1, '2024-12-12 17:04:24', '2024-12-12 17:04:24'),
(76, 1, 1, 38, 'storage/book_thumb/book_thumb_38_1734023079.jpg', 1, '2024-12-12 17:04:39', '2024-12-12 17:04:39'),
(77, 1, 1, 39, 'storage/book_thumb/book_thumb_39_1734023097.jpg', 1, '2024-12-12 17:04:57', '2024-12-12 17:04:57'),
(78, 1, 1, 40, 'storage/book_thumb/book_thumb_40_1734023119.jpg', 1, '2024-12-12 17:05:19', '2024-12-12 17:05:19'),
(79, 1, 1, 41, 'storage/book_thumb/book_thumb_41_1734023131.jpg', 1, '2024-12-12 17:05:31', '2024-12-12 17:05:31'),
(80, 1, 1, 42, 'storage/book_thumb/book_thumb_42_1734023143.jpg', 1, '2024-12-12 17:05:43', '2024-12-12 17:05:43'),
(81, 1, 1, 43, 'storage/book_thumb/book_thumb_43_1734074731.png', 1, '2024-12-13 07:25:31', '2024-12-13 07:25:31'),
(82, 1, 1, 44, 'storage/book_thumb/book_thumb_44_1734074748.png', 1, '2024-12-13 07:25:48', '2024-12-13 07:25:48'),
(83, 1, 1, 45, 'storage/book_thumb/book_thumb_45_1734074760.png', 1, '2024-12-13 07:26:00', '2024-12-13 07:26:00'),
(84, 1, 1, 46, 'storage/book_thumb/book_thumb_46_1734074776.png', 1, '2024-12-13 07:26:16', '2024-12-13 07:26:16'),
(85, 1, 2, 47, 'storage/book_thumb/book_thumb_47_1734076907.png', 1, '2024-12-13 08:01:47', '2024-12-13 08:01:47'),
(86, 1, 2, 48, 'storage/book_thumb/book_thumb_48_1734076927.png', 1, '2024-12-13 08:02:07', '2024-12-13 08:02:07'),
(87, 1, 1, 49, 'storage/book_thumb/book_thumb_49_1734076940.png', 1, '2024-12-13 08:02:20', '2024-12-13 08:02:20'),
(88, 1, 2, 50, 'storage/book_thumb/book_thumb_50_1734076959.png', 1, '2024-12-13 08:02:39', '2024-12-13 08:02:39'),
(89, 1, 2, 51, 'storage/book_thumb/book_thumb_51_1734077429.png', 1, '2024-12-13 08:10:29', '2024-12-13 08:10:29'),
(90, 1, 2, 52, 'storage/book_thumb/book_thumb_52_1734077447.png', 1, '2024-12-13 08:10:47', '2024-12-13 08:10:47'),
(91, 1, 2, 53, 'storage/book_thumb/book_thumb_53_1734078603.png', 1, '2024-12-13 08:30:03', '2024-12-13 08:30:03'),
(92, 1, 2, 54, 'storage/book_thumb/book_thumb_54_1734078621.png', 1, '2024-12-13 08:30:21', '2024-12-13 08:30:21'),
(93, 1, 1, 55, 'storage/book_thumb/book_thumb_55_1734079208.png', 1, '2024-12-13 08:40:08', '2024-12-13 08:40:08'),
(94, 1, 2, 56, 'storage/book_thumb/book_thumb_56_1734081329.png', 1, '2024-12-13 09:15:29', '2024-12-13 09:15:29'),
(95, 1, 2, 57, 'storage/book_thumb/book_thumb_57_1734082198.png', 1, '2024-12-13 09:29:58', '2024-12-13 09:29:58'),
(97, 1, 2, 59, 'storage/book_thumb/book_thumb_59_1734083244.png', 1, '2024-12-13 09:47:24', '2024-12-13 09:47:24'),
(98, 1, 2, 60, 'storage/book_thumb/book_thumb_60_1734083353.png', 1, '2024-12-13 09:49:13', '2024-12-13 09:49:13'),
(99, 1, 2, 61, 'storage/book_thumb/book_thumb_61_1734084108.png', 1, '2024-12-13 10:01:48', '2024-12-13 10:01:48'),
(100, 1, 2, 62, 'storage/book_thumb/book_thumb_62_1734084122.png', 1, '2024-12-13 10:02:02', '2024-12-13 10:02:02'),
(101, 1, 2, 63, 'storage/book_thumb/book_thumb_63_1734097745.png', 1, '2024-12-13 13:49:05', '2024-12-13 13:49:05'),
(102, 1, 2, 64, 'storage/book_thumb/book_thumb_64_1734097779.png', 1, '2024-12-13 13:49:39', '2024-12-13 13:49:39'),
(103, 1, 2, 65, 'storage/book_thumb/book_thumb_65_1734097801.png', 1, '2024-12-13 13:50:01', '2024-12-13 13:50:01'),
(104, 1, 2, 66, 'storage/book_thumb/book_thumb_66_1734097822.png', 1, '2024-12-13 13:50:22', '2024-12-13 13:50:22'),
(105, 1, 2, 67, 'storage/book_thumb/book_thumb_67_1734097859.png', 1, '2024-12-13 13:50:59', '2024-12-13 13:50:59'),
(106, 1, 2, 68, 'storage/book_thumb/book_thumb_68_1734097879.png', 1, '2024-12-13 13:51:19', '2024-12-13 13:51:19'),
(107, 1, 2, 69, 'storage/book_thumb/book_thumb_69_1734097900.png', 1, '2024-12-13 13:51:40', '2024-12-13 13:51:40'),
(108, 1, 2, 70, 'storage/book_thumb/book_thumb_70_1734097922.png', 1, '2024-12-13 13:52:02', '2024-12-13 13:52:02'),
(109, 1, 2, 71, 'storage/book_thumb/book_thumb_71_1734097941.png', 1, '2024-12-13 13:52:21', '2024-12-13 13:52:21'),
(110, 1, 2, 72, 'storage/book_thumb/book_thumb_72_1734097964.png', 1, '2024-12-13 13:52:44', '2024-12-13 13:52:44'),
(111, 1, 2, 73, 'storage/book_thumb/book_thumb_73_1734097982.png', 1, '2024-12-13 13:53:02', '2024-12-13 13:53:02'),
(112, 1, 2, 58, 'storage/book_thumb/book_thumb_58_1734098356.png', 1, '2024-12-13 13:59:16', '2024-12-13 13:59:16'),
(113, 1, 1, 1, 'storage/book_thumb/book_thumb_1_1734098747.png', 1, '2024-12-13 14:05:47', '2024-12-13 14:05:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book_images`
--
ALTER TABLE `book_images`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book_images`
--
ALTER TABLE `book_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
