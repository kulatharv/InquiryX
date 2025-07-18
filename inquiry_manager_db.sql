-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 18, 2025 at 10:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inquiry_manager_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `contact` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `company`, `address`, `created_at`, `contact`) VALUES
(1, 'asda', 'dad2@dd', 'dsa', 'sdds', NULL, '2025-06-17 18:49:22', NULL),
(2, 'asda', 'dad2@dd', NULL, NULL, '', '2025-06-17 18:56:04', ''),
(3, 'rrk', 'rrk@gmail.com', NULL, NULL, 'paithan', '2025-06-18 02:50:21', '8732152484'),
(4, 'sd', 'fds@gmail.com', NULL, NULL, 'fsds', '2025-07-18 05:40:03', 'ewe33');

-- --------------------------------------------------------

--
-- Table structure for table `followups`
--

CREATE TABLE `followups` (
  `id` int(11) NOT NULL,
  `followup_date` date NOT NULL,
  `contact_mode` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `followups`
--

INSERT INTO `followups` (`id`, `followup_date`, `contact_mode`, `description`, `status`, `remarks`, `created_at`, `customer_id`) VALUES
(3, '2025-06-18', 'Call', 'dsd', 'pending', NULL, '2025-06-17 19:45:01', 1),
(4, '2025-06-18', 'Call', 'dsd', 'pending', NULL, '2025-06-17 19:47:45', 1),
(5, '2025-06-18', 'Visit', 'dwd', 'pending', NULL, '2025-06-18 02:51:47', 3),
(6, '2025-06-18', 'Visit', 'dwd', 'pending', NULL, '2025-06-18 02:52:01', 3),
(7, '2025-06-19', 'Visit', 'dwd', 'pending', NULL, '2025-06-18 02:52:20', 3),
(8, '2025-06-28', 'Visit', 'dwd', 'pending', NULL, '2025-06-18 03:53:13', 3),
(9, '2025-06-28', 'Visit', 'dwd', 'pending', NULL, '2025-06-18 03:53:18', 3),
(10, '2025-07-18', 'Call', 'dsd', 'pending', NULL, '2025-07-18 05:39:15', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `inquiry_text` text NOT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `subject` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `product` varchar(100) DEFAULT NULL,
  `inquiry_date` date DEFAULT NULL,
  `next_followup` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `customer_id`, `inquiry_text`, `status`, `created_at`, `subject`, `description`, `product`, `inquiry_date`, `next_followup`) VALUES
(1, 1, '', 'Pending', '2025-06-17 19:17:04', NULL, 'dsds', 'dsd\\', '2025-06-17', '0000-00-00'),
(2, 1, '', 'Pending', '2025-06-17 19:27:36', NULL, 'sa', 'sas', '2025-06-17', '2025-06-18'),
(3, 1, '', 'Pending', '2025-06-17 19:27:44', NULL, 'sa', 'sas', '2025-06-17', '2025-06-18'),
(4, 1, '', 'Pending', '2025-06-17 19:43:51', NULL, 'Needs urgent callback', 'Printer', '2025-06-18', NULL),
(5, 3, '', 'Pending', '2025-06-18 02:51:03', NULL, 'need urgent callback', 'washing machine', '2025-06-18', '2025-06-18'),
(6, 1, '', 'Pending', '2025-07-18 06:17:52', NULL, 'SDDS', 'AS', '2025-07-18', '2025-07-18'),
(7, 1, '', 'new', '2025-07-18 07:26:27', 'Test Inquiry 1', NULL, NULL, NULL, NULL),
(8, 1, '', 'ongoing', '2025-07-08 07:26:27', 'Test Inquiry 2', NULL, NULL, NULL, NULL),
(9, 1, '', 'closed', '2025-06-28 07:26:27', 'Test Inquiry 3', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(20) DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `viewed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `followups`
--
ALTER TABLE `followups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `followups`
--
ALTER TABLE `followups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD CONSTRAINT `inquiries_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
