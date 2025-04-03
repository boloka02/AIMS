-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2025 at 07:22 AM
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
-- Database: `db_ams`
--

-- --------------------------------------------------------

--
-- Table structure for table `asset`
--

CREATE TABLE `asset` (
  `id` int(11) NOT NULL,
  `adonwork_no` varchar(200) NOT NULL,
  `mboard` varchar(200) NOT NULL,
  `keyboard` varchar(200) NOT NULL,
  `mouse` varchar(200) NOT NULL,
  `monitor` varchar(200) NOT NULL,
  `monitor2` varchar(200) NOT NULL,
  `webcam` varchar(200) NOT NULL,
  `headset` varchar(200) NOT NULL,
  `processor` varchar(200) NOT NULL,
  `ram` varchar(200) NOT NULL,
  `employee` varchar(200) NOT NULL,
  `assign_date` date NOT NULL,
  `laptop` varchar(200) NOT NULL,
  `location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `asset`
--

INSERT INTO `asset` (`id`, `adonwork_no`, `mboard`, `keyboard`, `mouse`, `monitor`, `monitor2`, `webcam`, `headset`, `processor`, `ram`, `employee`, `assign_date`, `laptop`, `location`) VALUES
(116, 'ADONWORK-312', 'MB-00001', 'KE-00001', 'MO-00001', 'MT-00001', 'MTnd-00001', 'WE-00001', 'HE-00001', 'PR-00001', 'RM-00001', 'Uzumaki Naturo', '2025-03-01', 'LA-00001', 'HR 2');

-- --------------------------------------------------------

--
-- Table structure for table `delete`
--

CREATE TABLE `delete` (
  `id` int(11) NOT NULL,
  `asset_id` varchar(200) NOT NULL,
  `Name` varchar(200) NOT NULL,
  `Category` varchar(200) NOT NULL,
  `Status` varchar(200) NOT NULL,
  `Value` varchar(200) NOT NULL,
  `warranty` date NOT NULL,
  `purchasedate` date NOT NULL,
  `assign` varchar(200) NOT NULL,
  `Location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `idnumber` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `position` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `adonwork_no` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `date_hired` date NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `total_asset_value` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `idnumber`, `name`, `position`, `department`, `adonwork_no`, `status`, `date_hired`, `email`, `password`, `total_asset_value`) VALUES
(12, '330022', 'Uzumaki Naturo', 'STANDING POSITION', 'MIS', 'ADONWORK-312', 'PROVE', '2025-03-01', '', '', '3961.75'),
(13, '09999', 'James Harden', 'STANDING POSITION', 'MIS', '', 'PROVE', '2025-03-01', '', '', '0'),
(14, '110200', 'Frutos', 'STANDING POSITION', 'MIS', '', 'PROVE', '2025-03-01', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `furniture`
--

CREATE TABLE `furniture` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty` date NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `model` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `headset`
--

CREATE TABLE `headset` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty` date NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `history` varchar(200) NOT NULL,
  `assign_to` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `headset`
--

INSERT INTO `headset` (`id`, `name`, `price`, `purchase_date`, `warranty`, `supplier`, `status`, `history`, `assign_to`) VALUES
(172, 'HE-00001', '2716', '2025-03-01', '2025-03-19', 'Elton', 'Assigned', '', 'Uzumaki Naturo'),
(173, 'HE-00002', '2716', '2025-03-01', '2025-03-19', 'Elton', 'Available', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `type` varchar(200) NOT NULL,
  `category` varchar(200) NOT NULL,
  `quantity` varchar(200) NOT NULL,
  `total_value` varchar(200) NOT NULL,
  `stock` varchar(200) NOT NULL,
  `available_stock` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `purchasedate` date NOT NULL,
  `warranty` date NOT NULL,
  `location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `type`, `category`, `quantity`, `total_value`, `stock`, `available_stock`, `status`, `purchasedate`, `warranty`, `location`) VALUES
(170, 'Headset', 'IT Equipment', '2', '5432', 'In Stock', '1', 'Available', '2025-03-01', '2025-03-19', 'Storage Room'),
(171, 'Keyboard', 'IT Equipment', '2', '312', 'In Stock', '1', 'Available', '2025-03-01', '2025-03-19', 'Storage Room'),
(172, 'Mboard', 'IT Equipment', '3', '123', 'In Stock', '2', 'Available', '2025-03-01', '2025-03-19', 'Storage Room'),
(173, 'Monitor', 'IT Equipment', '2', '321', 'In Stock', '1', 'Available', '2025-03-01', '2025-03-19', 'Storage Room'),
(174, '2nd Monitor', 'IT Equipment', '3', '1242', 'In Stock', '5', 'Available', '2025-03-01', '2025-03-19', 'Storage Room'),
(175, 'Mouse', 'IT Equipment', '4', '231', 'In Stock', '3', 'Available', '2025-03-01', '2025-03-18', 'Management'),
(176, 'Processor', 'IT Equipment', '2', '431', 'In Stock', '1', 'Available', '2025-03-01', '2025-03-19', 'Storage Room'),
(177, 'RAM', 'IT Equipment', '2', '412', 'In Stock', '1', 'Available', '2025-03-01', '2025-03-19', 'AdOn'),
(178, 'Webcam', 'IT Equipment', '2', '421', 'In Stock', '1', 'Available', '2025-03-01', '2025-03-13', 'Management'),
(179, 'Laptop', 'IT Equipment', '2', '421', 'In Stock', '1', 'Available', '2025-03-01', '2025-03-19', 'Storage Room');

-- --------------------------------------------------------

--
-- Table structure for table `keyboard`
--

CREATE TABLE `keyboard` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty` date NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `assign_to` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keyboard`
--

INSERT INTO `keyboard` (`id`, `name`, `price`, `purchase_date`, `warranty`, `supplier`, `status`, `assign_to`) VALUES
(41, 'KE-00001', '156', '2025-03-01', '2025-03-19', 'Elton', 'Assigned', 'Uzumaki Naturo'),
(42, 'KE-00002', '156', '2025-03-01', '2025-03-19', 'Elton', 'Available', '');

-- --------------------------------------------------------

--
-- Table structure for table `laptop`
--

CREATE TABLE `laptop` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `model` varchar(200) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty` date NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `assign_to` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laptop`
--

INSERT INTO `laptop` (`id`, `name`, `model`, `purchase_date`, `warranty`, `supplier`, `price`, `status`, `assign_to`) VALUES
(67, 'LA-00001', '2025-03-19', '0000-00-00', '2025-03-01', '210.5', 'ryen 5 6000', 'Assigned', 'Uzumaki Naturo'),
(68, 'LA-00002', '2025-03-19', '0000-00-00', '2025-03-01', '210.5', 'ryen 5 6000', 'Available', '');

-- --------------------------------------------------------

--
-- Table structure for table `mboard`
--

CREATE TABLE `mboard` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty` date NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `assign_to` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mboard`
--

INSERT INTO `mboard` (`id`, `name`, `price`, `purchase_date`, `warranty`, `supplier`, `status`, `assign_to`) VALUES
(36, 'MB-00001', '41', '2025-03-01', '2025-03-19', 'Jasper', 'Assigned', 'Uzumaki Naturo'),
(37, 'MB-00002', '41', '2025-03-01', '2025-03-19', 'Jasper', '', ''),
(38, 'MB-00003', '41', '2025-03-01', '2025-03-19', 'Jasper', 'Available', '');

-- --------------------------------------------------------

--
-- Table structure for table `monitor`
--

CREATE TABLE `monitor` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `size` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty` date NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `assign_to` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monitor`
--

INSERT INTO `monitor` (`id`, `name`, `size`, `price`, `purchase_date`, `warranty`, `supplier`, `status`, `assign_to`) VALUES
(156, 'MT-00001', '45', '160.5', '2025-03-01', '2025-03-19', 'Jasper', 'Assigned', 'Uzumaki Naturo'),
(157, 'MT-00002', '45', '160.5', '2025-03-01', '2025-03-19', 'Jasper', 'Available', '');

-- --------------------------------------------------------

--
-- Table structure for table `monitor2`
--

CREATE TABLE `monitor2` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `size` varchar(200) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty` date NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `assign_to` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monitor2`
--

INSERT INTO `monitor2` (`id`, `name`, `price`, `size`, `purchase_date`, `warranty`, `supplier`, `status`, `assign_to`) VALUES
(212, 'MTnd-00001', '414', '27', '2025-03-01', '2025-03-19', 'Jasper', 'Assigned', 'Uzumaki Naturo'),
(213, 'MTnd-00002', '414', '27', '2025-03-01', '2025-03-19', 'Jasper', '', ''),
(214, 'MTnd-00003', '414', '27', '2025-03-01', '2025-03-19', 'Jasper', 'Available', '');

-- --------------------------------------------------------

--
-- Table structure for table `mouse`
--

CREATE TABLE `mouse` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty` date NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `assign_to` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mouse`
--

INSERT INTO `mouse` (`id`, `name`, `price`, `purchase_date`, `warranty`, `supplier`, `status`, `assign_to`) VALUES
(27, 'MO-00001', '57.75', '2025-03-01', '2025-03-18', 'Elton', 'Assigned', 'Uzumaki Naturo'),
(28, 'MO-00002', '57.75', '2025-03-01', '2025-03-18', 'Elton', '', ''),
(29, 'MO-00003', '57.75', '2025-03-01', '2025-03-18', 'Elton', 'Available', ''),
(30, 'MO-00004', '57.75', '2025-03-01', '2025-03-18', 'Elton', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `processor`
--

CREATE TABLE `processor` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty` date NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `model` varchar(200) NOT NULL,
  `assign_to` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `processor`
--

INSERT INTO `processor` (`id`, `name`, `price`, `purchase_date`, `warranty`, `supplier`, `status`, `model`, `assign_to`) VALUES
(21, 'PR-00001', 'ryen 5 6000', '0000-00-00', '2025-03-01', '215.5', 'Assigned', '2025-03-19', 'Uzumaki Naturo'),
(22, 'PR-00002', 'ryen 5 6000', '0000-00-00', '2025-03-01', '215.5', 'Available', '2025-03-19', '');

-- --------------------------------------------------------

--
-- Table structure for table `ram`
--

CREATE TABLE `ram` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty` date NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `capacity` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `assign_to` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ram`
--

INSERT INTO `ram` (`id`, `name`, `price`, `purchase_date`, `warranty`, `supplier`, `capacity`, `status`, `assign_to`) VALUES
(21, 'RM-00001', '206', '2025-03-01', '2025-03-19', 'Elton', '16GB', 'Assigned', 'Uzumaki Naturo'),
(22, 'RM-00002', '206', '2025-03-01', '2025-03-19', 'Elton', '16GB', 'Available', '');

-- --------------------------------------------------------

--
-- Table structure for table `superuser`
--

CREATE TABLE `superuser` (
  `id` int(11) NOT NULL,
  `idnumber` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `superuser`
--

INSERT INTO `superuser` (`id`, `idnumber`, `name`, `email`, `password`, `role`) VALUES
(1, '110200', 'JINWOO', 'jinwoo@adongroup.com.au', 'admin', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `ticket_number` varchar(200) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `date_created` varchar(200) NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `priority` varchar(200) NOT NULL,
  `image` varchar(200) NOT NULL,
  `accept` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`id`, `ticket_number`, `subject`, `status`, `date_created`, `created_by`, `priority`, `image`, `accept`) VALUES
(54, 'TCK-0001', 'ghgf', 'Pending', '2025-03-25', 'Frutos', 'High', 'uploads/img2.jpg', 'James Harden'),
(55, 'TCK-0002', 'werew', 'Pending', '2025-03-25', 'Frutos', 'High', 'uploads/image.jpg', 'James Harden'),
(56, 'TCK-0003', 'guba akaoa PC', 'Pending', '2025-03-25', 'Frutos', 'High', '', 'James Harden'),
(57, 'TCK-0004', 'guba akaoa PC', 'Pending', '2025-03-25', 'Uzumaki Naturo', 'High', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `idnumber` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `idnumber`, `email`, `password`, `role`, `name`) VALUES
(3, '09999', 'admin@argon.com', '$2y$10$R8egrXA1BFveSqDE71b3PueCmKwMGOpNq5Gt6yBX3wqNAj8XPj/vG', 'Admin', 'James Harden'),
(5, '330022', 'jinwoo@adongroup', '$2y$10$qTiWpfxvOvw1rgh5lZAwou7YUlF21SeC8ISJVPUMG9hc8Zd7d9VpO', 'Employee', 'Uzumaki Naturo'),
(6, '110200', 'frutos@adongroup.com.au', '$2y$10$8hNM3/SoXEQvGY.0Gff/geUo7ggDyOHysa9mJHal40z5BTnYbN456', 'Employee', 'Frutos');

-- --------------------------------------------------------

--
-- Table structure for table `webcam`
--

CREATE TABLE `webcam` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` varchar(200) NOT NULL,
  `purchase_date` date NOT NULL,
  `warranty` date NOT NULL,
  `supplier` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `assign_to` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `webcam`
--

INSERT INTO `webcam` (`id`, `name`, `price`, `purchase_date`, `warranty`, `supplier`, `status`, `assign_to`) VALUES
(33, 'WE-00001', '210.5', '2025-03-01', '2025-03-13', 'Jasper', 'Assigned', 'Uzumaki Naturo'),
(34, 'WE-00002', '210.5', '2025-03-01', '2025-03-13', 'Jasper', 'Available', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `asset`
--
ALTER TABLE `asset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delete`
--
ALTER TABLE `delete`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `furniture`
--
ALTER TABLE `furniture`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `headset`
--
ALTER TABLE `headset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keyboard`
--
ALTER TABLE `keyboard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laptop`
--
ALTER TABLE `laptop`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mboard`
--
ALTER TABLE `mboard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monitor`
--
ALTER TABLE `monitor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monitor2`
--
ALTER TABLE `monitor2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mouse`
--
ALTER TABLE `mouse`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `processor`
--
ALTER TABLE `processor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ram`
--
ALTER TABLE `ram`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `superuser`
--
ALTER TABLE `superuser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webcam`
--
ALTER TABLE `webcam`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `asset`
--
ALTER TABLE `asset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `delete`
--
ALTER TABLE `delete`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `furniture`
--
ALTER TABLE `furniture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `headset`
--
ALTER TABLE `headset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `keyboard`
--
ALTER TABLE `keyboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `laptop`
--
ALTER TABLE `laptop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `mboard`
--
ALTER TABLE `mboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `monitor`
--
ALTER TABLE `monitor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `monitor2`
--
ALTER TABLE `monitor2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;

--
-- AUTO_INCREMENT for table `mouse`
--
ALTER TABLE `mouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `processor`
--
ALTER TABLE `processor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `ram`
--
ALTER TABLE `ram`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `superuser`
--
ALTER TABLE `superuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `webcam`
--
ALTER TABLE `webcam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
