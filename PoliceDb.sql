-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 10, 2025 at 05:24 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `PoliceDb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(2550) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$xDCy4VcwGedLDI8WfORlq.rMhm78gH9gSWruiqKoNJfEHv/HpEON6', '2025-04-22 02:54:19');

-- --------------------------------------------------------

--
-- Table structure for table `assigned_duties`
--

CREATE TABLE `assigned_duties` (
  `id` int(11) NOT NULL,
  `officer_id` int(11) NOT NULL,
  `duty_id` int(11) NOT NULL,
  `duty_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigned_duties`
--

INSERT INTO `assigned_duties` (`id`, `officer_id`, `duty_id`, `duty_date`, `created_at`) VALUES
(819, 2, 4, '2025-05-08', '2025-05-08 09:12:57'),
(820, 3, 2, '2025-05-08', '2025-05-08 09:12:57'),
(821, 2, 3, '2025-05-09', '2025-05-08 09:12:57'),
(822, 3, 5, '2025-05-09', '2025-05-08 09:12:57'),
(823, 2, 5, '2025-05-10', '2025-05-08 09:12:57'),
(824, 3, 5, '2025-05-10', '2025-05-08 09:12:57'),
(825, 2, 1, '2025-05-11', '2025-05-08 09:12:57'),
(826, 3, 2, '2025-05-11', '2025-05-08 09:12:57'),
(827, 2, 3, '2025-05-12', '2025-05-08 09:12:57'),
(828, 3, 5, '2025-05-12', '2025-05-08 09:12:57'),
(829, 2, 4, '2025-05-13', '2025-05-08 09:12:57'),
(830, 3, 1, '2025-05-13', '2025-05-08 09:12:57'),
(831, 3, 3, '2025-05-14', '2025-05-08 09:12:57'),
(832, 2, 4, '2025-05-14', '2025-05-08 09:12:57'),
(833, 3, 5, '2025-05-15', '2025-05-08 09:12:57'),
(834, 2, 5, '2025-05-15', '2025-05-08 09:12:57'),
(835, 3, 3, '2025-05-16', '2025-05-08 09:12:57'),
(837, 2, 5, '2025-05-16', '2025-05-08 09:12:57'),
(838, 3, 2, '2025-05-17', '2025-05-08 09:12:57'),
(840, 2, 3, '2025-05-17', '2025-05-08 09:12:57'),
(841, 3, 2, '2025-05-18', '2025-05-08 09:12:57'),
(842, 2, 3, '2025-05-18', '2025-05-08 09:12:57'),
(844, 2, 4, '2025-05-19', '2025-05-08 09:12:57'),
(845, 3, 3, '2025-05-19', '2025-05-08 09:12:57'),
(847, 3, 1, '2025-05-20', '2025-05-08 09:12:57'),
(849, 2, 5, '2025-05-20', '2025-05-08 09:12:57'),
(851, 2, 5, '2025-05-21', '2025-05-08 09:12:57'),
(852, 3, 5, '2025-05-21', '2025-05-08 09:12:57'),
(854, 3, 4, '2025-05-22', '2025-05-08 09:12:57'),
(855, 2, 3, '2025-05-22', '2025-05-08 09:12:57'),
(856, 3, 4, '2025-05-23', '2025-05-08 09:12:57'),
(857, 2, 1, '2025-05-23', '2025-05-08 09:12:57'),
(858, 1, 3, '2025-05-23', '2025-05-08 09:12:57'),
(859, 3, 3, '2025-05-24', '2025-05-08 09:12:57'),
(860, 2, 5, '2025-05-24', '2025-05-08 09:12:57'),
(861, 1, 5, '2025-05-24', '2025-05-08 09:12:57'),
(862, 3, 2, '2025-05-25', '2025-05-08 09:12:57'),
(863, 1, 5, '2025-05-25', '2025-05-08 09:12:57'),
(864, 2, 5, '2025-05-25', '2025-05-08 09:12:57'),
(865, 3, 3, '2025-05-26', '2025-05-08 09:12:57'),
(866, 1, 5, '2025-05-26', '2025-05-08 09:12:57'),
(867, 2, 5, '2025-05-26', '2025-05-08 09:12:57'),
(868, 1, 3, '2025-05-27', '2025-05-08 09:12:57'),
(869, 2, 4, '2025-05-27', '2025-05-08 09:12:57'),
(870, 3, 1, '2025-05-27', '2025-05-08 09:12:57'),
(871, 3, 1, '2025-05-28', '2025-05-08 09:12:57'),
(872, 2, 3, '2025-05-28', '2025-05-08 09:12:57'),
(873, 1, 4, '2025-05-28', '2025-05-08 09:12:57'),
(874, 2, 5, '2025-05-29', '2025-05-08 09:12:57'),
(875, 1, 5, '2025-05-29', '2025-05-08 09:12:57'),
(876, 3, 5, '2025-05-29', '2025-05-08 09:12:57'),
(877, 2, 1, '2025-05-30', '2025-05-08 09:12:57'),
(878, 1, 2, '2025-05-30', '2025-05-08 09:12:57'),
(879, 3, 5, '2025-05-30', '2025-05-08 09:12:57'),
(880, 1, 4, '2025-05-31', '2025-05-08 09:12:57'),
(881, 3, 2, '2025-05-31', '2025-05-08 09:12:57'),
(882, 2, 5, '2025-05-31', '2025-05-08 09:12:57'),
(883, 3, 1, '2025-06-01', '2025-05-08 09:12:57'),
(884, 2, 3, '2025-06-01', '2025-05-08 09:12:57'),
(885, 1, 2, '2025-06-01', '2025-05-08 09:12:57'),
(886, 3, 4, '2025-06-02', '2025-05-08 09:12:57'),
(887, 1, 3, '2025-06-02', '2025-05-08 09:12:57'),
(888, 2, 5, '2025-06-02', '2025-05-08 09:12:57'),
(889, 1, 1, '2025-06-03', '2025-05-08 09:12:57'),
(890, 2, 3, '2025-06-03', '2025-05-08 09:12:57'),
(891, 3, 4, '2025-06-03', '2025-05-08 09:12:57'),
(892, 2, 3, '2025-06-04', '2025-05-08 09:12:57'),
(893, 1, 4, '2025-06-04', '2025-05-08 09:12:57'),
(894, 3, 5, '2025-06-04', '2025-05-08 09:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `id` int(11) NOT NULL,
  `case_title` varchar(255) NOT NULL,
  `case_description` text DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`id`, `case_title`, `case_description`, `assigned_to`, `status`, `created_at`) VALUES
(1, 'Murder Case', 'Kottayam', 3, 'In Progress', '2025-04-22 07:41:44'),
(2, 'ibh ijbij b', 'oi jio ij nij jk b ', NULL, 'Pending', '2025-04-22 07:47:13');

-- --------------------------------------------------------

--
-- Table structure for table `case_officers`
--

CREATE TABLE `case_officers` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `officer_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `case_officers`
--

INSERT INTO `case_officers` (`id`, `case_id`, `officer_id`, `assigned_at`) VALUES
(6, 2, 2, '2025-04-22 07:47:19'),
(7, 1, 1, '2025-05-08 08:44:15'),
(8, 1, 2, '2025-05-08 08:44:15'),
(9, 1, 3, '2025-05-08 08:44:15');

-- --------------------------------------------------------

--
-- Table structure for table `Duties`
--

CREATE TABLE `Duties` (
  `Id` int(11) NOT NULL,
  `DutyType` varchar(1200) DEFAULT NULL,
  `IsFixed` int(11) NOT NULL,
  `Count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Duties`
--

INSERT INTO `Duties` (`Id`, `DutyType`, `IsFixed`, `Count`) VALUES
(1, 'General Diary', 0, 1),
(2, 'Station Writer', 0, 1),
(3, 'Assistant Station Writer', 0, 1),
(4, 'Process', 0, 1),
(5, 'Law And Order', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `duty_schedule`
--

CREATE TABLE `duty_schedule` (
  `id` int(11) NOT NULL,
  `officer_id` int(11) NOT NULL,
  `duty_date` date NOT NULL,
  `duty_type_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `duty_types`
--

CREATE TABLE `duty_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `required_count` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `duty_types`
--

INSERT INTO `duty_types` (`id`, `name`, `required_count`) VALUES
(1, 'General Diary', 1),
(2, 'Station Writer', 1),
(3, 'Station Centry', 2),
(4, 'Law And Order', 5),
(5, 'Investigation', 5),
(6, 'Janamythri', 1);

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `officer_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `officer_id`, `start_date`, `end_date`, `reason`, `status`, `created_at`) VALUES
(1, 1, '2025-05-16', '2025-05-22', 'fever', 'approved', '2025-04-22 05:21:43'),
(2, 3, '2025-04-23', '2025-04-23', 'medical', 'approved', '2025-04-22 06:25:14');

-- --------------------------------------------------------

--
-- Table structure for table `police_officers`
--

CREATE TABLE `police_officers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gl_number` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rank` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `police_officers`
--

INSERT INTO `police_officers` (`id`, `name`, `gl_number`, `phone`, `email`, `password`, `rank`, `created_at`) VALUES
(1, 'ANISH S NAIR', '76476', '09496873618', 'anish@gmail.com', '$2y$10$35J6hkkP9HUE1ZnaC4MHLub9SsA4rLtYFFwUHR7WmL8xUXqZz4Ro2', 'CPO', '2025-04-22 03:42:14'),
(2, 'ANISH SASIDHARAN NAIR', '67578', '09496873618', 'anish1@gmail.com', '$2y$10$kit4q639P9UE0OyD3T7c1uGblPlA20gRUSV0Nwwwo8/EkcpfXLidq', 'CPO', '2025-04-22 03:42:48'),
(3, 'Rakesh', '764761', '9526674440', 'rakesh@gmail.com', '$2y$10$w.SVHlMeurtFwjWCl5npYemXo4lA62hZAyL/nlgahsa0moPqORvxm', 'CPO', '2025-04-22 06:09:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `assigned_duties`
--
ALTER TABLE `assigned_duties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_duty_assignment` (`officer_id`,`duty_date`),
  ADD KEY `duty_id` (`duty_id`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `case_officers`
--
ALTER TABLE `case_officers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `case_id` (`case_id`),
  ADD KEY `officer_id` (`officer_id`);

--
-- Indexes for table `Duties`
--
ALTER TABLE `Duties`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `duty_schedule`
--
ALTER TABLE `duty_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_id` (`officer_id`),
  ADD KEY `duty_type_id` (`duty_type_id`);

--
-- Indexes for table `duty_types`
--
ALTER TABLE `duty_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `officer_id` (`officer_id`);

--
-- Indexes for table `police_officers`
--
ALTER TABLE `police_officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gl_number` (`gl_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `assigned_duties`
--
ALTER TABLE `assigned_duties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=895;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `case_officers`
--
ALTER TABLE `case_officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Duties`
--
ALTER TABLE `Duties`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `duty_schedule`
--
ALTER TABLE `duty_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `duty_types`
--
ALTER TABLE `duty_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `police_officers`
--
ALTER TABLE `police_officers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assigned_duties`
--
ALTER TABLE `assigned_duties`
  ADD CONSTRAINT `assigned_duties_ibfk_1` FOREIGN KEY (`officer_id`) REFERENCES `police_officers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assigned_duties_ibfk_2` FOREIGN KEY (`duty_id`) REFERENCES `Duties` (`Id`) ON DELETE CASCADE;

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `police_officers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `case_officers`
--
ALTER TABLE `case_officers`
  ADD CONSTRAINT `case_officers_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`),
  ADD CONSTRAINT `case_officers_ibfk_2` FOREIGN KEY (`officer_id`) REFERENCES `police_officers` (`id`);

--
-- Constraints for table `duty_schedule`
--
ALTER TABLE `duty_schedule`
  ADD CONSTRAINT `duty_schedule_ibfk_1` FOREIGN KEY (`officer_id`) REFERENCES `police_officers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `duty_schedule_ibfk_2` FOREIGN KEY (`duty_type_id`) REFERENCES `duty_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`officer_id`) REFERENCES `police_officers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
