-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 23, 2025 at 03:11 PM
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
-- Database: `u931425719_encounter_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `bible_verse_themes`
--

CREATE TABLE `bible_verse_themes` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bible_verse_themes`
--

INSERT INTO `bible_verse_themes` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'General', 1, '2024-06-24 14:32:20', '2024-06-24 14:32:20'),
(2, 'Christmas', 1, '2024-06-24 14:32:20', '2024-06-24 14:32:20'),
(3, 'Easter', 1, '2024-06-24 14:32:20', '2024-06-24 14:32:20'),
(4, 'Period of Lent', 1, '2024-06-24 14:32:20', '2024-06-24 14:32:20'),
(7, 'Mother Mary', 1, '2024-10-15 16:18:20', '2024-11-24 06:31:00'),
(8, 'Abortion', 1, '2024-12-21 10:39:06', '2024-12-21 10:39:06'),
(9, 'Acceptance', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(10, 'Addiction', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(11, 'Boldness and Conviction', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(12, 'Children', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(13, 'Claims', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(14, 'Construction', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(15, 'Difficulties', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(16, 'Faith', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(17, 'Fear', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(18, 'Forgiveness', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(19, 'Generosity', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(20, 'God\'s Provision', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(21, 'Happiness', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(22, 'Healing', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(23, 'Holiness', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(24, 'Holy Spirit', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(25, 'Honesty', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(26, 'House', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(27, 'Love', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(28, 'Marriage', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(29, 'Peace', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(30, 'Perversion', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(31, 'Pregnancy', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(32, 'Pride and Arrogance', 1, '2024-12-21 10:39:07', '2024-12-21 10:39:07'),
(33, 'Rest in Lord', 1, '2024-12-21 10:39:08', '2024-12-21 10:39:08'),
(34, 'Seperation', 1, '2024-12-21 10:39:08', '2024-12-21 10:39:08'),
(35, 'Spiritual growth', 1, '2024-12-21 10:39:08', '2024-12-21 10:39:08'),
(36, 'Thankfulness', 1, '2024-12-21 10:39:08', '2024-12-21 10:39:08'),
(37, 'Vice', 1, '2024-12-21 10:39:08', '2024-12-21 10:39:08'),
(38, 'Vocation', 1, '2024-12-21 10:39:08', '2024-12-21 10:39:08'),
(39, 'Wealth', 1, '2024-12-21 10:39:08', '2024-12-21 10:39:08'),
(40, 'Wisdom', 1, '2024-12-21 10:39:08', '2024-12-21 10:39:08'),
(41, 'Grief', 1, '2024-12-21 10:39:08', '2024-12-21 10:39:08'),
(42, 'Depression', 1, '2024-12-21 10:39:08', '2024-12-21 10:39:08'),
(43, 'Learning', 1, '2024-12-21 10:39:08', '2024-12-21 10:39:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bible_verse_themes`
--
ALTER TABLE `bible_verse_themes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bible_verse_themes`
--
ALTER TABLE `bible_verse_themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
