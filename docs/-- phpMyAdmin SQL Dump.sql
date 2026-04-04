-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 04, 2026 lúc 10:17 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `bookmyroom`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `amenities`
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
-- Cấu trúc bảng cho bảng `auditlogs`
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
-- Cấu trúc bảng cho bảng `bedtypes`
--

CREATE TABLE `bedtypes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `maxPeople` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bedtypes`
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
-- Cấu trúc bảng cho bảng `bookingdetails`
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
-- Đang đổ dữ liệu cho bảng `bookingdetails`
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
(10, 10, 10, NULL, '2026-05-20', '2026-05-21', 1, 1800000.00, 1800000.00, '2026-03-25 03:49:07'),
(11, 1, 1, 2, '2026-03-30', '2026-04-01', 1, 555555.00, 555555.00, '2026-03-29 02:05:56'),
(12, 2, 2, 4, '2026-04-01', '2026-04-04', 1, 555555.00, 555555.00, '2026-03-29 02:50:17'),
(13, 2, 11, 4, '2026-04-01', '2026-04-04', 1, 555555.00, 555555.00, '2026-03-29 02:50:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `status` enum('PENDING','CONFIRMED','CANCELLED','COMPLETED') NOT NULL DEFAULT 'PENDING',
  `source` enum('WEBSITE','BOOKING_DOT_COM','EXPEDIA','DIRECT') NOT NULL DEFAULT 'WEBSITE',
  `totalAmount` decimal(12,2) DEFAULT NULL,
  `platformFee` decimal(12,2) DEFAULT NULL,
  `partnerRevenue` decimal(12,2) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deposit` decimal(11,0) DEFAULT 0,
  `voucherId` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `userId`, `status`, `source`, `totalAmount`, `platformFee`, `partnerRevenue`, `createdAt`, `deposit`, `voucherId`) VALUES
(1, 5, 'COMPLETED', 'WEBSITE', 1000000.00, 100000.00, 900000.00, '2026-03-25 03:49:07', 0, NULL),
(2, 6, 'CONFIRMED', 'BOOKING_DOT_COM', 800000.00, 120000.00, 680000.00, '2026-03-25 03:49:07', 0, NULL),
(3, 7, 'PENDING', 'WEBSITE', 1200000.00, 120000.00, 1080000.00, '2026-03-25 03:49:07', 0, NULL),
(4, 8, 'COMPLETED', 'DIRECT', 3000000.00, 0.00, 3000000.00, '2026-03-25 03:49:07', 0, NULL),
(5, 9, 'CANCELLED', 'EXPEDIA', 1500000.00, 225000.00, 1275000.00, '2026-03-25 03:49:07', 0, NULL),
(6, 10, 'COMPLETED', 'WEBSITE', 2000000.00, 200000.00, 1800000.00, '2026-03-25 03:49:07', 0, NULL),
(7, 5, 'COMPLETED', 'WEBSITE', 500000.00, 50000.00, 450000.00, '2026-03-25 03:49:07', 0, NULL),
(8, 6, 'CONFIRMED', 'WEBSITE', 900000.00, 90000.00, 810000.00, '2026-03-25 03:49:07', 0, NULL),
(9, 7, 'COMPLETED', 'WEBSITE', 450000.00, 45000.00, 405000.00, '2026-03-25 03:49:07', 0, NULL),
(10, 8, 'COMPLETED', 'WEBSITE', 1800000.00, 180000.00, 1620000.00, '2026-03-25 03:49:07', 0, NULL),
(11, 11, 'PENDING', 'WEBSITE', 0.00, 0.00, 0.00, '2026-04-01 01:19:14', 0, NULL),
(12, 11, 'PENDING', 'WEBSITE', 400000000.00, 40400000.00, 278800000.00, '2026-04-01 01:21:00', 121200000, NULL),
(13, 11, 'PENDING', 'WEBSITE', 10000.00, 1010.00, 6970.00, '2026-04-01 01:22:13', 3030, NULL),
(14, 11, 'PENDING', 'WEBSITE', 10000.00, 1010.00, 6970.00, '2026-04-01 01:23:41', 3030, NULL),
(15, 11, 'PENDING', 'WEBSITE', 4800000.00, 480000.00, 4320000.00, '2026-04-01 09:43:01', 1454400, NULL),
(16, 11, 'PENDING', 'WEBSITE', 4800000.00, 480000.00, 4320000.00, '2026-04-01 09:43:57', 1454400, NULL),
(17, 11, 'PENDING', 'WEBSITE', 4800000.00, 480000.00, 4320000.00, '2026-04-01 09:55:01', 1454400, NULL),
(18, 11, 'PENDING', 'WEBSITE', 10800000.00, 1080000.00, 9720000.00, '2026-04-01 10:18:22', 3272400, NULL),
(20, 11, 'PENDING', 'WEBSITE', 10800000.00, 1080000.00, 9720000.00, '2026-04-01 10:24:05', 3272400, NULL),
(21, 11, 'PENDING', 'WEBSITE', 10800000.00, 1080000.00, 9720000.00, '2026-04-01 10:26:42', 3272400, NULL),
(22, 11, 'PENDING', 'WEBSITE', 10800000.00, 1080000.00, 9720000.00, '2026-04-01 10:31:35', 3272400, NULL),
(23, 11, 'PENDING', 'WEBSITE', 10800000.00, 1080000.00, 9720000.00, '2026-04-01 11:04:18', 3272400, NULL),
(24, 11, 'PENDING', 'WEBSITE', 153600000.00, 15360000.00, 138240000.00, '2026-04-01 14:25:51', 46540800, NULL),
(25, 11, 'PENDING', 'WEBSITE', 153600000.00, 15360000.00, 138240000.00, '2026-04-01 14:26:08', 46540800, NULL),
(28, 11, 'PENDING', 'WEBSITE', 6000000.00, 600000.00, 5400000.00, '2026-04-01 14:29:30', 1818000, NULL),
(29, 11, 'PENDING', 'WEBSITE', 6000000.00, 600000.00, 5400000.00, '2026-04-01 14:36:53', 1818000, NULL),
(30, 11, 'PENDING', 'WEBSITE', 6000000.00, 600000.00, 5400000.00, '2026-04-01 14:39:14', 1818000, NULL),
(33, 11, 'PENDING', 'WEBSITE', 6000000.00, 600000.00, 5400000.00, '2026-04-01 14:58:36', 1818000, NULL),
(34, 11, 'PENDING', 'WEBSITE', 6000000.00, 600000.00, 5400000.00, '2026-04-01 14:58:45', 1818000, NULL),
(35, 11, 'PENDING', 'WEBSITE', 6000000.00, 600000.00, 5400000.00, '2026-04-01 15:01:09', 1818000, NULL),
(38, 11, 'CONFIRMED', 'WEBSITE', 3600000.00, 360000.00, 3240000.00, '2026-04-01 15:51:48', 1090800, NULL),
(39, 11, 'CONFIRMED', 'WEBSITE', 3600000.00, 360000.00, 3240000.00, '2026-04-01 15:55:37', 1090800, NULL),
(40, 13, 'CONFIRMED', 'WEBSITE', 150000000.00, 15000000.00, 135000000.00, '2026-04-03 04:03:59', 45450000, NULL),
(41, 13, 'CONFIRMED', 'WEBSITE', 3300000.00, 330000.00, 2970000.00, '2026-04-03 00:27:26', 999900, NULL),
(42, 13, 'CONFIRMED', 'WEBSITE', 2400000.00, 240000.00, 2160000.00, '2026-04-03 04:04:16', 727200, NULL),
(43, 13, 'CONFIRMED', 'WEBSITE', 7600000.00, 760000.00, 6840000.00, '2026-04-03 04:11:46', 2302800, NULL),
(44, 13, 'CONFIRMED', 'WEBSITE', 1200000.00, 120000.00, 1080000.00, '2026-04-03 14:14:02', 363600, NULL),
(45, 13, 'CONFIRMED', 'WEBSITE', 1200000.00, 120000.00, 1080000.00, '2026-04-03 14:28:15', 363600, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cities`
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
(10, 'Hải Phòng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hotelimages`
--

CREATE TABLE `hotelimages` (
  `id` int(11) NOT NULL,
  `hotelId` int(11) NOT NULL,
  `imageUrl` text NOT NULL,
  `isPrimary` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hotelimages`
--

INSERT INTO `hotelimages` (`id`, `hotelId`, `imageUrl`, `isPrimary`) VALUES
(1, 1, 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/809511045.jpg?k=7a0a0a712c790d7679df98fc6e1c1c690aa3bed72bd1e672695181f3534d682a&o=', 1),
(2, 1, 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/695658127.jpg?k=e760d692f1a24af811af5560c3d1c4ec71264dd3ce445f91631bdef4e32b666c&o=', 0),
(3, 1, 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/809511045.jpg?k=7a0a0a712c790d7679df98fc6e1c1c690aa3bed72bd1e672695181f3534d682a&o=', 0),
(4, 1, 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/617560088.jpg?k=b38129a1f880137d7f6a7e1f54dd2232ceb15a951aab686d26aabe4ab688647c&o=', 0),
(5, 1, 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/809511299.jpg?k=bfaaed0c2162b47698684e041f083fdafb8f67f20152ae676bcaf5796a54592c&o=', 0),
(6, 1, 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/739595265.jpg?k=c2ecc1d5472f5d481d1e900758ad7b814e3d6f7ae58830cdc25825bb370ac368&o=', 0),
(7, 1, 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/739595311.jpg?k=d4f5a6f3c9d3ad736911d4be19c1587870d3ec64bb225a02a35477e74aa35b6d&o=', 0),
(8, 1, 'https://cf.bstatic.com/xdata/images/hotel/max500/789417740.jpg?k=5d9dab144ab3a0a10b6759cccdd615e65c088e948c5f148ee59ad71f78032091&o=', 0),
(9, 1, 'https://cf.bstatic.com/xdata/images/hotel/max500/799112456.jpg?k=601637043300231cdc97b1c550c8bd01a14019475a067a1d712bcfcfd87675ea&o=', 0),
(10, 1, 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/789418047.jpg?k=2316c9b84cd5c19a803a098319a9b3beafb7cddb811f51f131b1786bb1bfbae7&o=', 0),
(11, 2, 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=800&q=80', 1),
(12, 3, 'https://images.unsplash.com/photo-1551882547-ff43c63efa5e?auto=format&fit=crop&w=800&q=80', 1),
(13, 4, 'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=800&q=80', 1),
(14, 5, 'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=800&q=80', 1),
(15, 6, 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=800&q=80', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hotels`
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
  `createdAt` datetime DEFAULT NULL,
  `deletedAt` datetime DEFAULT NULL,
  `status` enum('ACTIVE','PENDING_STOP','STOP') DEFAULT 'ACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hotels`
--

INSERT INTO `hotels` (`id`, `partnerId`, `hotelName`, `description`, `rating`, `cityId`, `wardId`, `address`, `createdAt`, `deletedAt`, `status`) VALUES
(1, 2, 'Skeeyzi Farm Stay', 'Grand Palace Hotel là khách sạn 5 sao sang trọng nằm tại trung tâm Hà Nội, cách `Hồ Hoàn Kiếm chỉ 5 phút đi bộ. Với kiến trúc độc đáo kết hợp giữa phong cách Á - Âu, khách sạn mang đến không gian nghỉ dưỡng đẳng cấp với đầy đủ tiện nghi hiện đại.</br>\r\nChúng tôi tự hào với 200 phòng nghỉ được thiết kế tinh tế, nhà hàng phục vụ ẩm thực đa quốc gia, hồ bơi vô cực trên tầng thượng, spa cao cấp và phòng gym hiện đại.\\n\r\nĐội ngũ nhân viên chuyên nghiệp luôn sẵn sàng phục vụ 24/7 để mang đến trải nghiệm\r\ntuyệt vời nhất cho quý khách.`\r\n\r\n', 4.5, 1, 1, '123 Bến Nghé', '2026-03-25 10:49:07', NULL, 'ACTIVE'),
(2, 2, 'Saigon Riverside', NULL, 4.8, 1, 2, '45 Đa Kao', '2026-03-25 10:49:07', NULL, 'ACTIVE'),
(3, 3, 'Hanoi Old Quarter', NULL, 4.2, 2, 3, '12 Hàng Đào', '2026-03-25 10:49:07', NULL, 'ACTIVE'),
(4, 3, 'Tràng Tiền Luxury', NULL, 5, 2, 4, '88 Tràng Tiền', '2026-03-25 10:49:07', NULL, 'ACTIVE'),
(5, 4, 'Danang Beach Hotel', NULL, 4.7, 3, 5, '01 Hải Châu', '2026-03-25 10:49:07', NULL, 'ACTIVE'),
(6, 4, 'Green Hill Dalat', NULL, 4, 4, 6, '10 Phường 1', '2026-03-25 10:49:07', NULL, 'ACTIVE'),
(7, 2, 'Nha Trang Oasis', NULL, 4.6, 5, 7, '20 Lộc Thọ', '2026-03-25 10:49:07', NULL, 'ACTIVE'),
(8, 3, 'Vung Tau Corner', NULL, 3.9, 6, 8, '15 Thắng Tam', '2026-03-25 10:49:07', NULL, 'ACTIVE'),
(9, 4, 'Hue Ancient House', NULL, 4.3, 7, 9, '05 Phú Hội', '2026-03-25 10:49:07', NULL, 'ACTIVE'),
(10, 2, 'Phú Quốc Sunset', NULL, 4.9, 8, 10, '99 Dương Đông', '2026-03-25 10:49:07', NULL, 'ACTIVE');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `partners`
--

CREATE TABLE `partners` (
  `userId` int(11) NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `taxCode` varchar(100) NOT NULL,
  `businessLicense` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`businessLicense`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `partners`
--

INSERT INTO `partners` (`userId`, `companyName`, `taxCode`, `businessLicense`) VALUES
(2, 'Skeeyzi Farm Group', '0123456789', '{\"license\": \"L001\", \"issued\": \"2025-01-01\"}'),
(3, 'Trần Gia Hospitality', '0987654321', '{\"license\": \"L002\", \"issued\": \"2025-02-01\"}'),
(4, 'Hoàng Lê Travel', '0112233445', '{\"license\": \"L003\", \"issued\": \"2025-03-01\"}');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
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
-- Đang đổ dữ liệu cho bảng `payments`
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
(10, 10, 1800000.00, 'VNPAY', 'PAID', NULL, '2026-03-25 03:49:07', '2026-03-25 03:49:07'),
(11, 16, 1454400.00, 'MOMO', 'PENDING', NULL, '2026-04-01 09:43:57', '0000-00-00 00:00:00'),
(12, 17, 1454400.00, 'MOMO', 'PENDING', NULL, '2026-04-01 09:55:01', '0000-00-00 00:00:00'),
(13, 18, 3272400.00, 'MOMO', 'PENDING', NULL, '2026-04-01 10:18:22', '0000-00-00 00:00:00'),
(14, 20, 3272400.00, 'MOMO', 'PENDING', NULL, '2026-04-01 10:24:05', '0000-00-00 00:00:00'),
(36, 38, 1090800.00, 'VNPAY', 'PAID', NULL, '2026-04-01 15:51:48', '0000-00-00 00:00:00'),
(37, 39, 1090800.00, 'VNPAY', 'PAID', NULL, '2026-04-01 15:55:36', '0000-00-00 00:00:00'),
(38, 40, 45450000.00, 'MOMO', 'PAID', NULL, '2026-04-01 17:19:49', '0000-00-00 00:00:00'),
(39, 41, 999900.00, 'MOMO', 'PAID', NULL, '2026-04-03 00:27:26', '0000-00-00 00:00:00'),
(40, 42, 727200.00, 'MOMO', 'PAID', NULL, '2026-04-03 01:54:18', '0000-00-00 00:00:00'),
(41, 43, 2302800.00, 'MOMO', 'PAID', NULL, '2026-04-03 04:11:46', '0000-00-00 00:00:00'),
(42, 44, 363600.00, 'VNPAY', 'PAID', NULL, '2026-04-03 14:14:02', '0000-00-00 00:00:00'),
(43, 45, 363600.00, 'MOMO', 'PAID', NULL, '2026-04-03 14:28:15', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `physicalrooms`
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
-- Đang đổ dữ liệu cho bảng `physicalrooms`
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
(12, 11, '108', 1, 'AVAILABLE', NULL),
(13, 8, '202', 2, 'AVAILABLE', NULL),
(14, 2, '203', 2, 'AVAILABLE', NULL),
(15, 2, '204', 2, 'AVAILABLE', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `bookingDetailId` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hotelId` int(11) NOT NULL DEFAULT 1,
  `replyContent` text DEFAULT NULL,
  `replyDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `userId`, `bookingDetailId`, `rating`, `content`, `createdAt`, `hotelId`, `replyContent`, `replyDate`) VALUES
(1, 5, 1, 5, 'Dịch vụ tuyệt vời!', '2026-03-25 03:49:07', 1, NULL, NULL),
(2, 6, 2, 4, 'Phòng hơi nhỏ', '2026-03-25 03:49:07', 1, NULL, NULL),
(3, 7, 3, 5, 'View đẹp xuất sắc', '2026-03-25 03:49:07', 1, NULL, NULL),
(4, 8, 4, 5, 'Khách sạn rất sang trọng', '2026-03-25 03:49:07', 1, NULL, NULL),
(5, 9, 5, 2, 'Hủy phòng do công việc nhưng hỗ trợ kém', '2026-03-25 03:49:07', 1, NULL, NULL),
(6, 10, 6, 4, 'Thoáng mát, sạch sẽ', '2026-03-25 03:49:07', 1, NULL, NULL),
(7, 5, 7, 3, 'Giá hơi cao so với chất lượng', '2026-03-25 03:49:07', 1, NULL, NULL),
(8, 6, 8, 5, 'Sẽ quay lại lần sau', '2026-03-25 03:49:07', 1, NULL, NULL),
(9, 7, 9, 4, 'Địa điểm thuận tiện', '2026-03-25 03:49:07', 1, NULL, NULL),
(10, 8, 10, 5, 'Nhân viên thân thiện', '2026-03-25 03:49:07', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roomamenities`
--

CREATE TABLE `roomamenities` (
  `roomConfigId` int(11) NOT NULL,
  `amenityId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roomamenities`
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
-- Cấu trúc bảng cho bảng `roomconfigurations`
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
-- Đang đổ dữ liệu cho bảng `roomconfigurations`
--

INSERT INTO `roomconfigurations` (`id`, `hotelId`, `roomTypeId`, `basePrice`, `area`, `maxPeople`, `createdAt`, `deleted_at`) VALUES
(1, 1, 1, 50000000.00, 28, 2, '2026-03-29 03:07:13', NULL),
(2, 1, 3, 1200000.00, 40, 2, '2026-03-26 14:48:47', NULL),
(3, 2, 2, 800000.00, 30, 2, '2026-03-25 03:49:07', NULL),
(4, 3, 1, 600000.00, 20, 2, '2026-03-25 03:49:07', NULL),
(5, 4, 4, 3000000.00, 80, 2, '2026-03-25 03:49:07', NULL),
(6, 5, 3, 1500000.00, 50, 2, '2026-03-25 03:49:07', NULL),
(7, 6, 8, 2000000.00, 45, 2, '2026-03-25 03:49:07', NULL),
(8, 7, 2, 900000.00, 35, 2, '2026-03-25 03:49:07', NULL),
(9, 8, 1, 450000.00, 22, 1, '2026-03-25 03:49:07', NULL),
(10, 9, 5, 1800000.00, 60, 4, '2026-03-25 03:49:07', NULL),
(11, 1, 10, 500000.00, 20, 2, '2026-04-03 15:14:02', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roomconfiguration_bedtypes`
--

CREATE TABLE `roomconfiguration_bedtypes` (
  `roomConfigId` int(11) NOT NULL,
  `bedTypeId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roomconfiguration_bedtypes`
--

INSERT INTO `roomconfiguration_bedtypes` (`roomConfigId`, `bedTypeId`, `quantity`) VALUES
(1, 1, 2),
(1, 6, 1),
(1, 8, 1),
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
-- Cấu trúc bảng cho bảng `roomimages`
--

CREATE TABLE `roomimages` (
  `id` int(11) NOT NULL,
  `roomConfigId` int(11) NOT NULL,
  `imageUrl` varchar(255) NOT NULL,
  `isPrimary` tinyint(1) DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roomimages`
--

INSERT INTO `roomimages` (`id`, `roomConfigId`, `imageUrl`, `isPrimary`, `createdAt`) VALUES
(1, 1, 'https://pix8.agoda.net/hotelImages/55765781/-1/7c03bbc26bceac0fc5b5758905d0d0bd.jpg?ce=0&s=1024x', 0, '2026-03-28 07:41:32'),
(2, 1, 'https://pix8.agoda.net/property/55765781/879095298/3b2f795b75f82da854ceb8ffb4022ae8.jpeg?ce=0&s=1024x', 0, '2026-03-28 07:41:32'),
(3, 1, 'https://pix8.agoda.net/hotelImages/55765781/-1/e7548b9bcff6eba38dfd904261d9ce0f.jpg?ce=0&s=1024x', 0, '2026-03-28 07:41:57'),
(4, 1, 'https://pix8.agoda.net/property/55765781/878970973/9a5fa45e21ed2545dc3758d49f4bbe57.jpeg?ce=0&s=1024x', 0, '2026-03-28 07:41:57'),
(5, 1, 'https://pix8.agoda.net/hotelImages/55765781/-1/8a817e6324d74c39e0025bbddf39b343.jpg?ce=0&s=1024x', 0, '2026-03-28 07:42:11'),
(6, 2, 'https://pix8.agoda.net/property/50513627/879449935/6a56d3a219f36c17ba94dd263726f9c5.jpeg?ce=2&s=1024x', 0, '2026-03-28 07:44:20'),
(7, 2, 'https://pix8.agoda.net/hotelImages/50513627/804821761/4c1f61a44b628e1f434d69ded197ebf9.jpg?ce=0&s=1024x', 0, '2026-03-28 07:44:20'),
(8, 2, 'https://q-xx.bstatic.com/xdata/images/hotel/840x460/720763615.jpg?k=6bd2f12335c45c5b97ad43fad8293bfb2bc72a8bc8932315b3941c42171f0da1&o=&s=1024x', 0, '2026-03-28 07:44:38'),
(9, 2, 'https://pix8.agoda.net/property/50513627/879449935/13463307497ae2113beded4697827668.jpeg?ce=2&s=1024x', 0, '2026-03-28 07:44:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roominventory`
--

CREATE TABLE `roominventory` (
  `id` int(11) NOT NULL,
  `roomConfigId` int(11) NOT NULL,
  `date` date NOT NULL,
  `availableCount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roominventory`
--

INSERT INTO `roominventory` (`id`, `roomConfigId`, `date`, `availableCount`) VALUES
(1, 1, '2026-04-01', 10),
(2, 1, '2026-04-02', 8),
(3, 2, '2026-04-01', 5),
(4, 3, '2026-04-01', 8),
(5, 4, '2026-04-01', 12),
(6, 5, '2026-04-01', 2),
(7, 6, '2026-04-01', 6),
(8, 7, '2026-04-01', 4),
(9, 8, '2026-04-01', 10),
(10, 9, '2026-04-01', 15);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roomprices`
--

CREATE TABLE `roomprices` (
  `id` int(11) NOT NULL,
  `roomConfigId` int(11) NOT NULL,
  `date` date NOT NULL,
  `price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roomprices`
--

INSERT INTO `roomprices` (`id`, `roomConfigId`, `date`, `price`) VALUES
(1, 1, '2026-04-01', 500000.00),
(2, 1, '2026-04-02', 550000.00),
(3, 2, '2026-04-01', 1200000.00),
(4, 3, '2026-04-01', 800000.00),
(5, 4, '2026-04-01', 600000.00),
(6, 5, '2026-04-01', 3000000.00),
(7, 6, '2026-04-01', 1500000.00),
(8, 7, '2026-04-01', 2000000.00),
(9, 8, '2026-04-01', 900000.00),
(10, 9, '2026-04-01', 450000.00),
(11, 1, '2026-04-03', 50000000.00),
(12, 2, '2026-04-03', 1200000.00),
(13, 11, '2026-04-03', 500000.00),
(14, 1, '2026-04-03', 50000000.00),
(15, 2, '2026-04-03', 1200000.00),
(16, 11, '2026-04-03', 500000.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roomtypes`
--

CREATE TABLE `roomtypes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `roomtypes`
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
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
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
  `role` enum('ADMIN','CUSTOMER','PARTNER','STAFF') DEFAULT 'CUSTOMER',
  `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `hotel_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `fullName`, `email`, `password`, `google_id`, `phone`, `status`, `address`, `gender`, `birthDate`, `avatarUrl`, `cityId`, `wardId`, `createdAt`, `deletedAt`, `role`, `updatedAt`, `hotel_id`, `created_by`) VALUES
(1, 'Admin Skeeyzi', 'admin@skeeyzi.com', 'pass_hash', NULL, '0901234567', 'ACTIVE', NULL, NULL, NULL, NULL, 1, 1, '2026-03-25 03:49:07', '0000-00-00 00:00:00', 'CUSTOMER', NULL, NULL, NULL),
(2, 'Nguyễn Văn Đối Tác', 'partner1@gmail.com', 'pass_hash', NULL, '0901234568', 'ACTIVE', NULL, NULL, NULL, NULL, 1, 2, '2026-03-25 03:49:07', '0000-00-00 00:00:00', 'CUSTOMER', NULL, NULL, NULL),
(3, 'Trần Thị Chủ Căn', 'partner2@gmail.com', 'pass_hash', NULL, '0901234569', 'ACTIVE', NULL, NULL, NULL, NULL, 2, 3, '2026-03-25 03:49:07', '0000-00-00 00:00:00', 'CUSTOMER', NULL, NULL, NULL),
(4, 'Lê Hoàng Partner', 'partner3@gmail.com', 'pass_hash', NULL, '0901234570', 'ACTIVE', NULL, NULL, NULL, NULL, 3, 5, '2026-03-25 03:49:07', '0000-00-00 00:00:00', 'CUSTOMER', NULL, NULL, NULL),
(5, 'Khách Hàng A', 'customer1@gmail.com', 'pass_hash', NULL, '0901234571', 'ACTIVE', NULL, NULL, NULL, NULL, 1, 1, '2026-03-25 03:49:07', '0000-00-00 00:00:00', 'CUSTOMER', NULL, NULL, NULL),
(6, 'Khách Hàng B', 'customer2@gmail.com', 'pass_hash', NULL, '0901234572', 'ACTIVE', NULL, NULL, NULL, NULL, 2, 4, '2026-03-25 03:49:07', '0000-00-00 00:00:00', 'CUSTOMER', NULL, NULL, NULL),
(7, 'Khách Hàng C', 'customer3@gmail.com', 'pass_hash', NULL, '0901234573', 'ACTIVE', NULL, NULL, NULL, NULL, 4, 6, '2026-03-25 03:49:07', '0000-00-00 00:00:00', 'CUSTOMER', NULL, NULL, NULL),
(8, 'Khách Hàng D', 'customer4@gmail.com', 'pass_hash', NULL, '0901234574', 'ACTIVE', NULL, NULL, NULL, NULL, 5, 7, '2026-03-25 03:49:07', '0000-00-00 00:00:00', 'CUSTOMER', NULL, NULL, NULL),
(9, 'Khách Hàng E', 'customer5@gmail.com', 'pass_hash', NULL, '0901234575', 'ACTIVE', NULL, NULL, NULL, NULL, 6, 8, '2026-03-25 03:49:07', '0000-00-00 00:00:00', 'CUSTOMER', NULL, NULL, NULL),
(10, 'Khách Hàng F', 'customer6@gmail.com', 'pass_hash', NULL, '0901234576', 'ACTIVE', NULL, NULL, NULL, NULL, 1, 1, '2026-03-25 03:49:07', '0000-00-00 00:00:00', 'CUSTOMER', NULL, NULL, NULL),
(11, 'Quang Vinhh', 'buigiaquangvinh@gmail.com', '$2y$10$ufh2didGSmaavOEvXkY6O.Ep3Nj81a.PYt8H3souhFQWW/rKeelTC', NULL, '0937354532', 'ACTIVE', NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-03 04:26:57', NULL, 'CUSTOMER', '2026-04-03 04:26:57', NULL, NULL),
(12, 'Gia Vinh', 'vinh@gmail.com', '$2y$10$vjL20X6hkNer56.dEXVDLuJnbCqBQBILbzaXD5Hk5k0lENfsHEt3.', NULL, NULL, 'ACTIVE', NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-03 15:02:18', NULL, 'PARTNER', NULL, NULL, NULL),
(13, 'Quang Vinh Bùi Gia', 'embimatdep@gmail.com', '123123', '108667603443721296343', '0947837200', 'ACTIVE', 'sssss', NULL, '2026-04-28', 'https://lh3.googleusercontent.com/a/ACg8ocKzhgeMcLAdO7dpCTM0hw3zPwt6tKlrb6OOSu7DnPb0l4nELFp6=s96-c', 2, 3, '2026-04-03 07:51:46', NULL, 'CUSTOMER', '2026-04-03 07:51:46', NULL, NULL),
(14, 'u uees', 'vanan@gmail.com', '$2y$10$Iw18p7Z3t1P1a2A86lNPa.c4duTfOrj5toaN3AzIfA9QMZM3yvNj6', NULL, NULL, 'ACTIVE', NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-03 02:59:59', NULL, 'CUSTOMER', NULL, NULL, NULL),
(15, 'Em Ơi', 'embidep@gmail.com', '$2y$10$yctpVAxPeApuv2rt4EraDOo6Q95cXN1rN1/sS./oHlmLFShuPdvkK', NULL, '0358955915', 'ACTIVE', NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-03 15:18:15', NULL, 'STAFF', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
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
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `quantity`, `type`, `amount`, `condition`, `startDate`, `endDate`, `hotelId`) VALUES
(1, 'WELCOME', 100, 'PERCENT', 10.00, 0.00, '2026-01-01', '2026-12-31', NULL),
(2, 'SALE50', 50, 'FIXED', 50000.00, 500000.00, '2026-03-01', '2026-04-01', NULL),
(3, 'VIP30', 10, 'PERCENT', 30.00, 2000000.00, '2026-01-01', '2026-12-31', NULL),
(4, 'SKEEYZI', 20, 'FIXED', 100000.00, 1000000.00, '2026-03-01', '2026-06-01', NULL),
(5, 'SUMMER', 200, 'PERCENT', 15.00, 500000.00, '2026-06-01', '2026-08-31', NULL),
(6, 'FREESHIP', 100, 'FIXED', 20000.00, 0.00, '2026-01-01', '2026-12-31', NULL),
(7, 'OFFER10', 50, 'PERCENT', 10.00, 200000.00, '2026-01-01', '2026-03-31', NULL),
(8, 'LUCKY', 1, 'FIXED', 500000.00, 1000000.00, '2026-04-01', '2026-04-30', NULL),
(9, 'MOMO10', 100, 'FIXED', 10000.00, 100000.00, '2026-01-01', '2026-12-31', NULL),
(10, 'VNPAY20', 50, 'PERCENT', 20.00, 1000000.00, '2026-01-01', '2026-12-31', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wards`
--

CREATE TABLE `wards` (
  `id` int(11) NOT NULL,
  `cityId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `wards`
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
(10, 8, 'Dương Đông');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `auditlogs`
--
ALTER TABLE `auditlogs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `bedtypes`
--
ALTER TABLE `bedtypes`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `bookingdetails`
--
ALTER TABLE `bookingdetails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookingId` (`bookingId`),
  ADD KEY `roomConfigId` (`roomConfigId`),
  ADD KEY `physicalRoomId` (`physicalRoomId`);

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `hotelimages`
--
ALTER TABLE `hotelimages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotelId` (`hotelId`);

--
-- Chỉ mục cho bảng `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `partnerId` (`partnerId`),
  ADD KEY `cityId` (`cityId`),
  ADD KEY `wardId` (`wardId`);

--
-- Chỉ mục cho bảng `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `companyName` (`companyName`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookingId` (`bookingId`);

--
-- Chỉ mục cho bảng `physicalrooms`
--
ALTER TABLE `physicalrooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roomConfigId` (`roomConfigId`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookingDetailId` (`bookingDetailId`),
  ADD KEY `userId` (`userId`);

--
-- Chỉ mục cho bảng `roomamenities`
--
ALTER TABLE `roomamenities`
  ADD PRIMARY KEY (`roomConfigId`,`amenityId`),
  ADD KEY `amenityId` (`amenityId`);

--
-- Chỉ mục cho bảng `roomconfigurations`
--
ALTER TABLE `roomconfigurations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotelId` (`hotelId`),
  ADD KEY `roomTypeId` (`roomTypeId`);

--
-- Chỉ mục cho bảng `roomconfiguration_bedtypes`
--
ALTER TABLE `roomconfiguration_bedtypes`
  ADD PRIMARY KEY (`roomConfigId`,`bedTypeId`),
  ADD KEY `bedTypeId` (`bedTypeId`);

--
-- Chỉ mục cho bảng `roomimages`
--
ALTER TABLE `roomimages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roomConfigId` (`roomConfigId`);

--
-- Chỉ mục cho bảng `roominventory`
--
ALTER TABLE `roominventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roomConfigId` (`roomConfigId`);

--
-- Chỉ mục cho bảng `roomprices`
--
ALTER TABLE `roomprices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roomConfigId` (`roomConfigId`);

--
-- Chỉ mục cho bảng `roomtypes`
--
ALTER TABLE `roomtypes`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `google_id` (`google_id`),
  ADD KEY `cityId` (`cityId`),
  ADD KEY `wardId` (`wardId`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cityId` (`cityId`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `auditlogs`
--
ALTER TABLE `auditlogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `bedtypes`
--
ALTER TABLE `bedtypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `bookingdetails`
--
ALTER TABLE `bookingdetails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT cho bảng `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `hotelimages`
--
ALTER TABLE `hotelimages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT cho bảng `physicalrooms`
--
ALTER TABLE `physicalrooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `roomconfigurations`
--
ALTER TABLE `roomconfigurations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `roomimages`
--
ALTER TABLE `roomimages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `roominventory`
--
ALTER TABLE `roominventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `roomprices`
--
ALTER TABLE `roomprices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `roomtypes`
--
ALTER TABLE `roomtypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `wards`
--
ALTER TABLE `wards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookingdetails`
--
ALTER TABLE `bookingdetails`
  ADD CONSTRAINT `bookingdetails_ibfk_1` FOREIGN KEY (`bookingId`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `bookingdetails_ibfk_2` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`),
  ADD CONSTRAINT `bookingdetails_ibfk_3` FOREIGN KEY (`physicalRoomId`) REFERENCES `physicalrooms` (`id`);

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `hotelimages`
--
ALTER TABLE `hotelimages`
  ADD CONSTRAINT `hotelimages_ibfk_1` FOREIGN KEY (`hotelId`) REFERENCES `hotels` (`id`);

--
-- Các ràng buộc cho bảng `hotels`
--
ALTER TABLE `hotels`
  ADD CONSTRAINT `hotels_ibfk_1` FOREIGN KEY (`partnerId`) REFERENCES `partners` (`userId`),
  ADD CONSTRAINT `hotels_ibfk_2` FOREIGN KEY (`cityId`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `hotels_ibfk_3` FOREIGN KEY (`wardId`) REFERENCES `wards` (`id`);

--
-- Các ràng buộc cho bảng `partners`
--
ALTER TABLE `partners`
  ADD CONSTRAINT `partners_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`bookingId`) REFERENCES `bookings` (`id`);

--
-- Các ràng buộc cho bảng `physicalrooms`
--
ALTER TABLE `physicalrooms`
  ADD CONSTRAINT `physicalrooms_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`);

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`bookingDetailId`) REFERENCES `bookingdetails` (`id`);

--
-- Các ràng buộc cho bảng `roomamenities`
--
ALTER TABLE `roomamenities`
  ADD CONSTRAINT `roomamenities_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`),
  ADD CONSTRAINT `roomamenities_ibfk_2` FOREIGN KEY (`amenityId`) REFERENCES `amenities` (`id`);

--
-- Các ràng buộc cho bảng `roomconfigurations`
--
ALTER TABLE `roomconfigurations`
  ADD CONSTRAINT `roomconfigurations_ibfk_1` FOREIGN KEY (`hotelId`) REFERENCES `hotels` (`id`),
  ADD CONSTRAINT `roomconfigurations_ibfk_2` FOREIGN KEY (`roomTypeId`) REFERENCES `roomtypes` (`id`);

--
-- Các ràng buộc cho bảng `roomconfiguration_bedtypes`
--
ALTER TABLE `roomconfiguration_bedtypes`
  ADD CONSTRAINT `roomconfiguration_bedtypes_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`),
  ADD CONSTRAINT `roomconfiguration_bedtypes_ibfk_2` FOREIGN KEY (`bedTypeId`) REFERENCES `bedtypes` (`id`);

--
-- Các ràng buộc cho bảng `roomimages`
--
ALTER TABLE `roomimages`
  ADD CONSTRAINT `roomimages_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`);

--
-- Các ràng buộc cho bảng `roominventory`
--
ALTER TABLE `roominventory`
  ADD CONSTRAINT `roominventory_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`);

--
-- Các ràng buộc cho bảng `roomprices`
--
ALTER TABLE `roomprices`
  ADD CONSTRAINT `roomprices_ibfk_1` FOREIGN KEY (`roomConfigId`) REFERENCES `roomconfigurations` (`id`);

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`cityId`) REFERENCES `cities` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`wardId`) REFERENCES `wards` (`id`);

--
-- Các ràng buộc cho bảng `wards`
--
ALTER TABLE `wards`
  ADD CONSTRAINT `wards_ibfk_1` FOREIGN KEY (`cityId`) REFERENCES `cities` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
