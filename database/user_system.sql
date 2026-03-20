-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2026 at 04:12 PM
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
-- Database: `user_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `language` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `book_condition` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `front_img` varchar(255) DEFAULT NULL,
  `back_img` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `exchanged` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `language`, `type`, `book_condition`, `description`, `front_img`, `back_img`, `created_at`, `user_id`, `exchanged`) VALUES
(12, 'Mount', 'English', 'magazine', 'used', 'it\'s a new magazine showing the beauty of the nature.', 'uploads/1764583908_front_img1.jpg', 'uploads/1764583908_back_img4.jpg', '2025-12-01 10:11:48', 4, 1),
(13, 'Mount', 'English', 'story', 'damaged', 'it\'s a very good book to read.', 'uploads/1764832557_front_img5.jpg', 'uploads/1764832557_back_img1.jpg', '2025-12-04 07:15:57', 3, 1),
(15, 'Rich Dad Poor Dad', 'English', 'novel', 'new', 'learn something new.', 'uploads/1765380925_front_front photo.jpg', 'uploads/1765380925_back_back photo.jpg', '2025-12-10 15:35:25', 4, 1),
(16, 'last one', 'English', 'story', 'new', 'nice book.', 'uploads/1765381031_front_img2.jpg', 'uploads/1765381031_back_img4.jpg', '2025-12-10 15:37:11', 3, 1),
(17, 'Rich Dad Poor Dad', 'English', 'story', 'new', 'About Business', 'uploads/1765680321_front_front photo.jpg', 'uploads/1765680321_back_back photo.jpg', '2025-12-14 02:45:21', 3, 1),
(18, 'Book icms', 'English', 'story', 'used', 'good book', 'uploads/1765686871_front_img1.jpg', 'uploads/1765686871_back_img2.jpg', '2025-12-14 04:34:31', 6, 1),
(19, 'stars', 'English', 'textbook', 'used', 'just stars', 'uploads/1765727269_front_digitalclock.jpg', 'uploads/1765727269_back_digitalclock.jpg', '2025-12-14 15:47:49', 6, 1),
(20, 'Rise', 'English', 'story', 'used', 'it\'s good book', 'uploads/1765943049_front_img1.jpg', 'uploads/1765943049_back_img3.jpg', '2025-12-17 03:44:09', 6, 1),
(21, 'View', 'English', 'novel', 'used', '...', 'uploads/1765943143_front_img5.jpg', 'uploads/1765943143_back_img1.jpg', '2025-12-17 03:45:43', 4, 1),
(22, 'Nature', 'English', 'story', 'new', 'good book', 'uploads/1766040954_front_img3.jpg', 'uploads/1766040954_back_img4.jpg', '2025-12-18 06:55:54', 4, 1),
(23, 'Design Template', 'English', 'magazine', 'new', 'learn new..', 'uploads/1766324631_front_front(1).jpg', 'uploads/1766324631_back_back(1).jpg', '2025-12-21 13:43:51', 3, 1),
(24, 'Headline', 'English', 'novel', 'new', 'learn something..', 'uploads/1766331831_front_front(2).jpg', 'uploads/1766331831_back_back(2).jpg', '2025-12-21 15:43:51', 6, 1),
(26, 'one', 'English', 'magazine', 'new', '..', 'homepage/uploads/1766677634_front_img3.jpg', 'homepage/uploads/1766677634_back_img4.jpg', '2025-12-25 15:47:14', 2, 1),
(28, 'Design', 'English', 'magazine', 'new', '....', 'uploads/1770519673_front_front(2).jpg', 'uploads/1770519673_back_back(2).jpg', '2026-02-08 03:01:13', 9, 1),
(29, 'design', 'English', 'novel', 'new', '.....', 'uploads/1770519948_front_back(2).jpg', 'uploads/1770519948_back_Dinning table.exe', '2026-02-08 03:05:48', 3, 1),
(30, 'boook', 'English', 'novel', 'new', '...', 'uploads/1770520233_front_back(1).jpg', 'uploads/1770520234_back_front(1).jpg', '2026-02-08 03:10:34', 3, 1),
(31, 'design template', 'English', 'story', 'new', 'it\'s a new book', 'uploads/1770557723_front_front(1).jpg', 'uploads/1770557723_back_back(1).jpg', '2026-02-08 13:35:23', 2, 1),
(32, 'nooo', 'English', 'novel', 'new', '....', 'uploads/1770557909_front_front(1).jpg', 'uploads/1770557909_back_back(1).jpg', '2026-02-08 13:38:29', 4, 1),
(33, 'book', 'English', 'story', 'used', 'nothing...', 'uploads/1770557984_front_back(2).jpg', 'uploads/1770557984_back_back(2).jpg', '2026-02-08 13:39:44', 2, 1),
(34, 'template', 'English', 'novel', 'new', 'good book', 'uploads/1770565257_6988ae89c7154.jpg', 'uploads/1770565257_6988ae89c75fe.jpg', '2026-02-08 15:40:57', 2, 1),
(35, 'try title', 'English', 'story', 'new', 'learn new things.', 'uploads/1770647899_6989f15bba8d9.jpg', 'uploads/1770647899_6989f15bbabf3.jpg', '2026-02-09 14:38:19', 3, 1),
(36, 'the last one', 'English', 'novel', 'new', 'good book', 'uploads/1770653346_698a06a2df227.jpg', 'uploads/1770653346_698a06a2df3aa.jpg', '2026-02-09 16:09:06', 4, 0),
(37, 'new one', 'English', 'novel', 'new', '...', 'uploads/1770653383_698a06c7011fa.jpg', 'uploads/1770653383_698a06c70177b.jpg', '2026-02-09 16:09:43', 6, 1),
(38, 'finally', 'English', 'textbook', 'new', 'working now!', 'uploads/1770656850_698a145202a68.jpg', 'uploads/1770656850_698a145202c1b.jpg', '2026-02-09 17:07:30', 3, 1),
(39, 'nebula', 'English', 'magazine', 'used', 'fully working!', 'uploads/1770656902_698a1486ee06f.jpg', 'uploads/1770656902_698a1486ee2dd.jpg', '2026-02-09 17:08:22', 4, 1),
(40, 'Harry Potter 1', 'English', 'story', 'new', 'it is the harry potter 1 written by J.K Rowling.\r\nThe first volume of harry potter.', 'uploads/1770819352_698c8f18cc891.jpg', 'uploads/1770819352_698c8f18ccc98.jpg', '2026-02-11 14:15:52', 6, 1),
(41, 'Harry Potter ', 'English', 'story', 'new', 'Harry potter 2 \"Philosopher\'s stone\".\r\nwritten by J.K Rowling.', 'uploads/1770819536_698c8fd0f3536.jpg', 'uploads/1770819536_698c8fd0f383c.jpg', '2026-02-11 14:18:56', 3, 1),
(42, 'Lord Of The Rings', 'English', 'story', 'used', '\"Lord of the rings\" it\'s an amazing book to read know more about fantasy with crazy story.', 'uploads/1770820164_698c9244c1970.jpg', 'uploads/1770820164_698c9244c1d53.jpg', '2026-02-11 14:29:24', 2, 1),
(43, ' All The Young Dudes', 'English', 'magazine', 'old', 'it\'s a great magazine to timepass. The final part \"The End\".', 'uploads/1770820485_698c938522315.png', 'uploads/1770820485_698c938522705.png', '2026-02-11 14:34:45', 6, 0),
(44, 'The 48 Law of Power', 'English', 'textbook', 'used', 'non-fiction self-help and strategy book.', 'uploads/1773325288_69b2cbe82e0da.jpg', 'uploads/1773325288_69b2cbe82e344.jpg', '2026-03-12 14:21:28', 10, 1),
(45, 'Harry Potter 3', 'English', 'story', 'new', 'it\'s a good book', 'uploads/1773653131_69b7cc8b683a7.jpg', 'uploads/1773653131_69b7cc8b688ee.jpg', '2026-03-16 09:25:31', 3, 1),
(46, 'Rich Dad Poor Dad', 'English', 'magazine', 'new', 'good book', 'uploads/1773653324_69b7cd4c06e12.jpg', 'uploads/1773653324_69b7cd4c071a8.jpg', '2026-03-16 09:28:44', 6, 0),
(47, 'The End', 'English', 'novel', 'new', 'best book for new readers', 'uploads/1773653427_69b7cdb33122b.png', 'uploads/1773653427_69b7cdb33164f.png', '2026-03-16 09:30:27', 6, 1),
(49, 'Game Of Thron', 'English', 'story', 'new', 'best book to read ', 'uploads/1773653737_69b7cee94a902.jpg', 'uploads/1773653737_69b7cee94abb9.jpg', '2026-03-16 09:35:37', 2, 1),
(50, 'Power', 'English', 'magazine', 'new', '', 'uploads/1773718806_69b8cd16adb97.jpg', 'uploads/1773718806_69b8cd16adec1.jpg', '2026-03-17 03:40:06', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `exchange_requests`
--

CREATE TABLE `exchange_requests` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `sender_book_id` int(10) UNSIGNED NOT NULL,
  `receiver_book_id` int(10) UNSIGNED NOT NULL,
  `sender_phone` varchar(10) NOT NULL,
  `receiver_phone` varchar(10) DEFAULT NULL,
  `status` enum('pending','accepted','rejected','completed') DEFAULT 'pending',
  `expires_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exchange_requests`
--

INSERT INTO `exchange_requests` (`id`, `sender_id`, `receiver_id`, `sender_book_id`, `receiver_book_id`, `sender_phone`, `receiver_phone`, `status`, `expires_at`, `created_at`, `updated_at`, `completed_at`) VALUES
(2, 3, 6, 17, 18, '1234567876', '1234567654', 'accepted', NULL, '2025-12-14 15:18:33', '2025-12-14 15:35:37', NULL),
(3, 6, 3, 18, 16, '0000000000', '1111111111', 'completed', NULL, '2025-12-14 15:43:21', NULL, '2025-12-14 15:44:40'),
(4, 6, 3, 19, 17, '1232321231', NULL, 'rejected', NULL, '2025-12-14 15:48:09', '2025-12-14 15:48:47', NULL),
(5, 3, 6, 17, 19, '1234567878', '2345678765', 'completed', NULL, '2025-12-17 03:41:23', NULL, '2025-12-17 03:42:38'),
(6, 6, 4, 20, 21, '1234567876', '1111111111', 'completed', NULL, '2025-12-18 06:54:38', NULL, '2025-12-18 06:55:14'),
(7, 3, 4, 23, 22, '1234567876', NULL, 'rejected', NULL, '2025-12-21 14:26:27', '2025-12-21 14:27:19', NULL),
(8, 3, 2, 23, 26, '1234567876', NULL, 'rejected', NULL, '2026-02-08 02:54:32', '2026-02-08 02:55:00', NULL),
(9, 9, 3, 28, 23, '1234567876', '1111111111', 'completed', NULL, '2026-02-08 03:01:37', NULL, '2026-02-08 03:02:23'),
(10, 3, 2, 29, 26, '1234567876', '2345678765', 'completed', NULL, '2026-02-08 03:07:13', NULL, '2026-02-08 03:08:23'),
(11, 3, 6, 30, 24, '1234567876', NULL, '', NULL, '2026-02-08 03:11:01', NULL, NULL),
(12, 3, 4, 30, 22, '1234567876', NULL, 'rejected', NULL, '2026-02-08 03:11:23', '2026-02-08 13:40:34', NULL),
(13, 3, 6, 30, 24, '1234567876', '1234567654', 'completed', NULL, '2026-02-08 03:11:40', NULL, '2026-02-08 03:13:36'),
(14, 3, 6, 30, 24, '1234567876', '1111111111', 'completed', NULL, '2026-02-08 03:12:05', NULL, '2026-02-08 03:13:03'),
(15, 2, 4, 31, 22, '1234567876', '1111111111', 'completed', NULL, '2026-02-08 13:35:33', NULL, '2026-02-08 13:37:01'),
(16, 2, 4, 33, 32, '1234567878', NULL, 'rejected', NULL, '2026-02-08 13:39:57', '2026-02-08 13:40:50', NULL),
(17, 4, 2, 32, 33, '0000000000', NULL, 'rejected', NULL, '2026-02-08 15:53:54', '2026-02-08 15:54:42', NULL),
(18, 2, 4, 34, 32, '0000000000', '9999999999', 'completed', NULL, '2026-02-08 16:03:50', NULL, '2026-02-08 16:04:30'),
(19, 3, 2, 35, 33, '2222222222', NULL, '', NULL, '2026-02-09 14:38:51', NULL, NULL),
(20, 4, 6, 36, 37, '0000000000', NULL, 'rejected', NULL, '2026-02-09 16:12:33', '2026-02-09 16:34:42', NULL),
(21, 4, 6, 36, 37, '0000000000', NULL, 'rejected', NULL, '2026-02-09 16:35:16', '2026-02-09 16:51:00', NULL),
(22, 6, 4, 37, 36, '0000000000', NULL, 'rejected', NULL, '2026-02-09 16:51:12', '2026-02-09 16:52:15', NULL),
(23, 6, 4, 37, 36, '0000000000', NULL, 'rejected', NULL, '2026-02-09 16:55:10', '2026-02-10 15:40:37', NULL),
(24, 2, 3, 33, 35, '1234567878', '9999999999', 'completed', NULL, '2026-02-09 17:06:06', NULL, '2026-02-09 17:06:42'),
(25, 3, 4, 38, 39, '0000000000', NULL, 'rejected', NULL, '2026-02-09 17:14:26', '2026-02-09 17:14:43', NULL),
(26, 4, 6, 39, 37, '0000000000', '1111111111', 'completed', NULL, '2026-02-10 15:41:12', NULL, '2026-02-10 15:43:46'),
(27, 3, 6, 41, 43, '1000000000', NULL, 'rejected', NULL, '2026-02-21 11:13:16', '2026-02-21 11:13:46', NULL),
(28, 3, 10, 38, 44, '0123456123', NULL, 'rejected', NULL, '2026-03-13 03:30:53', '2026-03-13 03:35:42', NULL),
(29, 6, 10, 40, 44, '1247312812', '1243134562', 'completed', NULL, '2026-03-13 03:38:19', NULL, '2026-03-13 03:42:05'),
(30, 3, 10, 38, 44, '1120830121', '2345678908', 'completed', NULL, '2026-03-13 03:38:25', NULL, '2026-03-13 03:41:16'),
(31, 3, 6, 41, 43, '0000000000', NULL, 'rejected', NULL, '2026-03-15 17:11:17', '2026-03-15 17:12:51', NULL),
(32, 3, 2, 45, 49, '2673763572', NULL, 'rejected', NULL, '2026-03-17 01:53:28', '2026-03-17 01:54:01', NULL),
(33, 2, 3, 42, 45, '0000000000', NULL, 'rejected', NULL, '2026-03-17 01:54:50', '2026-03-17 01:55:46', NULL),
(34, 3, 2, 41, 49, '1234567876', '1111111111', 'completed', NULL, '2026-03-17 03:28:35', NULL, '2026-03-17 03:29:13'),
(35, 2, 3, 42, 45, '1234567876', NULL, 'pending', NULL, '2026-03-17 03:34:28', NULL, NULL),
(36, 2, 6, 50, 47, '1234567876', NULL, 'pending', NULL, '2026-03-17 03:40:42', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `last_seen_receive` datetime DEFAULT NULL,
  `last_seen_send` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `firstname`, `lastname`, `email`, `password`, `last_seen_receive`, `last_seen_send`) VALUES
(1, 'riya', 'thapa', 'riya1@gmail.com', '$2y$10$HZh/wmMDpADXcfN6u2V.EOCMUdh.pQ.AKhdgtSgNmx8v2CnLNOI7W', NULL, NULL),
(2, 'riya', 'rai', 'riya2@gmail.com', '$2y$10$FNCaDWwelOg/aRFLPdwxKeCEiyMvh.WPfxLZO8FmHjMPtzgaLdYZW', '2026-03-17 09:14:02', '2026-03-17 09:21:31'),
(3, 'Anu', 'D', 'anu22@gmail.com', '$2y$10$H.HTKHQ8AUPeGdy/oH3BKONNgLAM3Uxz9myxUJwsX8N3YJ7LDJAYi', '2026-03-17 09:21:42', '2026-03-17 09:13:45'),
(4, 'John', 'Don', 'john123@gmail.com', '$2y$10$rNDDkf9SclqfgUpEZuF7JOY3qMm6kUU0uwDUwrOgiDiZ3vGdW4Kye', '2026-02-10 21:25:37', '2026-02-11 20:14:56'),
(5, 'Rohan', 'Thapa', 'rohanthapa123@gmail.com', '$2y$10$GVjeEBu0SEx5z3tMceSW9O3O0WVErop235P5G6kaG79xV8fxpl4Xi', NULL, NULL),
(6, 'Bimichan', 'Thapa', 'bimichanthapa123@gmail.com', '$2y$10$GNA8vSAZGLXy73aRA6/vRejVYh59nmaBvy2JHRabOZF0SI7m3VmBO', '2026-03-15 22:57:51', '2026-03-13 09:23:56'),
(7, 'Bob', 'Builder', 'bob1@gmail.com', '$2y$10$O2PMWZCnVOakV.MxwNIPFuZVSudipOUGStd01KiAQasMvkEMeDM8y', NULL, NULL),
(8, 'Jhon', 'Don', 'jhon1@gmail.com', '$2y$10$S6N2dTpalWlZwuEhvLbAKO8hgLNJl.T57lUA4sC1hQoUtKO9/o4A6', NULL, NULL),
(9, 'biraj', 'khatri', 'biraj123@gmail.com', '$2y$10$6vnOEWufzXR8NMtOjIy1y.nXXF3cur7tx7y.XUYyma2lHKfgHjRGW', NULL, NULL),
(10, 'Subu', 'Tamang', 'subutmg12@gmail.com', '$2y$10$vQi/N6EVU.f/eRFYeA8IQu54HT7TPoogLgOsukQanEZdrMPiIRW0m', '2026-03-13 09:26:27', NULL),
(11, 'Bob', 'D', 'test123@gmail.com', '$2y$10$TC4jhBg8lXE1uZoAvXRiYOLiv6/93lvUHfNvWANE20l9evotG5y/m', NULL, '2026-03-13 19:09:15'),
(12, 'Biraj', 'Khatri', 'biraj12@gmail.com', '$2y$10$81TKlt2Fk5tzLLmEpxwey.GPaOH9lEIkynXgAcAGyUbml5vqaAEX.', NULL, NULL),
(13, 'Biraj', 'Khatri', 'biraj21@gmail.com', '$2y$10$n/cR7AEwoPnNNEWvRqc1leWObz3nr/Tebm8i5VRmDkKLjdLHeYAy2', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `sender_book_id` (`sender_book_id`),
  ADD KEY `receiver_book_id` (`receiver_book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  ADD CONSTRAINT `fk_receiver` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_receiver_book` FOREIGN KEY (`receiver_book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_sender_book` FOREIGN KEY (`sender_book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
