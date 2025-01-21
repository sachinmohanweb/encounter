-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 21, 2025 at 03:15 PM
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
-- Table structure for table `g_q_categories`
--

CREATE TABLE `g_q_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `g_q_categories`
--

INSERT INTO `g_q_categories` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Gospel of Matthew', 1, '2024-06-15 13:27:34', '2024-12-19 05:33:27'),
(2, 'Gospel of Mark', 1, '2024-06-15 13:27:34', '2024-12-18 16:54:36'),
(4, 'Gospel of Luke', 1, '2024-09-14 09:05:10', '2024-12-18 16:54:45'),
(5, 'Gospel of John', 1, '2024-09-14 09:13:56', '2024-12-18 16:57:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `g_q_categories`
--
ALTER TABLE `g_q_categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `g_q_categories`
--
ALTER TABLE `g_q_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
