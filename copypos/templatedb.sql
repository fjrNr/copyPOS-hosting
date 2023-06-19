-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2020 at 06:19 AM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.1.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `copypos`
--

-- --------------------------------------------------------

--
-- Table structure for table `branchs`
--

CREATE TABLE `branchs` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `totalCredit` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `branchId` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `allowSale` tinyint(1) NOT NULL,
  `allowPurchase` tinyint(1) NOT NULL,
  `allowStock` tinyint(1) NOT NULL,
  `allowExpense` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `branchId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owners`
--

CREATE TABLE `owners` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_receptions`
--

CREATE TABLE `payment_receptions` (
  `id` int(11) NOT NULL,
  `saleId` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_submissions`
--

CREATE TABLE `payment_submissions` (
  `id` int(11) NOT NULL,
  `purchaseId` int(11) NOT NULL,
  `date` date NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `photocopy_sale_details`
--

CREATE TABLE `photocopy_sale_details` (
  `saleId` int(11) NOT NULL,
  `photocopyServiceId` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `photocopy_services`
--

CREATE TABLE `photocopy_services` (
  `id` int(11) NOT NULL,
  `paperProductId` int(11) NOT NULL,
  `branchId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sellPrice` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `print_sale_details`
--

CREATE TABLE `print_sale_details` (
  `saleId` int(11) NOT NULL,
  `printServiceId` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `print_services`
--

CREATE TABLE `print_services` (
  `id` int(11) NOT NULL,
  `paperProductId` int(11) NOT NULL,
  `branchId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sellPrice` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `branchId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `minStock` int(11) NOT NULL,
  `purchasePrice` int(11) NOT NULL,
  `sellPrice` int(11) NOT NULL,
  `isPaper` tinyint(1) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product_sale_details`
--

CREATE TABLE `product_sale_details` (
  `saleId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `branchId` int(11) NOT NULL,
  `supplierId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `invoiceNo` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `totalPrice` int(11) NOT NULL,
  `dueDate` date DEFAULT NULL,
  `paymentStatus` enum('dibatalkan','hutang','lunas') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_details`
--

CREATE TABLE `purchase_details` (
  `purchaseId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `branchId` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `invoiceNo` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `totalPrice` int(11) NOT NULL,
  `dueDate` date DEFAULT NULL,
  `paymentStatus` enum('dibatalkan','piutang','lunas') NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `branchId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sellPrice` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `service_sale_details`
--

CREATE TABLE `service_sale_details` (
  `saleId` int(11) NOT NULL,
  `serviceId` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_histories`
--

CREATE TABLE `stock_histories` (
  `id` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `date` date NOT NULL,
  `changeAmount` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `currStock` int(11) NOT NULL,
  `changeMethod` enum('pembelian','penjualan','penyesuaian stok','tambah produk baru') NOT NULL,
  `notes` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `companyName` varchar(255) DEFAULT NULL,
  `personName` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `totalDebt` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branchs`
--
ALTER TABLE `branchs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ownerId` (`ownerId`) USING BTREE;

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ownerId` (`ownerId`) USING BTREE;

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`phone`),
  ADD UNIQUE KEY `phone` (`username`),
  ADD KEY `branchId` (`branchId`) USING BTREE;

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkBranchtbExpenses` (`branchId`);

--
-- Indexes for table `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `payment_receptions`
--
ALTER TABLE `payment_receptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkSaleTbPaymentReceptions` (`saleId`);

--
-- Indexes for table `payment_submissions`
--
ALTER TABLE `payment_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkPurchaseTbPaymentSubmissions` (`purchaseId`);

--
-- Indexes for table `photocopy_sale_details`
--
ALTER TABLE `photocopy_sale_details`
  ADD PRIMARY KEY (`saleId`,`photocopyServiceId`),
  ADD KEY `idxPhotocopyServiceId` (`photocopyServiceId`) USING BTREE,
  ADD KEY `idxSaleId` (`saleId`);

--
-- Indexes for table `photocopy_services`
--
ALTER TABLE `photocopy_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkPaperProductTbFotocopyServices` (`paperProductId`),
  ADD KEY `fkbranchTbFotocopyServices` (`branchId`);

--
-- Indexes for table `print_sale_details`
--
ALTER TABLE `print_sale_details`
  ADD PRIMARY KEY (`saleId`,`printServiceId`),
  ADD KEY `idxPrintServiceId` (`printServiceId`) USING BTREE,
  ADD KEY `idxSaleId` (`saleId`);

--
-- Indexes for table `print_services`
--
ALTER TABLE `print_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkPaperProductTbPrintServices` (`paperProductId`),
  ADD KEY `fkbranchTbPrintServices` (`branchId`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branchId` (`branchId`);

--
-- Indexes for table `product_sale_details`
--
ALTER TABLE `product_sale_details`
  ADD PRIMARY KEY (`saleId`,`productId`),
  ADD KEY `idxProductId` (`productId`) USING BTREE,
  ADD KEY `idxSaleId` (`saleId`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkBranchtbPurchases` (`branchId`),
  ADD KEY `fkSuppliertbPurchases` (`supplierId`);

--
-- Indexes for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD PRIMARY KEY (`purchaseId`,`productId`),
  ADD KEY `idxProductId` (`productId`),
  ADD KEY `idxPurchaseId` (`purchaseId`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoiceNo` (`invoiceNo`),
  ADD KEY `branchId` (`branchId`),
  ADD KEY `customerId` (`customerId`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkBranchtbServices` (`branchId`);

--
-- Indexes for table `service_sale_details`
--
ALTER TABLE `service_sale_details`
  ADD PRIMARY KEY (`saleId`,`serviceId`) USING BTREE,
  ADD KEY `idxServiceId` (`serviceId`) USING BTREE,
  ADD KEY `idxSaleId` (`saleId`);

--
-- Indexes for table `stock_histories`
--
ALTER TABLE `stock_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fkProductTbStockHistories` (`productId`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ownerIdtbSupplier` (`ownerId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branchs`
--
ALTER TABLE `branchs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owners`
--
ALTER TABLE `owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_receptions`
--
ALTER TABLE `payment_receptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_submissions`
--
ALTER TABLE `payment_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `photocopy_services`
--
ALTER TABLE `photocopy_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `print_services`
--
ALTER TABLE `print_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_histories`
--
ALTER TABLE `stock_histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branchs`
--
ALTER TABLE `branchs`
  ADD CONSTRAINT `fkOwnertbBranchs` FOREIGN KEY (`ownerId`) REFERENCES `owners` (`id`);

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `fkOwnerTbCustomers` FOREIGN KEY (`ownerId`) REFERENCES `owners` (`id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `fkBranchtbEmployee` FOREIGN KEY (`branchId`) REFERENCES `branchs` (`id`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `fkBranchtbExpenses` FOREIGN KEY (`branchId`) REFERENCES `branchs` (`id`);

--
-- Constraints for table `payment_receptions`
--
ALTER TABLE `payment_receptions`
  ADD CONSTRAINT `fkSaleTbPaymentReceptions` FOREIGN KEY (`saleId`) REFERENCES `sales` (`id`);

--
-- Constraints for table `payment_submissions`
--
ALTER TABLE `payment_submissions`
  ADD CONSTRAINT `fkPurchaseTbPaymentSubmissions` FOREIGN KEY (`purchaseId`) REFERENCES `purchases` (`id`);

--
-- Constraints for table `photocopy_sale_details`
--
ALTER TABLE `photocopy_sale_details`
  ADD CONSTRAINT `fkPhotocopyTbPhotocopySaleDetails` FOREIGN KEY (`photocopyServiceId`) REFERENCES `photocopy_services` (`id`),
  ADD CONSTRAINT `fkSaleTbPhotocopySaleDetails` FOREIGN KEY (`saleId`) REFERENCES `sales` (`id`);

--
-- Constraints for table `photocopy_services`
--
ALTER TABLE `photocopy_services`
  ADD CONSTRAINT `fkPaperProductTbFotocopyServices` FOREIGN KEY (`paperProductId`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fkbranchTbFotocopyServices` FOREIGN KEY (`branchId`) REFERENCES `branchs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `print_sale_details`
--
ALTER TABLE `print_sale_details`
  ADD CONSTRAINT `fkPrintTbPrintSaleDetails` FOREIGN KEY (`printServiceId`) REFERENCES `print_services` (`id`),
  ADD CONSTRAINT `fkSaleTbPrintSaleDetails` FOREIGN KEY (`saleId`) REFERENCES `sales` (`id`);

--
-- Constraints for table `print_services`
--
ALTER TABLE `print_services`
  ADD CONSTRAINT `fkPaperProductTbPrintServices` FOREIGN KEY (`paperProductId`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fkbranchTbPrintServices` FOREIGN KEY (`branchId`) REFERENCES `branchs` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fkbranchTbProducts` FOREIGN KEY (`branchId`) REFERENCES `branchs` (`id`);

--
-- Constraints for table `product_sale_details`
--
ALTER TABLE `product_sale_details`
  ADD CONSTRAINT `fkProductTbProductSaleDetails` FOREIGN KEY (`productId`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fkSaleTbProductSaleDetails` FOREIGN KEY (`saleId`) REFERENCES `sales` (`id`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `fkBranchtbPurchases` FOREIGN KEY (`branchId`) REFERENCES `branchs` (`id`),
  ADD CONSTRAINT `fkSuppliertbPurchases` FOREIGN KEY (`supplierId`) REFERENCES `suppliers` (`id`);

--
-- Constraints for table `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD CONSTRAINT `fkProductTbPurchaseDetails` FOREIGN KEY (`productId`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fkPurchaseTbPurchaseDetails` FOREIGN KEY (`purchaseId`) REFERENCES `purchases` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fkBranchTbSales` FOREIGN KEY (`branchId`) REFERENCES `branchs` (`id`),
  ADD CONSTRAINT `fkCustomerTbSales` FOREIGN KEY (`customerId`) REFERENCES `customers` (`id`);

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `fkBranchtbServices` FOREIGN KEY (`branchId`) REFERENCES `branchs` (`id`);

--
-- Constraints for table `service_sale_details`
--
ALTER TABLE `service_sale_details`
  ADD CONSTRAINT `fkSaleTbServiceSaleDetails` FOREIGN KEY (`saleId`) REFERENCES `sales` (`id`),
  ADD CONSTRAINT `fkServiceTbServiceSaleDetails` FOREIGN KEY (`serviceId`) REFERENCES `services` (`id`);

--
-- Constraints for table `stock_histories`
--
ALTER TABLE `stock_histories`
  ADD CONSTRAINT `fkProductTbStockHistories` FOREIGN KEY (`productId`) REFERENCES `products` (`id`);

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `fk_ownerIdtbSupplier` FOREIGN KEY (`ownerId`) REFERENCES `owners` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
