-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2026 at 01:35 PM
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
-- Database: `disaster`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `user_id`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `delivery_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `volunteer_id` int(11) NOT NULL,
  `assigned_date` date DEFAULT NULL,
  `delivery_status` enum('Assigned','In Transit','Delivered','Cancelled') DEFAULT 'Assigned',
  `delivery_time` datetime DEFAULT NULL,
  `assigned_area` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disaster_report`
--

CREATE TABLE `disaster_report` (
  `report_id` int(11) NOT NULL,
  `victim_id` int(11) NOT NULL,
  `area_name` varchar(100) NOT NULL,
  `disaster_type` enum('Flood','Fire','Cyclone','Earthquake','Landslide','Storm','Others') NOT NULL,
  `number_of_victims` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Resolved') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `disaster_report`
--

INSERT INTO `disaster_report` (`report_id`, `victim_id`, `area_name`, `disaster_type`, `number_of_victims`, `report_date`, `notes`, `status`) VALUES
(1, 1, 'Satkania', 'Flood', 100, '2026-07-30', 'No one was injured,,everyone is safed.', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `request_id` int(11) NOT NULL,
  `victim_id` int(11) NOT NULL,
  `expected_delivery_time` datetime DEFAULT NULL,
  `urgency_level` enum('Low','Medium','High','Critical') DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `request_status` enum('Pending','Approved','Assigned','In Transit','Completed','Rejected') DEFAULT 'Pending',
  `approval_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resource`
--

CREATE TABLE `resource` (
  `resource_id` int(11) NOT NULL,
  `resource_name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `available_stock` int(11) DEFAULT 0,
  `total_stock` int(11) DEFAULT 0,
  `expiry_date` date DEFAULT NULL,
  `last_updated` datetime DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resource`
--

INSERT INTO `resource` (`resource_id`, `resource_name`, `category`, `available_stock`, `total_stock`, `expiry_date`, `last_updated`, `unit`) VALUES
(2, 'Rice', 'Food', 5, 10, '2026-12-18', '2026-07-18 05:03:32', 'kg'),
(3, 'Dry Food Package', 'Food', 10, 12, '2026-08-03', '2026-07-30 12:53:28', 'Packets');

-- --------------------------------------------------------

--
-- Table structure for table `resource_request`
--

CREATE TABLE `resource_request` (
  `request_id` int(11) NOT NULL,
  `victim_id` int(11) NOT NULL,
  `food_type` varchar(100) DEFAULT NULL,
  `volunteer_id` int(11) DEFAULT NULL,
  `resource_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `medicine_type` varchar(100) DEFAULT NULL,
  `clothing_type` varchar(100) DEFAULT NULL,
  `request_date` date NOT NULL,
  `status` enum('Pending','Approved','Rejected','Assigned','In Transit','Delivered') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resource_request`
--

INSERT INTO `resource_request` (`request_id`, `victim_id`, `food_type`, `volunteer_id`, `resource_name`, `category`, `unit`, `quantity`, `medicine_type`, `clothing_type`, `request_date`, `status`) VALUES
(1, 1, 'Rice', 2, 'Food Request', 'Food', 'kg', 2.00, NULL, NULL, '2026-07-29', 'Delivered'),
(2, 1, NULL, 1, 'Dry Food Package', 'Food', 'Packets', 2.00, NULL, NULL, '2026-07-30', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `registration_date` date NOT NULL,
  `nid_number` varchar(20) NOT NULL,
  `role` enum('Admin','Volunteer','Victim') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `phone`, `address`, `password`, `registration_date`, `nid_number`, `role`) VALUES
(1, 'Jannatul Maowa', 'jannatul@gmail.com', '017xxxxxxxx', 'Chawkbazar', '$2y$10$Hi/eZcLK08WVeFQWEUhmyO4JmPdRj/O7hydmRrduIiYFrT4ikU6kO', '2026-07-17', '1234567', 'Volunteer'),
(2, 'System Administrator', 'admin@gmail.com', '01700000000', 'Chattogram, Bangladesh', '$2y$10$oLYYXKtIWlW8Q6Ux.dK8J.jqXoyCEzcYfVXDaDteBP4MJ7ijxIk1S', '2026-07-17', '1234567890123', 'Admin'),
(3, 'zakia', 'zakia@gmail.com', '017xxxxxxx1', 'Satkania', '$2y$10$QPaZ0bH3ct7mJTg/up59qulk4Aw67FVuuH7F6fE/kqlO4qU6FtSvO', '2026-07-17', '8910111213', 'Victim'),
(4, 'Rahim Alam', 'rahim@gmail.com', '01882299980', 'Agrabad', '$2y$10$WDJmFwf5Jb9EU0LaYj2ppOgUWoZvySNBcD0WKO9T/9A2N4rnvTY0K', '2026-07-28', '1122334455', 'Volunteer');

-- --------------------------------------------------------

--
-- Table structure for table `victim`
--

CREATE TABLE `victim` (
  `victim_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `number_of_family_members` int(11) DEFAULT NULL,
  `emergency_level` enum('Low','Medium','High','Critical') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `victim`
--

INSERT INTO `victim` (`victim_id`, `user_id`, `number_of_family_members`, `emergency_level`) VALUES
(1, 3, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `volunteer`
--

CREATE TABLE `volunteer` (
  `volunteer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `availability_status` enum('Available','Busy','Offline') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer`
--

INSERT INTO `volunteer` (`volunteer_id`, `user_id`, `availability_status`) VALUES
(1, 1, 'Available'),
(2, 4, 'Busy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `volunteer_id` (`volunteer_id`);

--
-- Indexes for table `disaster_report`
--
ALTER TABLE `disaster_report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `victim_id` (`victim_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `victim_id` (`victim_id`);

--
-- Indexes for table `resource`
--
ALTER TABLE `resource`
  ADD PRIMARY KEY (`resource_id`);

--
-- Indexes for table `resource_request`
--
ALTER TABLE `resource_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `victim_id` (`victim_id`),
  ADD KEY `fk_resource_volunteer` (`volunteer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `nid_number` (`nid_number`);

--
-- Indexes for table `victim`
--
ALTER TABLE `victim`
  ADD PRIMARY KEY (`victim_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD PRIMARY KEY (`volunteer_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `disaster_report`
--
ALTER TABLE `disaster_report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resource`
--
ALTER TABLE `resource`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `resource_request`
--
ALTER TABLE `resource_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `victim`
--
ALTER TABLE `victim`
  MODIFY `victim_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `volunteer`
--
ALTER TABLE `volunteer`
  MODIFY `volunteer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery`
--
ALTER TABLE `delivery`
  ADD CONSTRAINT `delivery_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `request` (`request_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delivery_ibfk_2` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteer` (`volunteer_id`) ON DELETE CASCADE;

--
-- Constraints for table `disaster_report`
--
ALTER TABLE `disaster_report`
  ADD CONSTRAINT `disaster_report_ibfk_1` FOREIGN KEY (`victim_id`) REFERENCES `victim` (`victim_id`) ON DELETE CASCADE;

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`victim_id`) REFERENCES `victim` (`victim_id`) ON DELETE CASCADE;

--
-- Constraints for table `resource_request`
--
ALTER TABLE `resource_request`
  ADD CONSTRAINT `fk_resource_volunteer` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteer` (`volunteer_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `resource_request_ibfk_1` FOREIGN KEY (`victim_id`) REFERENCES `victim` (`victim_id`) ON DELETE CASCADE;

--
-- Constraints for table `victim`
--
ALTER TABLE `victim`
  ADD CONSTRAINT `victim_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `volunteer`
--
ALTER TABLE `volunteer`
  ADD CONSTRAINT `volunteer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
