-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2024 at 12:23 PM
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
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE `note` (
  `note_id` int(11) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `note`
--

INSERT INTO `note` (`note_id`, `description`, `date`, `title`, `user_id`, `user`) VALUES
(10, 'zengy', '2024-12-09 19:15:14', 'popo', 0, 'Tawy'),
(11, 'A7A', '2024-12-09 19:16:27', 'helooooo', 0, 'Smolx'),
(12, 'ggggg', '2024-12-11 18:54:20', 'asdasdf', 0, 'Tawy'),
(13, 'jhkfg', '2024-12-18 18:27:19', 'yyyyyyy', 0, 'Tawy'),
(14, 'uiopl', '2024-12-18 18:29:28', 'lololol', 0, 'Tawy'),
(15, 'qwert', '2024-12-20 13:10:58', 'ashiii', 0, 'Tawy'),
(16, 'kokokoko', '2024-12-20 21:54:20', 'pppppoooosss', 0, 'Tawy');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `description` text NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `pass`, `created_at`, `description`, `role`) VALUES
(19, 'Tawy', 'zeyadtantawy@gmail.com', 'Sheep#123', '2024-11-28 19:37:42', '', 'ADMIN'),
(22, 'Smolx', 'mohamedmagdy@gmail.com', 'Smolx#7890', '2024-11-28 20:38:43', '', 'USER'),
(29, 'D_-VIL', 'kareemahmed@gmail.com', 'sHEEP#456', '2024-12-20 22:20:42', '', 'USER'),
(38, 'lol', 'zeyadashraf34@gmail.com', 'Sheep#890', '2024-12-20 22:32:39', '', 'USER');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `fk_note_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `note`
--
ALTER TABLE `note`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
