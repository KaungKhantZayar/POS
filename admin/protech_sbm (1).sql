-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2025 at 07:52 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `protech_sbm`
--

-- --------------------------------------------------------

--
-- Table structure for table `cash_purchase`
--

CREATE TABLE `cash_purchase` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `vr_no` int(11) NOT NULL,
  `supplier_id` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `po_no` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cash_purchase`
--

INSERT INTO `cash_purchase` (`id`, `date`, `vr_no`, `supplier_id`, `item_id`, `price`, `qty`, `po_no`) VALUES
(18, '2025-06-05', 25744427, '4001', '2001', 100000, 10, ''),
(23, '2025-06-06', 25430096, '4001', '2001', 100000, 10, ''),
(24, '2025-06-06', 25536557, '4001', '2001', 100000, 10, ''),
(25, '2025-06-06', 25378778, '4003', '2001', 100000, 10, ''),
(26, '2025-06-06', 25310267, '4001', '2001', 100000, 20, ''),
(27, '2025-06-06', 2529156, '4001', '2001', 100000, 10, ''),
(28, '2025-06-06', 25873809, '4003', '4001', 5000000, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `cash_sale`
--

CREATE TABLE `cash_sale` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `vr_no` int(11) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cash_sale`
--

INSERT INTO `cash_sale` (`id`, `date`, `vr_no`, `customer_id`, `item_id`, `qty`) VALUES
(1, '2025-06-04', 25001, '3002', '5003', 2),
(2, '2025-06-06', 35924157, '3001', '2001', 3),
(3, '2025-06-06', 35924157, '3001', '2001', 3),
(4, '2025-06-06', 35830920, '3001', '2001', 2),
(5, '2025-06-06', 35830920, '3001', '2001', 2),
(6, '2025-06-06', 35830920, '3001', '2001', 2),
(7, '2025-06-06', 35942074, '3001', '2001', 3),
(8, '2025-06-06', 35588102, '3001', '2001', 2);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `categories_code` varchar(255) NOT NULL,
  `categories_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `categories_code`, `categories_name`) VALUES
(4, 'C1001', 'Computer'),
(5, 'P2001', 'Phone');

-- --------------------------------------------------------

--
-- Table structure for table `credit_purchase`
--

CREATE TABLE `credit_purchase` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `vr_no` int(11) NOT NULL,
  `supplier_id` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `po_no` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `credit_purchase`
--

INSERT INTO `credit_purchase` (`id`, `date`, `vr_no`, `supplier_id`, `item_id`, `price`, `qty`, `po_no`) VALUES
(14, '2025-06-05', 2586403, '4003', '5002', 200000, 5, ''),
(15, '2025-06-05', 25637307, '4002', '2002', 3000000, 3, ''),
(16, '2025-06-06', 25308312, '4003', '2001', 100000, 2, ''),
(17, '2025-06-06', 25240583, '4003', '2001', 100000, 20, ''),
(18, '2025-06-06', 25141511, '4003', '2001', 100000, 1, ''),
(19, '2025-06-06', 25385756, '4003', '2001', 100000, 10, ''),
(20, '2025-06-06', 25208051, '4003', '4001', 5000000, 3, ''),
(21, '2025-06-06', 25956646, '4003', '5003', 2000000, 2, ''),
(22, '2025-06-13', 25107077, '4004', '2002', 3000000, 11, ''),
(23, '2025-06-13', 25120964, '4004', '2001', 100000, 5, ''),
(24, '2025-06-13', 25114007, '4004', '4001', 5000000, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `credit_sale`
--

CREATE TABLE `credit_sale` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `vr_no` int(11) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `credit_sale`
--

INSERT INTO `credit_sale` (`id`, `date`, `vr_no`, `customer_id`, `item_id`, `qty`) VALUES
(1, '2025-06-05', 25001, '3001', '5003', 2),
(2, '2025-06-06', 3521701, '3002', '5003', 2),
(3, '2025-06-06', 35274254, '3002', '5002', 5),
(4, '2025-06-06', 3521701, '3002', '5003', 2),
(5, '2025-06-06', 35274254, '3002', '5002', 5),
(6, '2025-06-06', 35993343, '3001', '2001', 3),
(7, '2025-06-12', 35470178, '1005', '5003', 1),
(8, '2025-06-12', 3559955, '1005', '4001', 1),
(11, '2025-06-14', 35826154, '1005', '2001', 2),
(12, '2025-06-14', 35249379, '1005', '4001', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` int(11) NOT NULL,
  `customer_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `customer_id`, `customer_name`, `customer_phone`, `customer_address`) VALUES
(2, 3001, 'Kyaw Kyaw', 98345888, 'yangon'),
(3, 3002, 'Mg Myat Thu ', 994838482, 'yangon'),
(5, 1005, 'Lexy', 876856768, 'ygn');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `categories_id` varchar(255) NOT NULL,
  `original_price` int(11) NOT NULL,
  `selling_price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `item_id`, `item_name`, `categories_id`, `original_price`, `selling_price`) VALUES
(2, '1001', 'lenovo', '4', 200000, 250000),
(4, '5001', 'Realme', '5', 120000, 150000),
(5, '2001', 'Acer', '4', 100000, 150000),
(6, '3001', 'Dell', '4', 2500000, 3000000),
(7, '4001', 'Apple', '4', 5000000, 5200000),
(8, '5002', 'oppo', '5', 200000, 220000),
(9, '5003', 'Samsung', '5', 2000000, 2200000),
(11, '2002', 'HP', '4', 3000000, 3500000);

-- --------------------------------------------------------

--
-- Table structure for table `payable`
--

CREATE TABLE `payable` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `vr_no` varchar(255) NOT NULL,
  `supplier_id` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `paid` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `asc_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payable`
--

INSERT INTO `payable` (`id`, `date`, `vr_no`, `supplier_id`, `amount`, `paid`, `balance`, `purchase_id`, `asc_id`, `group_id`, `status`) VALUES
(13, '2025-06-05', '25637307', '4002', 9000000, 0, 9000000, 15, 0, 0, 'pending'),
(17, '2025-06-06', '25001', '4002', 0, 100000, 8900000, 0, 0, 0, ''),
(24, '2025-06-06', '25385756', '4003', 1000000, 0, 1000000, 19, 19, 25385756, 'paid'),
(25, '2025-06-06', '25208051', '4003', 15000000, 0, 16000000, 20, 20, 25208051, 'paid'),
(26, '2025-06-06', '25956646', '4003', 4000000, 0, 4000000, 21, 24, 25956646, ''),
(56, '2025-06-07', '52149105', '4003', 0, 5000000, 11000000, 0, 21, 25208051, 'paid'),
(57, '2025-06-07', '52892419', '4003', 0, 1000000, 10000000, 0, 22, 52149105, 'paid'),
(58, '2025-06-07', '52933454', '4003', 0, 10000000, 0, 0, 23, 52892419, 'paid'),
(59, '2025-06-12', '52897171', '4002', 0, 5000000, 4000000, 0, 1, 25637307, 'pending'),
(60, '2025-06-13', '25107077', '4004', 33000000, 0, 33000000, 22, 22, 25107077, ''),
(66, '2025-06-13', '25120964', '4004', 500000, 0, 33500000, 23, 23, 25120964, ''),
(67, '2025-06-13', '25114007', '4004', 5000000, 0, 38500000, 24, 24, 25114007, '');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `id` int(11) NOT NULL,
  `order_no` varchar(100) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`id`, `order_no`, `supplier_id`, `order_date`, `item_id`, `qty`, `amount`, `status`) VALUES
(5, 'PO-396914', 4004, '2025-06-13', 2002, 100, 300000000, 'pending'),
(6, 'PO-234656', 4002, '2025-06-13', 4001, 50, 250000000, 'received');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return`
--

CREATE TABLE `purchase_return` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `return_vr_no` varchar(100) NOT NULL,
  `item_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `return_type` varchar(100) NOT NULL,
  `purchase_vr_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_return`
--

INSERT INTO `purchase_return` (`id`, `date`, `return_vr_no`, `item_id`, `qty`, `amount`, `reason`, `status`, `return_type`, `purchase_vr_no`) VALUES
(5, '2025-06-13', 'PR-928790', 2002, 1, 3000000, 'testing reason', 'pending', 'extra', 25107077);

-- --------------------------------------------------------

--
-- Table structure for table `receivable`
--

CREATE TABLE `receivable` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `vr_no` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `paid` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `asc_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `receivable`
--

INSERT INTO `receivable` (`id`, `date`, `vr_no`, `customer_id`, `amount`, `paid`, `balance`, `sale_id`, `asc_id`, `group_id`, `status`) VALUES
(1, '2025-06-06', '3521701', '3002', 4400000, 0, 4400000, 2, 0, 0, '0'),
(2, '2025-06-06', '35274254', '3002', 1100000, 0, 5500000, 3, 0, 0, '0'),
(3, '2025-06-06', '3521701', '3002', 4400000, 0, 9900000, 4, 0, 0, '0'),
(4, '2025-06-06', '35274254', '3002', 1100000, 0, 11000000, 5, 0, 0, '0'),
(5, '2025-06-06', '25001', '3002', 0, 100000, 10900000, 0, 0, 0, '0'),
(6, '2025-06-06', '35993343', '3001', 450000, 0, 450000, 6, 0, 0, '0'),
(7, '2025-06-12', '35470178', '1005', 2200000, 0, 2200000, 7, 7, 35470178, 'paid'),
(8, '2025-06-12', '3559955', '1005', 5200000, 0, 5200000, 8, 10, 3559955, ''),
(13, '2025-06-13', '25101300', '1005', 0, 200000, 2000000, 0, 8, 35470178, 'paid'),
(14, '2025-06-13', '25595494', '1005', 0, 2000000, 0, 0, 9, 25101300, 'paid'),
(17, '2025-06-14', '35826154', '1005', 300000, 0, 5500000, 11, 11, 35826154, ''),
(18, '2025-06-14', '35249379', '1005', 5200000, 0, 10550000, 12, 13, 35249379, ''),
(23, '2025-06-14', 'PR-406106', '1005', 0, 150000, 5350000, 0, 12, 35826154, '');

-- --------------------------------------------------------

--
-- Table structure for table `sale_order`
--

CREATE TABLE `sale_order` (
  `id` int(11) NOT NULL,
  `order_no` varchar(100) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_order`
--

INSERT INTO `sale_order` (`id`, `order_no`, `customer_id`, `order_date`, `item_id`, `qty`, `amount`, `status`) VALUES
(6, 'SO-90069', 1005, '2025-06-14', 2001, 2, 300000, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `sale_return`
--

CREATE TABLE `sale_return` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `return_vr_no` varchar(100) NOT NULL,
  `item_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` varchar(100) NOT NULL,
  `return_type` varchar(100) NOT NULL,
  `sale_vr_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_return`
--

INSERT INTO `sale_return` (`id`, `date`, `return_vr_no`, `item_id`, `qty`, `amount`, `reason`, `status`, `return_type`, `sale_vr_no`) VALUES
(3, '2025-06-14', 'PR-406106', 2001, 1, 150000, 'testing reason', 'received', 'wrong', 35826154);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `vr_no` int(11) NOT NULL,
  `to_from` varchar(100) NOT NULL,
  `in_qty` int(11) NOT NULL,
  `out_qty` int(11) NOT NULL,
  `foc_qty` int(11) NOT NULL,
  `balance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`id`, `date`, `item_id`, `vr_no`, `to_from`, `in_qty`, `out_qty`, `foc_qty`, `balance`) VALUES
(14, '2025-06-06', 2001, 25310267, 'purchase', 22, 0, 2, 22),
(15, '2025-06-06', 2001, 35588102, 'sale', 0, 2, 0, 20),
(16, '2025-06-06', 2001, 2529156, 'purchase', 10, 0, 0, 30),
(17, '2025-06-06', 2001, 25001, 'damage', 0, 1, 0, 29),
(18, '2025-06-06', 2001, 35993343, 'sale', 0, 3, 0, 26),
(19, '2025-06-06', 4001, 25873809, 'purchase', 1, 0, 0, 1),
(20, '2025-06-06', 2001, 25141511, 'purchase', 1, 0, 0, 27),
(21, '2025-06-06', 2001, 25385756, 'purchase', 10, 0, 0, 37),
(22, '2025-06-06', 4001, 25208051, 'purchase', 3, 0, 0, 4),
(23, '2025-06-06', 5003, 25956646, 'purchase', 2, 0, 0, 2),
(24, '2025-06-12', 5003, 35470178, 'sale', 0, 1, 0, 1),
(25, '2025-06-12', 4001, 3559955, 'sale', 0, 1, 0, 3),
(41, '2025-06-13', 2002, 25107077, 'purchase', 11, 0, 0, 11),
(44, '2025-06-13', 2001, 25120964, 'purchase', 5, 0, 0, 42),
(45, '2025-06-13', 4001, 25114007, 'purchase', 1, 0, 0, 4),
(50, '2025-06-14', 2001, 35826154, 'sale', 0, 2, 0, 40),
(51, '2025-06-14', 4001, 35249379, 'sale', 0, 1, 0, 3),
(56, '2025-06-14', 2001, 35826154, 'sale return', 1, 0, 0, 41);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_phone` int(11) NOT NULL,
  `supplier_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `supplier_id`, `supplier_name`, `supplier_phone`, `supplier_address`) VALUES
(3, 4002, 'Mg Mg', 766897896, 'yangon'),
(4, 4001, 'Ko Kyaw', 9667756, 'yangon'),
(5, 4003, 'Kaung Khant Zayar', 998877676, 'yangon'),
(6, 4004, 'Peter', 5464565, 'ygn');

-- --------------------------------------------------------

--
-- Table structure for table `temp_purchase`
--

CREATE TABLE `temp_purchase` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `vr_no` int(11) NOT NULL,
  `supplier_id` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `percentage` int(11) NOT NULL,
  `percentage_amount` int(11) NOT NULL,
  `stock_foc` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `po_no` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_sale`
--

CREATE TABLE `temp_sale` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `vr_no` int(11) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `percentage` int(11) NOT NULL,
  `percentage_amount` int(11) NOT NULL,
  `stock_foc` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `so_no` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cash_purchase`
--
ALTER TABLE `cash_purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_sale`
--
ALTER TABLE `cash_sale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_purchase`
--
ALTER TABLE `credit_purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_sale`
--
ALTER TABLE `credit_sale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payable`
--
ALTER TABLE `payable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_return`
--
ALTER TABLE `purchase_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receivable`
--
ALTER TABLE `receivable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_order`
--
ALTER TABLE `sale_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return`
--
ALTER TABLE `sale_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_purchase`
--
ALTER TABLE `temp_purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_sale`
--
ALTER TABLE `temp_sale`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cash_purchase`
--
ALTER TABLE `cash_purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `cash_sale`
--
ALTER TABLE `cash_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `credit_purchase`
--
ALTER TABLE `credit_purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `credit_sale`
--
ALTER TABLE `credit_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payable`
--
ALTER TABLE `payable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `purchase_return`
--
ALTER TABLE `purchase_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `receivable`
--
ALTER TABLE `receivable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `sale_order`
--
ALTER TABLE `sale_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sale_return`
--
ALTER TABLE `sale_return`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `temp_purchase`
--
ALTER TABLE `temp_purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `temp_sale`
--
ALTER TABLE `temp_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
