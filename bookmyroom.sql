-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2026 at 04:56 PM
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
-- Database: `bookmyroom`
--

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `icon`) VALUES
(1, 'Wi-Fi', 'wifi'),
(2, 'Hồ bơi', 'pool'),
(3, 'Điều hòa', 'ac'),
(4, 'Bãi đỗ xe', 'parking'),
(5, 'Bữa sáng', 'food'),
(6, 'Tivi', 'tv'),
(7, 'Tủ lạnh', 'fridge'),
(8, 'Thang máy', 'lift'),
(9, 'Gym', 'gym'),
(10, 'Spa', 'spa');

-- --------------------------------------------------------

--
-- Table structure for table `auditlogs`
--

CREATE TABLE `auditlogs` (
  `id` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `entity` varchar(100) DEFAULT NULL,
  `entityId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bedtypes`
--

CREATE TABLE `bedtypes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `maxPeople` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bedtypes`
--

INSERT INTO `bedtypes` (`id`, `name`, `maxPeople`) VALUES
(1, 'Single', 1),
(2, 'Double', 2),
(3, 'Queen', 2),
(4, 'King', 2),
(5, 'Twin', 2),
(6, 'Triple', 3),
(7, 'Quad', 4),
(8, 'Sofa Bed', 1),
(9, 'Bunk', 1),
(10, 'California King', 2);

-- --------------------------------------------------------

--
-- Table structure for table `bookingdetails`
--

CREATE TABLE `bookingdetails` (
  `id` int(11) NOT NULL,
  `bookingId` int(11) NOT NULL,
  `roomConfigId` int(11) NOT NULL,
  `physicalRoomId` int(11) DEFAULT NULL,
  `checkIn` date NOT NULL,
  `checkOut` date NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookingdetails`
--

INSERT INTO `bookingdetails` (`id`, `bookingId`, `roomConfigId`, `physicalRoomId`, `checkIn`, `checkOut`, `quantity`, `price`, `amount`, `createdAt`) VALUES
(1, 1, 1, 1, '2026-04-01', '2026-04-03', 1, 500000.00, 1000000.00, '2026-03-25 03:49:07'),
(2, 2, 3, 4, '2026-04-05', '2026-04-06', 1, 800000.00, 800000.00, '2026-03-25 03:49:07'),
(3, 3, 2, 3, '2026-04-10', '2026-04-11', 1, 1200000.00, 1200000.00, '2026-03-25 03:49:07'),
(4, 4, 5, 6, '2026-04-15', '2026-04-16', 1, 3000000.00, 3000000.00, '2026-03-25 03:49:07'),
(5, 5, 6, 7, '2026-04-20', '2026-04-21', 1, 1500000.00, 1500000.00, '2026-03-25 03:49:07'),
(6, 6, 7, 8, '2026-05-01', '2026-05-02', 1, 2000000.00, 2000000.00, '2026-03-25 03:49:07'),
(7, 7, 1, 2, '2026-05-05', '2026-05-06', 1, 500000.00, 500000.00, '2026-03-25 03:49:07'),
(8, 8, 8, 9, '2026-05-10', '2026-05-11', 1, 900000.00, 900000.00, '2026-03-25 03:49:07'),
(9, 9, 9, 10, '2026-05-15', '2026-05-16', 1, 450000.00, 450000.00, '2026-03-25 03:49:07'),
(10, 10, 10, NULL, '2026-05-20', '2026-05-21', 1, 1800000.00, 1800000.00, '2026-03-25 03:49:07');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `status` enum('PENDING','CONFIRMED','CANCELLED','COMPLETED') NOT NULL DEFAULT 'PENDING',
  `source` enum('WEBSITE','BOOKING_DOT_COM','EXPEDIA','DIRECT') NOT NULL DEFAULT 'WEBSITE',
  `totalAmount` decimal(12,2) DEFAULT NULL,
  `platformFee` decimal(12,2) DEFAULT NULL,
  `partnerRevenue` decimal(12,2) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `userId`, `status`, `source`, `totalAmount`, `platformFee`, `partnerRevenue`, `createdAt`) VALUES
(1, 5, 'COMPLETED', 'WEBSITE', 1000000.00, 100000.00, 900000.00, '2026-03-25 03:49:07'),
(2, 6, 'CONFIRMED', 'BOOKING_DOT_COM', 800000.00, 120000.00, 680000.00, '2026-03-25 03:49:07'),
(3, 7, 'PENDING', 'WEBSITE', 1200000.00, 120000.00, 1080000.00, '2026-04-01 15:18:34'),
(4, 8, 'COMPLETED', 'DIRECT', 3000000.00, 0.00, 3000000.00, '2026-03-25 03:49:07'),
(5, 9, 'CANCELLED', 'EXPEDIA', 1500000.00, 225000.00, 1275000.00, '2026-03-25 03:49:07'),
(6, 10, 'COMPLETED', 'WEBSITE', 2000000.00, 200000.00, 1800000.00, '2026-03-25 03:49:07'),
(7, 5, 'COMPLETED', 'WEBSITE', 500000.00, 50000.00, 450000.00, '2026-03-25 03:49:07'),
(8, 6, 'CONFIRMED', 'WEBSITE', 900000.00, 90000.00, 810000.00, '2026-03-25 03:49:07'),
(9, 7, 'COMPLETED', 'WEBSITE', 450000.00, 45000.00, 405000.00, '2026-03-25 03:49:07'),
(10, 8, 'COMPLETED', 'WEBSITE', 1800000.00, 180000.00, 1620000.00, '2026-03-25 03:49:07');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`) VALUES
(1, 'Hồ Chí Minh'),
(2, 'Hà Nội'),
(3, 'Đà Nẵng'),
(4, 'Đà Lạt'),
(5, 'Nha Trang'),
(6, 'Vũng Tàu'),
(7, 'Huế'),
(8, 'Phú Quốc'),
(9, 'Cần Thơ'),
(10, 'Hải Phòng'),
(11, 'Tỉnh Quảng Nam'),
(12, 'Tỉnh Quảng Ninh'),
(13, 'Tỉnh Lào Cai'),
(14, 'Tỉnh Ninh Bình'),
(15, 'Tỉnh Kiên Giang');

-- --------------------------------------------------------

--
-- Table structure for table `hotelimages`
--

CREATE TABLE `hotelimages` (
  `id` int(11) NOT NULL,
  `hotelId` int(11) NOT NULL,
  `imageUrl` varchar(255) NOT NULL,
  `isPrimary` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotelimages`
--

INSERT INTO `hotelimages` (`id`, `hotelId`, `imageUrl`, `isPrimary`) VALUES
(1, 1, 'h1.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `partnerId` int(11) NOT NULL,
  `hotelName` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `cityId` int(11) NOT NULL,
  `wardId` int(11) NOT NULL,
  `address` text NOT NULL,
  `status` enum('ACTIVE','PENDING_STOP','STOPPED') DEFAULT 'ACTIVE',
  `createdAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `partnerId`, `hotelName`, `description`, `rating`, `cityId`, `wardId`, `address`, `status`, `createdAt`, `deletedAt`) VALUES
(1, 2, 'Skeeyzi Farm Stay', NULL, 4.5, 1, 1, '123 Bến Nghé', 'ACTIVE', '2026-03-25 10:49:07', NULL),
(2, 2, 'Saigon Riverside', NULL, 4.8, 1, 2, '45 Đa Kao', 'ACTIVE', '2026-03-25 10:49:07', NULL),
(3, 3, 'Hanoi Old Quarter', NULL, 4.2, 2, 3, '12 Hàng Đào', 'ACTIVE', '2026-03-25 10:49:07', NULL),
(4, 3, 'Tràng Tiền Luxury', NULL, 5, 2, 4, '88 Tràng Tiền', 'ACTIVE', '2026-03-25 10:49:07', NULL),
(5, 4, 'Danang Beach Hotel', NULL, 4.7, 3, 5, '01 Hải Châu', 'ACTIVE', '2026-03-25 10:49:07', NULL),
(6, 4, 'Green Hill Dalat', NULL, 4, 4, 6, '10 Phường 1', 'ACTIVE', '2026-03-25 10:49:07', NULL),
(7, 2, 'Nha Trang Oasis', NULL, 4.6, 5, 7, '20 Lộc Thọ', 'ACTIVE', '2026-03-25 10:49:07', NULL),
(8, 3, 'Vung Tau Corner', NULL, 3.9, 6, 8, '15 Thắng Tam', 'ACTIVE', '2026-03-25 10:49:07', NULL),
(9, 4, 'Hue Ancient House', NULL, 4.3, 7, 9, '05 Phú Hội', 'ACTIVE', '2026-03-25 10:49:07', NULL),
(10, 2, 'Phú Quốc Sunset', NULL, 4.9, 8, 10, '99 Dương Đông', 'ACTIVE', '2026-03-25 10:49:07', NULL),
(11, 2, 'Khách Sạn Test', 'abbcddCC', 0, 4, 6, '123 Đường A, Phường C', 'ACTIVE', '2026-04-02 21:41:41', NULL),
(12, 2, 'Khách sạn TEST2', 'dbef', 0, 1, 1, '123 Đường D', 'PENDING_STOP', '2026-04-02 21:46:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `userId` int(11) NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `taxCode` varchar(100) NOT NULL,
  `businessLicense` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`businessLicense`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`userId`, `companyName`, `taxCode`, `businessLicense`) VALUES
(2, 'Skeeyzi Farm Group', '0123456789', '{\"license\": \"L001\", \"issued\": \"2025-01-01\"}'),
(3, 'Trần Gia Hospitality', '0987654321', '{\"license\": \"L002\", \"issued\": \"2025-02-01\"}'),
(4, 'Hoàng Lê Travel', '0112233445', '{\"license\": \"L003\", \"issued\": \"2025-03-01\"}');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `bookingId` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `paymentMethod` varchar(50) DEFAULT NULL,
  `paymentStatus` enum('PENDING','PAID','FAILED','REFUNDED') DEFAULT NULL,
  `externalTransactionId` varchar(255) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `paidAt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `bookingId`, `amount`, `paymentMethod`, `paymentStatus`, `externalTransactionId`, `createdAt`, `paidAt`) VALUES
(1, 1, 1000000.00, 'MOMO', 'PAID', NULL, '2026-03-25 03:49:07', '2026-03-25 03:49:07'),
(2, 2, 800000.00, 'VNPAY', 'PAID', NULL, '2026-03-25 03:49:07', '2026-03-25 03:49:07'),
(3, 3, 1200000.00, 'MOMO', 'PENDING', NULL, '2026-03-25 03:49:07', '2026-03-25 03:49:07'),
(4, 4, 3000000.00, 'CASH', 'PAID', NULL, '2026-03-25 03:49:07', '2026-03-25 03:49:07'),
(5, 5, 1500000.00, 'VNPAY', 'FAILED', NULL, '2026-03-25 03:49:07', '2026-03-25 03:49:07'),
(6, 6, 2000000.00, 'VISA', 'PAID', NULL, '2026-03-25 03:49:07', '2026-03-25 03:49:07'),
(7, 7, 500000.00, 'MOMO', 'PAID', NULL, '2026-03-25 03:49:07', '2026-03-25 03:49:07'),
(8, 8, 900000.00, 'VNPAY', 'PAID', NULL, '2026-03-25 03:49:07', '2026-03-25 03:49:07'),
(9, 9, 450000.00, 'MOMO', 'REFUNDED', NULL, '2026-03-25 03:49:07', '2026-03-25 03:49:07'),
(10, 10, 1800000.00, 'VNPAY', 'PAID', NULL, '2026-03-25 03:49:07', '2026-03-25 03:49:07');

-- --------------------------------------------------------

--
-- Table structure for table `physicalrooms`
--

CREATE TABLE `physicalrooms` (
  `id` int(11) NOT NULL,
  `roomConfigId` int(11) NOT NULL,
  `roomNumber` varchar(50) NOT NULL,
  `floor` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `physicalrooms`
--

INSERT INTO `physicalrooms` (`id`, `roomConfigId`, `roomNumber`, `floor`, `status`, `deleted_at`) VALUES
(1, 1, '101', 1, 'AVAILABLE', NULL),
(2, 1, '102', 1, 'AVAILABLE', NULL),
(3, 2, '201', 2, 'AVAILABLE', NULL),
(4, 3, '105', 1, 'AVAILABLE', NULL),
(5, 4, '301', 3, 'AVAILABLE', NULL),
(6, 5, '801', 8, 'AVAILABLE', NULL),
(7, 6, '101', 1, 'AVAILABLE', NULL),
(8, 7, 'B01', 1, 'AVAILABLE', NULL),
(9, 8, '202', 2, 'AVAILABLE', NULL),
(10, 9, '101', 1, 'AVAILABLE', NULL),
(13, 12, '401', 4, 'AVAILABLE', NULL),
(14, 13, '402', 4, 'AVAILABLE', NULL),
(20, 15, '501', 5, 'AVAILABLE', '2026-04-01 21:28:01');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `bookingDetailId` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `replyContent` text DEFAULT NULL,
  `replyDate` datetime DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `userId`, `bookingDetailId`, `rating`, `content`, `replyContent`, `replyDate`, `createdAt`) VALUES
(1, 5, 1, 5, 'Dịch vụ tuyệt vời!', 'Xin chân thành cảm ơn!', '2026-04-01 21:44:46', '2026-04-01 14:44:46'),
(2, 6, 2, 4, 'Phòng hơi nhỏ', NULL, NULL, '2026-03-25 03:49:07'),
(3, 7, 3, 5, 'View đẹp xuất sắc', NULL, NULL, '2026-03-25 03:49:07'),
(4, 8, 4, 5, 'Khách sạn rất sang trọng', NULL, NULL, '2026-03-25 03:49:07'),
(5, 9, 5, 2, 'Hủy phòng do công việc nhưng hỗ trợ kém', NULL, NULL, '2026-03-25 03:49:07'),
(6, 10, 6, 4, 'Thoáng mát, sạch sẽ', NULL, NULL, '2026-03-25 03:49:07'),
(7, 5, 7, 3, 'Giá hơi cao so với chất lượng', NULL, NULL, '2026-03-25 03:49:07'),
(8, 6, 8, 5, 'Sẽ quay lại lần sau', NULL, NULL, '2026-03-25 03:49:07'),
(9, 7, 9, 4, 'Địa điểm thuận tiện', NULL, NULL, '2026-03-25 03:49:07'),
(10, 8, 10, 5, 'Nhân viên thân thiện', NULL, NULL, '2026-03-25 03:49:07');

-- --------------------------------------------------------

--
-- Table structure for table `roomamenities`
--

CREATE TABLE `roomamenities` (
  `roomConfigId` int(11) NOT NULL,
  `amenityId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomamenities`
--

INSERT INTO `roomamenities` (`roomConfigId`, `amenityId`) VALUES
(1, 1),
(1, 3),
(2, 1),
(2, 2),
(2, 3),
(3, 1),
(3, 6),
(4, 4),
(5, 9),
(5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `roomconfigurations`
--

CREATE TABLE `roomconfigurations` (
  `id` int(11) NOT NULL,
  `hotelId` int(11) NOT NULL,
  `roomTypeId` int(11) NOT NULL,
  `basePrice` decimal(12,2) NOT NULL,
  `area` int(11) DEFAULT NULL,
  `maxPeople` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomconfigurations`
--

INSERT INTO `roomconfigurations` (`id`, `hotelId`, `roomTypeId`, `basePrice`, `area`, `maxPeople`, `createdAt`, `deleted_at`) VALUES
(1, 1, 1, 500000.00, 28, 2, '2026-03-26 14:49:01', NULL),
(2, 1, 3, 1200000.00, 40, 3, '2026-03-27 02:25:34', NULL),
(3, 2, 2, 800000.00, 30, 2, '2026-03-25 03:49:07', NULL),
(4, 3, 1, 600000.00, 20, 2, '2026-03-25 03:49:07', NULL),
(5, 4, 4, 3000000.00, 80, 2, '2026-03-25 03:49:07', NULL),
(6, 5, 3, 1500000.00, 50, 2, '2026-03-25 03:49:07', NULL),
(7, 6, 8, 2000000.00, 45, 2, '2026-03-25 03:49:07', NULL),
(8, 7, 2, 900000.00, 35, 2, '2026-03-25 03:49:07', NULL),
(9, 8, 1, 450000.00, 22, 1, '2026-03-25 03:49:07', NULL),
(10, 9, 5, 1800000.00, 60, 4, '2026-03-25 03:49:07', NULL),
(12, 1, 5, 2000000.00, 40, 10, '2026-03-29 04:17:36', NULL),
(13, 1, 4, 700000.00, 30, 2, '2026-03-29 05:43:03', NULL),
(14, 1, 7, 5000000.00, 40, 4, '2026-03-30 16:06:00', '2026-03-30 23:06:00'),
(15, 1, 2, 3000000.00, 40, 4, '2026-04-01 14:28:01', '2026-04-01 21:28:01');

-- --------------------------------------------------------

--
-- Table structure for table `roomconfiguration_bedtypes`
--

CREATE TABLE `roomconfiguration_bedtypes` (
  `roomConfigId` int(11) NOT NULL,
  `bedTypeId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomconfiguration_bedtypes`
--

INSERT INTO `roomconfiguration_bedtypes` (`roomConfigId`, `bedTypeId`, `quantity`) VALUES
(1, 1, 2),
(2, 3, 1),
(3, 2, 1),
(4, 5, 1),
(5, 4, 1),
(6, 3, 2),
(7, 8, 1),
(8, 2, 1),
(9, 1, 1),
(10, 5, 2);

-- --------------------------------------------------------

--
-- Table structure for table `roomimages`
--

CREATE TABLE `roomimages` (
  `id` int(11) NOT NULL,
  `roomConfigId` int(11) NOT NULL,
  `imageUrl` varchar(255) NOT NULL,
  `isPrimary` tinyint(1) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roominventory`
--

CREATE TABLE `roominventory` (
  `id` int(11) NOT NULL,
  `roomConfigId` int(11) NOT NULL,
  `date` date NOT NULL,
  `availableCount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roominventory`
--

INSERT INTO `roominventory` (`id`, `roomConfigId`, `date`, `availableCount`) VALUES
(2, 1, '2026-04-02', 8),
(4, 3, '2026-04-01', 0),
(5, 4, '2026-04-01', 12),
(6, 5, '2026-04-01', 2),
(7, 6, '2026-04-01', 6),
(8, 7, '2026-04-01', 4),
(9, 8, '2026-04-01', 10),
(10, 9, '2026-04-01', 15),
(22, 3, '2026-04-02', 0),
(23, 3, '2026-04-03', 0),
(24, 3, '2026-04-04', 0);

-- --------------------------------------------------------

--
-- Table structure for table `roomprices`
--

CREATE TABLE `roomprices` (
  `id` int(11) NOT NULL,
  `roomConfigId` int(11) NOT NULL,
  `date` date NOT NULL,
  `price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomprices`
--

INSERT INTO `roomprices` (`id`, `roomConfigId`, `date`, `price`) VALUES
(1, 1, '2026-04-01', 500000.00),
(2, 1, '2026-04-02', 550000.00),
(3, 2, '2026-04-01', 1200000.00),
(4, 3, '2026-04-01', 1500000.00),
(5, 4, '2026-04-01', 600000.00),
(6, 5, '2026-04-01', 3000000.00),
(7, 6, '2026-04-01', 1500000.00),
(8, 7, '2026-04-01', 2000000.00),
(9, 8, '2026-04-01', 900000.00),
(10, 9, '2026-04-01', 450000.00),
(11, 1, '2026-03-30', 530000.00),
(49, 3, '2026-04-02', 1500000.00),
(50, 3, '2026-04-03', 1500000.00),
(51, 3, '2026-04-04', 1500000.00),
(55, 12, '2026-04-01', 2000000.00),
(56, 13, '2026-04-01', 700000.00);

-- --------------------------------------------------------

--
-- Table structure for table `roomtypes`
--

CREATE TABLE `roomtypes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomtypes`
--

INSERT INTO `roomtypes` (`id`, `name`) VALUES
(1, 'Standard'),
(2, 'Superior'),
(3, 'Deluxe'),
(4, 'Suite'),
(5, 'Family'),
(6, 'Studio'),
(7, 'Penthouse'),
(8, 'Bungalow'),
(9, 'Connecting'),
(10, 'Dorm');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('ACTIVE','PENDING','BLOCKED') NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `avatarUrl` varchar(255) DEFAULT NULL,
  `cityId` int(11) DEFAULT NULL,
  `wardId` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deletedAt` datetime DEFAULT NULL,
  `role` enum('Admin','Customer','Partner','Staff') DEFAULT 'Customer',
  `created_by` int(11) DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullName`, `email`, `password`, `phone`, `status`, `address`, `gender`, `birthDate`, `avatarUrl`, `cityId`, `wardId`, `createdAt`, `deletedAt`, `role`, `created_by`, `hotel_id`) VALUES
(1, 'Admin Skeeyzi', 'admin@skeeyzi.com', 'pass_hash', '0901234567', 'ACTIVE', NULL, NULL, NULL, NULL, 1, 1, '2026-03-30 07:39:37', NULL, 'Admin', NULL, NULL),
(2, 'Nguyễn Văn Đối Tác', 'partner1@gmail.com', 'pass_hash', '0901234568', 'ACTIVE', NULL, NULL, NULL, NULL, 1, 2, '2026-03-30 07:39:37', NULL, 'Partner', NULL, NULL),
(3, 'Trần Thị Chủ Căn', 'partner2@gmail.com', 'pass_hash', '0901234569', 'ACTIVE', NULL, NULL, NULL, NULL, 2, 3, '2026-03-30 07:39:37', NULL, 'Partner', NULL, NULL),
(4, 'Lê Hoàng Partner', 'partner3@gmail.com', 'pass_hash', '0901234570', 'ACTIVE', NULL, NULL, NULL, NULL, 3, 5, '2026-03-30 07:39:37', NULL, 'Partner', NULL, NULL),
(5, 'Khách Hàng A', 'customer1@gmail.com', 'pass_hash', '0901234571', 'ACTIVE', NULL, NULL, NULL, NULL, 1, 1, '2026-03-30 07:39:37', NULL, 'Customer', NULL, NULL),
(6, 'Khách Hàng B', 'customer2@gmail.com', 'pass_hash', '0901234572', 'ACTIVE', NULL, NULL, NULL, NULL, 2, 4, '2026-03-30 07:39:37', NULL, 'Customer', NULL, NULL),
(7, 'Khách Hàng C', 'customer3@gmail.com', 'pass_hash', '0901234573', 'ACTIVE', NULL, NULL, NULL, NULL, 4, 6, '2026-03-30 07:39:37', NULL, 'Customer', NULL, NULL),
(8, 'Khách Hàng D', 'customer4@gmail.com', 'pass_hash', '0901234574', 'ACTIVE', NULL, NULL, NULL, NULL, 5, 7, '2026-03-30 07:39:37', NULL, 'Customer', NULL, NULL),
(9, 'Khách Hàng E', 'customer5@gmail.com', 'pass_hash', '0901234575', 'ACTIVE', NULL, NULL, NULL, NULL, 6, 8, '2026-03-30 07:39:37', NULL, 'Customer', NULL, NULL),
(10, 'Khách Hàng F', 'customer6@gmail.com', 'pass_hash', '0901234576', 'ACTIVE', NULL, NULL, NULL, NULL, 1, 1, '2026-03-30 07:39:37', NULL, 'Customer', NULL, NULL),
(11, 'Tran Van B', 'tranvanb@dalatpalace.com', '$2y$10$uumxkZxh6O6sWD0Ovd2Woe5gy6GG9KwC001Bia7V6ydxrTY8zcMU.', '0123456789', 'BLOCKED', NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-01 14:29:58', '2026-04-01 21:29:58', 'Staff', 1, 1),
(12, 'Trần Quốc Cờ', 'quocco.dalatplace@gmail.com', '$2y$10$q4vKCTRFK./oh7YIrVPKGOGxx.IkQt/bf2w10I2n/8CSbgInWH57C', '0123456789', 'ACTIVE', NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-29 16:54:48', NULL, 'Staff', 1, 1),
(13, 'Nguyễn Thị Lười', 'thiluoi@gmail.com', '$2y$10$lx7OyeRQThXNnAw5rFSJMOCVA93cjt0hj7uylFDwGULxUXL.oZA4G', '0912112332', 'BLOCKED', NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-01 14:42:09', NULL, 'Staff', 1, 1),
(14, 'Ngô Thị Nhàn', 'thinhan@gmail.com', '$2y$10$RR0eJLE4yGkKCvMps6JZA..leK/PHPlSkuWHLVqq05lN.lxXnlMfK', '0988765421', 'BLOCKED', NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-01 14:41:32', '2026-04-01 21:41:32', 'Staff', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `condition` decimal(12,2) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `hotelId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `quantity`, `type`, `amount`, `condition`, `startDate`, `endDate`, `hotelId`) VALUES
(1, 'WELCOME', 100, 'PERCENT', 10.00, 0.00, '2026-01-01', '2026-12-31', 1),
(2, 'SALE50', 50, 'FIXED', 50000.00, 500000.00, '2026-03-01', '2026-04-01', 1),
(3, 'VIP30', 10, 'PERCENT', 30.00, 2000000.00, '2026-01-01', '2026-12-31', 1),
(4, 'SKEEYZI', 20, 'FIXED', 100000.00, 1000000.00, '2026-03-01', '2026-06-01', 1),
(5, 'SUMMER', 200, 'PERCENT', 15.00, 500000.00, '2026-06-01', '2026-08-31', 1),
(6, 'FREESHIP', 100, 'FIXED', 20000.00, 0.00, '2026-01-01', '2026-12-31', 1),
(7, 'OFFER10', 50, 'PERCENT', 10.00, 200000.00, '2026-01-01', '2026-03-31', 1),
(8, 'LUCKY', 1, 'FIXED', 500000.00, 1000000.00, '2026-04-01', '2026-04-30', 1),
(9, 'MOMO10', 100, 'FIXED', 10000.00, 100000.00, '2026-01-01', '2026-12-31', 1),
(10, 'VNPAY20', 50, 'PERCENT', 20.00, 1000000.00, '2026-01-01', '2026-12-31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wards`
--

CREATE TABLE `wards` (
  `id` int(11) NOT NULL,
  `cityId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wards`
--

INSERT INTO `wards` (`id`, `cityId`, `name`) VALUES
(1, 1, 'Phường Bến Nghé'),
(2, 1, 'Phường Đa Kao'),
(3, 2, 'Phường Hàng Đào'),
(4, 2, 'Phường Tràng Tiền'),
(5, 3, 'Phường Hải Châu I'),
(6, 4, 'Phường 1'),
(7, 5, 'Phường Lộc Thọ'),
(8, 6, 'Phường Thắng Tam'),
(9, 7, 'Phường Phú Hội'),
(10, 8, 'Dương Đông'),
(11, 11, 'Thành phố Hội An'),
(12, 11, 'Thành phố Tam Kỳ'),
(13, 11, 'Huyện Duy Xuyên (Mỹ Sơn)'),
(14, 12, 'Thành phố Hạ Long'),
(15, 12, 'Thành phố Móng Cái'),
(16, 12, 'Huyện Vân Đồn'),
(17, 13, 'Thị xã Sa Pa'),
(18, 13, 'Thành phố Lào Cai'),
(19, 14, 'Thành phố Ninh Bình'),
(20, 14, 'Huyện Hoa Lư (Tràng An)'),
(21, 15, 'Thành phố Phú Quốc'),
(22, 15, 'Thành phố Hà Tiên'),
(23, 5, 'Huyện Vạn Ninh (Vịnh Vân Phong)'),
(24, 6, 'Huyện Xuyên Mộc (Hồ Tràm)'),
(25, 10, 'Huyện Đảo Phú Quý');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auditlogs`
--
ALTER TABLE `auditlogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bedtypes`
--
ALTER TABLE `bedtypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookingdetails`
--
ALTER TABLE `bookingdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookingId` (`bookingId`),
  ADD KEY `roomConfigId` (`roomConfigId`),
  ADD KEY `physicalRoomId` (`physicalRoomId`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotelimages`
--
ALTER TABLE `hotelimages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotelId` (`hotelId`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `partnerId` (`partnerId`),
  ADD KEY `cityId` (`cityId`),
  ADD KEY `wardId` (`wardId`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `companyName` (`companyName`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookingId` (`bookingId`);

--
-- Indexes for table `physicalrooms`
--
ALTER TABLE `physicalrooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roomConfigId` (`roomConfigId`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookingDetailId` (`bookingDetailId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `roomamenities`
--
ALTER TABLE `roomamenities`
  ADD PRIMARY KEY (`roomConfigId`,`amenityId`),
  ADD KEY `amenityId` (`amenityId`);

--
-- Indexes for table `roomconfigurations`
--
ALTER TABLE `roomconfigurations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotelId` (`hotelId`),
  ADD KEY `roomTypeId` (`roomTypeId`);

--
-- Indexes for table `roomconfiguration_bedtypes`
--
ALTER TABLE `roomconfiguration_bedtypes`
  ADD PRIMARY KEY (`roomConfigId`,`bedTypeId`),
  ADD KEY `bedTypeId` (`bedTypeId`);

--
-- Indexes for table `roomimages`
--
ALTER TABLE `roomimages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roomConfigId` (`roomConfigId`);

--
-- Indexes for table `roominventory`
--
ALTER TABLE `roominventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roomConfigId_2` (`roomConfigId`,`date`),
  ADD KEY `roomConfigId` (`roomConfigId`);

--
-- Indexes for table `roomprices`
--
ALTER TABLE `roomprices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roomConfigId_2` (`roomConfigId`,`date`),
  ADD KEY `roomConfigId` (`roomConfigId`);

--
-- Indexes for table `roomtypes`
--
ALTER TABLE `roomtypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `cityId` (`cityId`),
  ADD KEY `wardId` (`wardId`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cityId` (`cityId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `auditlogs`
--
ALTER TABLE `auditlogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bedtypes`
--
ALTER TABLE `bedtypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `bookingdetails`
--
ALTER TABLE `bookingdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `hotelimages`
--
ALTER TABLE `hotelimages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `physicalrooms`
--
ALTER TABLE `physicalrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `roomconfigurations`
--
ALTER TABLE `roomconfigurations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `roomimages`
--
ALTER TABLE `roomimages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roominventory`
--
ALTER TABLE `roominventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `roomprices`
--
ALTER TABLE `roomprices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `roomtypes`
--
ALTER TABLE `roomtypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wards`
--
ALTER TABLE `wards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookingdetails`
--
ALTER TABLE `bookingdetails`
  ADD CONSTRAINT `bookingdetails_ibfk_1` FOREIGN KEY (`bookingId`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `bookingdetails_ibfk_2` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`),
  ADD CONSTRAINT `bookingdetails_ibfk_3` FOREIGN KEY (`physicalRoomId`) REFERENCES `physicalrooms` (`id`);

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Constraints for table `hotelimages`
--
ALTER TABLE `hotelimages`
  ADD CONSTRAINT `hotelimages_ibfk_1` FOREIGN KEY (`hotelId`) REFERENCES `hotels` (`id`);

--
-- Constraints for table `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `hotels_ibfk_1` FOREIGN KEY (`partnerId`) REFERENCES `partners` (`userId`),
  ADD CONSTRAINT `hotels_ibfk_2` FOREIGN KEY (`cityId`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `hotels_ibfk_3` FOREIGN KEY (`wardId`) REFERENCES `wards` (`id`);

--
-- Constraints for table `partners`
--
ALTER TABLE `partners`
  ADD CONSTRAINT `partners_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`bookingId`) REFERENCES `bookings` (`id`);

--
-- Constraints for table `physicalrooms`
--
ALTER TABLE `physicalrooms`
  ADD CONSTRAINT `physicalrooms_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`bookingDetailId`) REFERENCES `bookingdetails` (`id`);

--
-- Constraints for table `roomamenities`
--
ALTER TABLE `roomamenities`
  ADD CONSTRAINT `roomamenities_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`),
  ADD CONSTRAINT `roomamenities_ibfk_2` FOREIGN KEY (`amenityId`) REFERENCES `amenities` (`id`);

--
-- Constraints for table `roomconfigurations`
--
ALTER TABLE `roomconfigurations`
  ADD CONSTRAINT `roomconfigurations_ibfk_1` FOREIGN KEY (`hotelId`) REFERENCES `hotels` (`id`),
  ADD CONSTRAINT `roomconfigurations_ibfk_2` FOREIGN KEY (`roomTypeId`) REFERENCES `roomtypes` (`id`);

--
-- Constraints for table `roomconfiguration_bedtypes`
--
ALTER TABLE `roomconfiguration_bedtypes`
  ADD CONSTRAINT `roomconfiguration_bedtypes_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`),
  ADD CONSTRAINT `roomconfiguration_bedtypes_ibfk_2` FOREIGN KEY (`bedTypeId`) REFERENCES `bedtypes` (`id`);

--
-- Constraints for table `roomimages`
--
ALTER TABLE `roomimages`
  ADD CONSTRAINT `roomimages_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`);

--
-- Constraints for table `roominventory`
--
ALTER TABLE `roominventory`
  ADD CONSTRAINT `roominventory_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`);

--
-- Constraints for table `roomprices`
--
ALTER TABLE `roomprices`
  ADD CONSTRAINT `roomprices_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`cityId`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`wardId`) REFERENCES `wards` (`id`);

--
-- Constraints for table `wards`
--
ALTER TABLE `wards`
  ADD CONSTRAINT `wards_ibfk_1` FOREIGN KEY (`cityId`) REFERENCES `cities` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
