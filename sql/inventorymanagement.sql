-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2025 at 04:43 PM
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
-- Database: `inventorymanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `alertlog`
--

CREATE TABLE `alertlog` (
  `id` int(255) NOT NULL,
  `batchNumber` varchar(255) NOT NULL,
  `productUnid` varchar(255) NOT NULL,
  `alertType` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `productsname`
--

CREATE TABLE `productsname` (
  `id` int(11) NOT NULL,
  `productunid` varchar(255) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productsname`
--

INSERT INTO `productsname` (`id`, `productunid`, `ProductName`, `dateAdded`) VALUES
(1, '876539', 'raspbery pi 3 A', '2025-09-08 12:18:47'),
(2, '673366', 'rfids', '2025-09-29 13:14:44');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `salesUnid` varchar(255) NOT NULL,
  `productUnid` varchar(255) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `productQuantity` varchar(255) NOT NULL,
  `productPrice` varchar(255) NOT NULL,
  `paymentMethod` varchar(255) NOT NULL,
  `mpesaPayment` varchar(255) NOT NULL,
  `cashPayment` varchar(255) NOT NULL,
  `grandTotal` varchar(255) NOT NULL,
  `dateOfSales` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`salesUnid`, `productUnid`, `ProductName`, `productQuantity`, `productPrice`, `paymentMethod`, `mpesaPayment`, `cashPayment`, `grandTotal`, `dateOfSales`) VALUES
('6639566', '876539', 'raspbery pi 3 A', '20', '650', 'cash', '0', '13000', '13000', '2025-09-29 13:43:14'),
('265524', '876539', 'raspbery pi 3 A', '10', '300', 'cash', '0', '3000', '3000', '2025-09-29 13:51:26'),
('37231', '876539', 'raspbery pi 3 A', '5', '300', 'mpesa', '1500', '0', '1500', '2025-09-29 13:52:48'),
('760676', '876539', 'raspbery pi 3 A', '5', '300', 'cash', '0', '1500', '1500', '2025-09-29 13:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `salesbatches`
--

CREATE TABLE `salesbatches` (
  `salesUnid` varchar(255) NOT NULL,
  `batchNumber` varchar(255) NOT NULL,
  `qnySold` varchar(255) NOT NULL,
  `sellingPrice` varchar(255) NOT NULL,
  `grandTotal` varchar(255) NOT NULL,
  `dateOfSales` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salesbatches`
--

INSERT INTO `salesbatches` (`salesUnid`, `batchNumber`, `qnySold`, `sellingPrice`, `grandTotal`, `dateOfSales`) VALUES
('6639566', 'B2262', '20', '650', '13000', '2025-09-29 13:43:15'),
('265524', 'B2262', '10', '300', '3000', '2025-09-29 13:51:26'),
('37231', 'B2262', '5', '300', '1500', '2025-09-29 13:52:48'),
('760676', 'B2262', '5', '300', '1500', '2025-09-29 13:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `stockexpirylogs`
--

CREATE TABLE `stockexpirylogs` (
  `id` int(255) NOT NULL,
  `batchNumber` varchar(255) NOT NULL,
  `productUnid` varchar(255) NOT NULL,
  `expiryDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocktable`
--

CREATE TABLE `stocktable` (
  `id` int(11) NOT NULL,
  `productUnid` varchar(255) NOT NULL,
  `productName` varchar(255) NOT NULL,
  `batchNumber` varchar(255) NOT NULL,
  `instock` varchar(255) NOT NULL,
  `soldStock` varchar(255) NOT NULL,
  `totalQuantity` varchar(255) NOT NULL,
  `ppPiece` varchar(255) NOT NULL,
  `tbPrice` varchar(255) NOT NULL,
  `msPrice` varchar(255) NOT NULL,
  `status` enum('in stock','out stock') NOT NULL DEFAULT 'in stock',
  `expiryDate` timestamp NULL DEFAULT NULL,
  `arrivalDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stocktable`
--

INSERT INTO `stocktable` (`id`, `productUnid`, `productName`, `batchNumber`, `instock`, `soldStock`, `totalQuantity`, `ppPiece`, `tbPrice`, `msPrice`, `status`, `expiryDate`, `arrivalDate`) VALUES
(1, '876539', 'raspbery pi 3 A', 'B2262', '10', '40', '50', '100', '5000', '200', 'in stock', NULL, '2025-09-29 13:26:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alertlog`
--
ALTER TABLE `alertlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productsname`
--
ALTER TABLE `productsname`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocktable`
--
ALTER TABLE `stocktable`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alertlog`
--
ALTER TABLE `alertlog`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `productsname`
--
ALTER TABLE `productsname`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stocktable`
--
ALTER TABLE `stocktable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
