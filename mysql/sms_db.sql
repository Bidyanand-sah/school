-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 19, 2026 at 10:16 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) DEFAULT 'Other',
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `achievements`
--

INSERT INTO `achievements` (`id`, `img`, `title`, `description`, `category`, `is_pinned`, `created_at`) VALUES
(1, 'uploads/achievements/achievement_6a8549b1becb19.67846868.JPG', 'code', 'uhdhuiued', 'Academic', 0, '2026-08-19 06:14:09'),
(2, 'uploads/achievements/achievement_6a8549cb2ae094.33289275.JPG', 'dsacdd', 'ewdwaed', 'Sports', 0, '2026-08-19 06:14:35'),
(4, 'uploads/achievements/achievement_6a8553a91299c6.60934775.JPG', 'fgbdfgg', 'rgsrgsrg', 'Academic', 1, '2026-08-19 06:56:02'),
(5, 'uploads/achievements/achievement_6a85539a6e28d4.96083976.PNG', 'fesdrgfr', 'ergrtg', 'Other', 1, '2026-08-19 06:56:26');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$rdFffF9RumMZNFRdWMNuqeBAP9QS3VGnMNBlOGaklcL3brHOJDHg6', '2026-08-17 09:56:28');

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_called` tinyint(1) DEFAULT 0,
  `called_at` datetime DEFAULT NULL,
  `time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enquiry`
--

INSERT INTO `enquiry` (`id`, `name`, `phone`, `email`, `message`, `is_called`, `called_at`, `time`) VALUES
(2, 'bidyanand', '2424488459', '', '', 0, NULL, '2026-08-17 12:16:33'),
(3, 'hello', '5454315642', 'bidyanandk506@gmail.com', 'urgent', 1, '2026-08-18 06:48:02', '2026-08-18 06:47:05');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(100) NOT NULL,
  `img` text NOT NULL,
  `detail` text NOT NULL,
  `Date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `img`, `detail`, `Date`) VALUES
(1, 'uploads/gallery/gallery_6a825fb6b660f8.96657109.png', 'saraswati puja', '2026-08-17 06:41:18'),
(3, 'uploads/gallery/gallery_6a82601a6fa7f1.09338859.png', 'screen shot2', '2026-08-17 06:42:08'),
(4, 'uploads/gallery/gallery_6a82617994bf21.53897206.png', '', '2026-08-17 06:48:49'),
(5, 'uploads/gallery/gallery_6a8261921f6f39.26594743.png', '', '2026-08-17 06:49:14'),
(6, 'uploads/gallery/gallery_6a82619f5e8f86.87259219.png', '', '2026-08-17 06:49:27'),
(8, 'uploads/gallery/gallery_6a8261be028ea0.44678420.png', '', '2026-08-17 06:49:58'),
(10, 'uploads/gallery/gallery_6a8261d4a9ab44.01839159.png', '', '2026-08-17 06:50:20'),
(11, 'uploads/gallery/gallery_6a83b1d701e212.20592010.JPG', 'sdcsf', '2026-08-17 22:47:02'),
(12, 'uploads/gallery/gallery_6a83b1fd99d585.56929434.JPG', 'hi', '2026-08-18 06:44:37'),
(13, 'uploads/gallery/gallery_6a855a8a2bff81.43702995.JPG', 'park', '2026-08-19 12:55:42');

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE `notice` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `pinned` tinyint(1) NOT NULL DEFAULT 0,
  `pdf` text DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notice`
--

INSERT INTO `notice` (`id`, `title`, `content`, `category`, `pinned`, `pdf`, `date`) VALUES
(1, 'nothing', 'every one come now jaldi', 'Urgent', 1, 'uploads/notice/notice_6a826bd784afd7.44519812.pdf', '2026-08-17 07:33:03'),
(3, 'enjoy', 'exam lunga', 'General', 1, '', '2026-08-17 07:35:10'),
(4, 'hello', 'dsdasd', 'Holiday', 0, 'uploads/notice/notice_6a83b3e3d0b7c7.17669394.pdf', '2026-08-18 06:52:43');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `id` int(100) NOT NULL,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `subject` text NOT NULL,
  `bio` text NOT NULL,
  `img` text NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`id`, `name`, `type`, `subject`, `bio`, `img`, `time`) VALUES
(4, 'mahesh', 'Director', '', 'good', 'uploads/teachers/teacher_6a817ad4e72cc5.77270575.png', '2026-08-16 11:37:09'),
(6, 'sdcsdccdssd', 'Vice Principal', '', 'sdcsf', 'uploads/teachers/teacher_6a8153d3b0edf6.76981475.png', '2026-08-16 11:38:19'),
(7, 'rohit', 'Principal', '', 'Good habit', 'uploads/teachers/teacher_6a8153feec90e4.14611105.png', '2026-08-16 11:39:02'),
(9, 'ronit', 'Teacher', 'math', 'Good behaviour', 'uploads/teachers/teacher_6a83b192b9e213.43547534.JPG', '2026-08-16 11:40:36'),
(10, 'Hero', 'Teacher', 'Science', 'Good behaviour', 'uploads/teachers/teacher_6a83b1351f8272.85413744.JPG', '2026-08-18 06:41:17');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `class` varchar(50) DEFAULT 'Parent',
  `review_text` text NOT NULL,
  `rating` int(11) DEFAULT 5,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `class`, `review_text`, `rating`, `created_at`) VALUES
(1, 'bidyanand', '2', 'ddewedew', 4, '2026-08-18 06:11:42'),
(2, 'bidyanand', '3', 'weferf', 5, '2026-08-18 06:13:56'),
(3, 'bidyanand', '2', 'ddewedew', 4, '2026-08-18 06:14:09'),
(4, 'bidyanand', '2', 'ddewedew', 4, '2026-08-18 06:14:14'),
(6, 'pratha', 'wedwed', 'wdwedwe', 2, '2026-08-18 07:02:13'),
(7, 'rohan', 'class 4', 'hbiwecdwe', 4, '2026-08-18 07:03:40'),
(8, 'shivam', 'Parent', 'd', 4, '2026-08-18 11:03:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `enquiry`
--
ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notice`
--
ALTER TABLE `notice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `enquiry`
--
ALTER TABLE `enquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notice`
--
ALTER TABLE `notice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
