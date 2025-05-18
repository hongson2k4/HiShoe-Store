-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th5 18, 2025 lúc 05:38 PM
-- Phiên bản máy phục vụ: 8.4.3
-- Phiên bản PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `hishoe_duydat`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Adiddas', 'Giày adiddas', 1, NULL, '2025-03-14 08:38:57'),
(2, 'Nike', 'Giày Nike', 1, NULL, '2025-03-14 08:39:00'),
(3, 'Ananas', 'Giày Annas', 1, NULL, '2025-03-14 08:39:04'),
(4, 'Yonex', 'Giày Yonex', 1, NULL, '2025-03-14 08:39:06'),
(5, 'Lining', 'Giày Lining', 1, NULL, '2025-03-14 08:39:09'),
(6, 'Van', 'Giày Van', 1, NULL, '2025-03-14 08:39:13'),
(7, 'Puma', 'Giày Puma', 1, '2025-03-11 07:03:43', '2025-03-11 07:03:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Giày Chạy bộ', 'Giày thể chuyên nghiệp dành cho runner\r\n', 0, NULL, NULL),
(2, 'Giày Mang hằng ngày', 'Những đôi giày basic dễ phối mặc', 0, NULL, NULL),
(3, 'Giày leo núi', 'Dành cho dân leo núi', 0, NULL, NULL),
(4, 'Giày đi bộ', 'Dành cho người không chuyên thích đi lại nhẹ nhàng', 0, NULL, NULL),
(5, 'Giày trẻ em', 'Giày dành cho trẻ em', 0, NULL, NULL),
(6, 'Các loại giày khác', 'Tổng hợp các loại giày của shop', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `colors`
--

CREATE TABLE `colors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `colors`
--

INSERT INTO `colors` (`id`, `name`, `code`) VALUES
(1, 'Hồng nhạt', '#fb8ec8'),
(2, 'Trắng', '#ffffff');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `likes`
--

CREATE TABLE `likes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_02_11_051559_create_categories_table', 1),
(6, '2025_02_11_051620_create_brands_table', 1),
(7, '2025_02_11_051816_create_products_table', 1),
(8, '2025_02_11_051827_create_size_table', 1),
(9, '2025_02_11_051839_create_color_table', 1),
(10, '2025_02_11_052001_create_products_variants_table', 1),
(11, '2025_02_11_053800_create_carts_table', 1),
(12, '2025_02_11_053943_create_vouchers_table', 1),
(13, '2025_02_11_053952_create_orders_table', 1),
(14, '2025_02_11_054008_create_order_details_table', 1),
(15, '2025_02_11_054020_create_payments_table', 1),
(16, '2025_02_11_054039_create_reviews_table', 1),
(18, '2025_02_14_063230_alter_users_table', 1),
(19, '2025_02_13_131636_add_users_account', 2),
(20, '2025_03_01_135837_create_user_history_changes_table', 3),
(21, '2025_04_04_180339_create_product_comments_table', 4),
(22, '2025_03_18_222240_create_likes_table', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_price` int NOT NULL,
  `status` int NOT NULL,
  `shipping_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `voucher_id` bigint UNSIGNED DEFAULT NULL,
  `is_reviewed` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_reasons` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `product_name_id` bigint UNSIGNED DEFAULT NULL,
  `needs_support` tinyint(1) DEFAULT '0',
  `needs_refunded` tinyint(1) NOT NULL DEFAULT '0',
  `order_check` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `is_refunded` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `shipping_address`, `voucher_id`, `is_reviewed`, `created_at`, `updated_at`, `customer_reasons`, `product_name_id`, `needs_support`, `needs_refunded`, `order_check`, `is_refunded`) VALUES
(16, 2, 1200000, 0, 'sậijijaijia', NULL, 0, '2025-05-04 22:47:19', '2025-05-04 22:47:19', NULL, NULL, 0, 0, '', 0),
(17, 2, 1200000, 1, 'tưttwtwtwtwtw', NULL, 0, '2025-05-04 22:50:13', '2025-05-04 22:50:13', NULL, NULL, 0, 0, '', 0),
(19, 2, 1200000, 7, 'fghjkl', NULL, 1, '2025-05-04 22:52:26', '2025-05-04 23:17:51', NULL, NULL, 0, 0, '', 0),
(20, 2, 1200000, 7, 'fgggdgdgdgdg', NULL, 1, '2025-05-04 22:56:51', '2025-05-04 23:17:44', NULL, NULL, 0, 0, '', 0),
(21, 7, 1200000, 1, 'nhhshshshshshshhs', NULL, 0, '2025-05-05 01:25:57', '2025-05-05 01:25:57', NULL, NULL, 0, 0, '', 0),
(23, 7, 1200000, 1, 'dfghjsds', NULL, 0, '2025-05-05 01:54:24', '2025-05-05 01:54:25', NULL, NULL, 0, 0, '', 0),
(25, 7, 1200000, 2, 'âs', NULL, 0, '2025-05-05 01:56:22', '2025-05-05 01:57:49', NULL, NULL, 0, 0, '', 0),
(28, 7, 1200000, 1, 'hihihihihi', NULL, 0, '2025-05-11 07:17:50', '2025-05-11 07:17:51', NULL, NULL, 0, 0, '', 0),
(29, 7, 1200000, 1, 'joojojo', NULL, 0, '2025-05-11 07:19:07', '2025-05-11 07:21:44', NULL, NULL, 0, 0, '', 0),
(1226, 8, 15000, 6, '12 cầu giấy', NULL, 0, '2025-05-16 04:18:06', '2025-05-17 00:35:42', NULL, 1, 0, 0, 'C0FF4ZUPGR', 1),
(1227, 8, 165000, 4, '12 cầu giấy', NULL, 0, '2025-05-16 04:26:33', '2025-05-17 00:47:33', NULL, 1, 0, 0, 'ARTQJMVQPC', 0),
(1228, 8, 45000, 1, '12 cầu giấy', NULL, 0, '2025-05-16 04:34:08', '2025-05-16 04:34:09', NULL, 1, 0, 0, 'TMO7223KNB', 0),
(1230, 8, 30000, 1, '12 cầu giấy', NULL, 0, '2025-05-16 10:08:33', '2025-05-16 10:09:31', NULL, NULL, 0, 0, 'I1U5HDD0VV', 0),
(1233, 8, 45000, 1, '12 cầu giấy', NULL, 0, '2025-05-18 09:38:10', '2025-05-18 09:38:11', NULL, NULL, 0, 0, 'BF47YJSWRM', 0),
(1234, 9, 15000, 1, '12 cầu giấy', NULL, 0, '2025-05-18 09:45:56', '2025-05-18 09:45:57', NULL, NULL, 0, 0, 'Y2W61DQGQA', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_variant_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(17, 1226, 1, 1, 15000, '2025-05-16 04:18:07', '2025-05-16 04:18:07'),
(18, 1227, 1, 11, 15000, '2025-05-16 04:26:34', '2025-05-16 04:26:34'),
(19, 1228, 1, 3, 15000, '2025-05-16 04:34:09', '2025-05-16 04:34:09'),
(20, 1230, 1, 2, 15000, '2025-05-16 10:09:31', '2025-05-16 10:09:31'),
(21, 1233, 1, 3, 15000, '2025-05-18 09:38:11', '2025-05-18 09:38:11'),
(22, 1234, 1, 1, 15000, '2025-05-18 09:45:57', '2025-05-18 09:45:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_item_histories`
--

CREATE TABLE `order_item_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `order_item_histories`
--

INSERT INTO `order_item_histories` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(121, 57, 1, 1, 216666.67, '2025-03-10 23:34:01', '2025-03-11 16:34:51'),
(122, 60, 2, 1, 445000.00, '2025-03-14 23:34:01', '2025-03-12 20:25:28'),
(123, 61, 3, 1, 320000.00, '2025-03-11 23:34:01', '2025-03-27 01:39:19'),
(124, 62, 4, 4, 275000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(125, 64, 5, 5, 190000.00, '2025-03-11 23:34:01', '2025-03-29 19:26:49'),
(126, 66, 6, 2, 375000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(127, 67, 7, 1, 890000.00, '2025-03-11 23:34:01', '2025-03-18 18:32:58'),
(128, 68, 8, 3, 106666.67, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(129, 69, 1, 2, 550000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(130, 71, 2, 1, 950000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(131, 76, 3, 4, 205000.00, '2023-03-11 23:34:01', '2025-03-11 23:34:01'),
(132, 78, 4, 2, 320000.00, '2025-03-11 23:34:01', '2025-03-23 17:09:16'),
(133, 79, 5, 3, 293333.33, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(134, 80, 6, 5, 180000.00, '2025-05-19 23:34:01', '2025-03-11 23:34:01'),
(135, 81, 7, 6, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(136, 86, 8, 7, 85714.29, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(137, 87, 1, 2, 375000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(138, 88, 2, 4, 205000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(139, 89, 3, 1, 920000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(140, 90, 4, 10, 64000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(141, 81, 1, 2, 550000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(142, 81, 2, 1, 950000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(143, 81, 3, 3, 320000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(144, 81, 1, 2, 550000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(145, 81, 2, 1, 950000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(146, 81, 3, 3, 320000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(147, 82, 4, 2, 275000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(148, 82, 5, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(149, 82, 6, 3, 183333.33, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(150, 82, 7, 1, 150000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(151, 83, 1, 1, 350000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(152, 83, 3, 2, 225000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(153, 83, 5, 1, 150000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(154, 85, 2, 3, 266666.67, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(155, 85, 4, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(156, 86, 1, 1, 150000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(157, 86, 3, 2, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(158, 86, 5, 1, 150000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(159, 86, 7, 1, 150000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(160, 87, 2, 2, 300000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(161, 87, 4, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(162, 87, 6, 2, 175000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(163, 88, 1, 3, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(164, 88, 3, 1, 260000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(165, 89, 2, 1, 150000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(166, 89, 4, 1, 170000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(167, 89, 6, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(168, 89, 1, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(169, 89, 3, 1, 230000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(170, 90, 5, 2, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(171, 90, 7, 1, 240000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(172, 90, 2, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(173, 92, 1, 1, 180000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(174, 92, 3, 2, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(175, 92, 5, 1, 170000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(176, 92, 7, 1, 170000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(177, 93, 2, 3, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(178, 93, 4, 1, 170000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(179, 93, 6, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(180, 94, 1, 2, 300000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(181, 94, 3, 1, 290000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(182, 95, 2, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-23 19:29:22'),
(183, 95, 4, 1, 150000.00, '2025-03-11 23:34:01', '2025-03-23 19:29:22'),
(184, 95, 6, 1, 250000.00, '2025-03-11 23:34:01', '2025-03-23 19:29:22'),
(185, 95, 8, 1, 220000.00, '2025-03-11 23:34:01', '2025-03-23 19:29:22'),
(186, 96, 1, 2, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(187, 96, 3, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(188, 96, 5, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(189, 97, 2, 3, 233333.33, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(190, 97, 4, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(191, 99, 1, 1, 120000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(192, 99, 3, 1, 150000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(193, 99, 5, 1, 180000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(194, 99, 7, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(195, 99, 2, 1, 170000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(196, 100, 4, 2, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(197, 100, 6, 1, 240000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(198, 100, 8, 1, 200000.00, '2025-03-11 23:34:01', '2025-03-11 23:34:01'),
(219, 201, 1, 2, 550000.00, '2025-04-01 01:00:00', '2025-04-01 01:00:00'),
(220, 201, 3, 1, 450000.00, '2025-04-01 01:00:00', '2025-04-01 01:00:00'),
(221, 201, 5, 2, 425000.00, '2025-04-01 01:00:00', '2025-04-01 01:00:00'),
(222, 202, 2, 3, 216666.67, '2025-04-01 01:05:00', '2025-04-01 01:05:00'),
(223, 202, 6, 1, 890000.00, '2025-04-01 01:05:00', '2025-04-01 01:05:00'),
(224, 203, 4, 4, 275000.00, '2025-04-01 01:10:00', '2025-04-01 01:10:00'),
(225, 203, 7, 2, 375000.00, '2025-04-01 01:10:00', '2025-04-01 01:10:00'),
(226, 203, 8, 1, 650000.00, '2025-04-01 01:10:00', '2025-04-01 01:10:00'),
(227, 204, 1, 2, 550000.00, '2025-04-01 01:15:00', '2025-04-01 01:15:00'),
(228, 204, 3, 4, 205000.00, '2025-04-01 01:15:00', '2025-04-01 01:15:00'),
(229, 205, 2, 1, 950000.00, '2025-04-01 01:20:00', '2025-04-01 01:20:00'),
(230, 205, 5, 3, 293333.33, '2025-04-01 01:20:00', '2025-04-01 01:20:00'),
(231, 205, 6, 5, 180000.00, '2025-04-01 01:20:00', '2025-04-01 01:20:00'),
(232, 206, 7, 6, 200000.00, '2025-04-01 01:25:00', '2025-04-01 01:25:00'),
(233, 206, 8, 7, 85714.29, '2025-04-01 01:25:00', '2025-04-01 01:25:00'),
(234, 207, 1, 2, 375000.00, '2025-04-01 01:30:00', '2025-04-01 01:30:00'),
(235, 207, 2, 4, 205000.00, '2025-04-01 01:30:00', '2025-04-01 01:30:00'),
(236, 207, 3, 1, 920000.00, '2025-04-01 01:30:00', '2025-04-01 01:30:00'),
(237, 208, 4, 10, 64000.00, '2025-04-01 01:35:00', '2025-04-01 01:35:00'),
(238, 208, 6, 3, 320000.00, '2025-04-01 01:35:00', '2025-04-01 01:35:00'),
(239, 209, 5, 2, 445000.00, '2025-04-01 01:40:00', '2025-04-01 01:40:00'),
(240, 209, 7, 3, 550000.00, '2025-04-01 01:40:00', '2025-04-01 01:40:00'),
(241, 210, 8, 2, 320000.00, '2025-04-01 01:45:00', '2025-04-01 01:45:00'),
(242, 210, 1, 4, 275000.00, '2025-04-01 01:45:00', '2025-04-01 01:45:00'),
(243, 210, 2, 3, 216666.67, '2025-04-01 01:45:00', '2025-04-01 01:45:00'),
(276, 217, 4, 5, 450000.00, '2025-03-30 07:00:00', '2025-03-30 07:00:00'),
(277, 217, 5, 3, 500000.00, '2025-03-30 07:00:00', '2025-03-30 07:00:00'),
(278, 217, 6, 2, 350000.00, '2025-03-30 07:00:00', '2025-03-30 07:00:00'),
(279, 218, 7, 4, 300000.00, '2025-03-30 07:30:00', '2025-03-30 07:30:00'),
(280, 218, 8, 2, 400000.00, '2025-03-30 07:30:00', '2025-03-30 07:30:00'),
(281, 218, 1, 1, 550000.00, '2025-03-30 07:30:00', '2025-03-30 07:30:00'),
(282, 219, 2, 3, 600000.00, '2025-03-30 08:00:00', '2025-03-30 08:00:00'),
(283, 219, 3, 1, 1200000.00, '2025-03-30 08:00:00', '2025-03-30 08:00:00'),
(284, 219, 4, 2, 450000.00, '2025-03-30 08:00:00', '2025-03-30 08:00:00'),
(285, 220, 5, 6, 500000.00, '2025-03-30 08:30:00', '2025-03-30 08:30:00'),
(286, 220, 6, 3, 350000.00, '2025-03-30 08:30:00', '2025-03-30 08:30:00'),
(287, 220, 7, 2, 300000.00, '2025-03-30 08:30:00', '2025-03-30 08:30:00'),
(288, 220, 8, 1, 400000.00, '2025-03-30 08:30:00', '2025-03-30 08:30:00'),
(289, 221, 1, 2, 550000.00, '2025-03-30 09:00:00', '2025-03-30 09:00:00'),
(290, 221, 2, 1, 600000.00, '2025-03-30 09:00:00', '2025-03-30 09:00:00'),
(291, 221, 3, 1, 1200000.00, '2025-03-30 09:00:00', '2025-03-30 09:00:00'),
(292, 222, 4, 3, 450000.00, '2025-03-30 09:30:00', '2025-03-30 09:30:00'),
(293, 222, 5, 2, 500000.00, '2025-03-30 09:30:00', '2025-03-30 09:30:00'),
(294, 222, 6, 1, 350000.00, '2025-03-30 09:30:00', '2025-03-30 09:30:00'),
(295, 223, 7, 5, 300000.00, '2025-03-30 10:00:00', '2025-03-30 10:00:00'),
(296, 223, 8, 3, 400000.00, '2025-03-30 10:00:00', '2025-03-30 10:00:00'),
(297, 223, 1, 2, 550000.00, '2025-03-30 10:00:00', '2025-03-30 10:00:00'),
(298, 223, 2, 1, 600000.00, '2025-03-30 10:00:00', '2025-03-30 10:00:00'),
(299, 216, 1, 3, 550000.00, '2025-03-30 06:34:00', '2025-03-30 06:34:00'),
(300, 216, 2, 2, 600000.00, '2025-03-30 06:34:00', '2025-03-30 06:34:00'),
(301, 216, 3, 1, 1200000.00, '2025-03-30 06:34:00', '2025-03-30 06:34:00'),
(302, 224, 3, 1, 1200000.00, '2025-03-30 10:30:00', '2025-03-30 10:30:00'),
(303, 224, 4, 4, 450000.00, '2025-03-30 10:30:00', '2025-03-30 10:30:00'),
(304, 224, 5, 2, 500000.00, '2025-03-30 10:30:00', '2025-03-30 10:30:00'),
(305, 225, 6, 2, 350000.00, '2025-03-30 11:00:00', '2025-03-30 11:00:00'),
(306, 225, 7, 3, 300000.00, '2025-03-30 11:00:00', '2025-03-30 11:00:00'),
(307, 225, 8, 1, 400000.00, '2025-03-30 11:00:00', '2025-03-30 11:00:00'),
(320, 1226, 1, 1, 15000.00, '2025-05-16 04:18:07', '2025-05-16 04:18:07'),
(321, 1227, 1, 11, 15000.00, '2025-05-16 04:26:34', '2025-05-16 04:26:34'),
(322, 1228, 1, 3, 15000.00, '2025-05-16 04:34:09', '2025-05-16 04:34:09'),
(323, 1230, 1, 2, 15000.00, '2025-05-16 10:09:31', '2025-05-16 10:09:31'),
(324, 1233, 1, 3, 15000.00, '2025-05-18 09:38:11', '2025-05-18 09:38:11'),
(325, 1234, 1, 1, 15000.00, '2025-05-18 09:45:57', '2025-05-18 09:45:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('datndph42403@gmail.com', '$2y$12$URDR04zypOCibgFqxYqa7.y23X.qgw/Gyr55ryRP0SnrTlnQ2O0BK', '2025-05-12 02:39:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` int NOT NULL,
  `payment_status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `amount`, `payment_status`, `created_at`, `updated_at`) VALUES
(14, 16, 'cod', 1200000, 1, '2025-05-04 22:47:19', '2025-05-04 22:47:19'),
(15, 17, 'cod', 1200000, 1, '2025-05-04 22:50:13', '2025-05-04 22:50:13'),
(17, 19, 'cod', 1200000, 1, '2025-05-04 22:52:26', '2025-05-04 22:52:26'),
(18, 20, 'vnpay', 1200000, 1, '2025-05-04 22:56:51', '2025-05-04 22:57:21'),
(19, 21, 'cod', 1200000, 1, '2025-05-05 01:25:57', '2025-05-05 01:25:57'),
(21, 23, 'cod', 1200000, 1, '2025-05-05 01:54:24', '2025-05-05 01:54:24'),
(23, 25, 'vnpay', 1200000, 1, '2025-05-05 01:56:22', '2025-05-05 01:56:58'),
(26, 28, 'cod', 1200000, 1, '2025-05-11 07:17:50', '2025-05-11 07:17:51'),
(27, 29, 'vnpay', 1200000, 1, '2025-05-11 07:19:07', '2025-05-11 07:21:44'),
(30, 1226, 'cod', 15000, 1, '2025-05-16 04:18:06', '2025-05-16 04:18:06'),
(31, 1227, 'cod', 165000, 1, '2025-05-16 04:26:33', '2025-05-16 04:26:33'),
(32, 1228, 'cod', 45000, 1, '2025-05-16 04:34:08', '2025-05-16 04:34:08'),
(34, 1230, 'vnpay', 30000, 1, '2025-05-16 10:08:33', '2025-05-16 10:09:31'),
(37, 1233, 'cod', 45000, 1, '2025-05-18 09:38:10', '2025-05-18 09:38:10'),
(38, 1234, 'cod', 15000, 1, '2025-05-18 09:45:56', '2025-05-18 09:45:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `sku_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` int NOT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `category_id` bigint UNSIGNED NOT NULL,
  `brand_id` bigint UNSIGNED NOT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int DEFAULT '0',
  `return_policy` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `sku_code`, `name`, `description`, `price`, `stock_quantity`, `category_id`, `brand_id`, `image_url`, `status`, `return_policy`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Nike Air\'s Fogce', 'Sự rạng rỡ vẫn tồn tại trên Nike Air Force 1 \'07, biểu tượng bóng rổ mang đến luồng gió mới cho những gì bạn biết rõ nhất: chất liệu da bền chắc, màu sắc nổi bật và độ lấp lánh hoàn hảo giúp bạn tỏa sáng.', 1220000, 123, 1, 2, 'uploads/image_url/6Qy9xg5kko40bTVTiBoaf8ON7xyoUmEK2WIXrVfM.webp', 0, NULL, '2025-03-11 06:41:52', '2025-03-11 06:41:52'),
(2, NULL, 'Mostro Fey Launch', 'Được phát hành lần đầu tiên vào năm 1999, Mostro đã trở thành một trong những đôi giày bán chạy nhất của PUMA từ trước đến nay. Được đặt tên trìu mến theo tên của người Ý', 1200000, 100, 1, 7, 'uploads/image_url/t79RgsLcoYnM09Bmb8PhfbawwAkWQMtbv8QZhOEZ.avif', 0, NULL, '2025-03-11 18:10:49', '2025-03-11 18:10:49'),
(3, NULL, 'Samba OG', 'Phiên bản mùa hè casual của đôi giày trainer bóng đá retro yêu thích.', 1224000, 100, 1, 1, 'uploads/image_url/IIUftP0Q0hkZZO36SXvHd1UyCzR56v6SosrAzD3v.avif', 0, NULL, '2025-03-11 18:31:12', '2025-03-11 18:31:12'),
(4, NULL, 'Samba OG W', 'Phiên bản mùa hè casual của đôi giày trainer bóng đá retro yêu thích.', 1229900, 0, 1, 1, 'uploads/image_url/mxIGCj3WcOOroqH0oKSTrCJJFRMXTf0ouGShKoAo.avif', 0, NULL, '2025-03-11 18:32:39', '2025-03-11 18:32:39'),
(5, NULL, 'VANS OLD SKOOL', 'VANS OLD SKOOL CLASSIC BLACK/WHITE', 1900000, 0, 3, 6, 'uploads/image_url/BPbfdAMqB6idOnaHPcpShVPDJaRg5B5DReDSdT4W.webp', 0, NULL, '2025-03-11 18:33:58', '2025-03-11 18:33:58'),
(6, '04M9X5YG5Y6W', 'Giày Chạy Bộ EQ21', NULL, 900000, 0, 2, 1, 'products/K7SswsFa0S4HeA8QwU55kuJgVi0zVlKzZ7rqPzly.webp', 0, NULL, '2025-05-05 00:03:49', '2025-05-05 00:03:49'),
(7, 'G8DI9NSL0TXN', 'Giày Chạy Bộ EQ21', NULL, 900000, 0, 2, 1, 'products/XZg5tWwvqMIlm8nvf9bhYL6SWHcIjzrKTHgDXhMv.webp', 0, NULL, '2025-05-05 00:04:05', '2025-05-05 00:04:05'),
(8, 'DO3VD4DSC53M', 'Nike Air Force 1 \'07 LV8', NULL, 300000, 0, 1, 2, 'products/MIfPCFG4HDEUcqdAeLr1JqS5Z8s1ENn2xeXekEI6.avif', 0, NULL, '2025-05-05 01:16:42', '2025-05-05 01:16:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_categories`
--

CREATE TABLE `product_categories` (
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_details`
--

CREATE TABLE `product_details` (
  `id` int NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `detail_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `detail_content` text,
  `detail_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `product_details`
--

INSERT INTO `product_details` (`id`, `product_id`, `detail_title`, `detail_content`, `detail_image`) VALUES
(1, 1, 'aaaa', 'ab ccc', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_image_gallerys`
--

CREATE TABLE `product_image_gallerys` (
  `id` int NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `gallery` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `size_id` bigint UNSIGNED NOT NULL,
  `color_id` bigint UNSIGNED NOT NULL,
  `price` int NOT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `size_id`, `color_id`, `price`, `stock_quantity`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 15000, 6, NULL, NULL, '2025-05-18 09:45:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sizes`
--

INSERT INTO `sizes` (`id`, `name`) VALUES
(1, '23'),
(2, '34'),
(3, '24'),
(4, '25');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `ban_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  `otp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `avatar`, `email`, `email_verified_at`, `phone_number`, `address`, `role`, `created_at`, `updated_at`, `status`, `ban_reason`, `banned_at`, `otp`) VALUES
(1, 'admin', '$2y$12$LcH0oLl7rtpRCuqVqFA4iut8FJCgJ1LkG5W9PALTDeeF0N.JJjxmO', 'admin', '0', 'admin@mail.com', NULL, '0123456789', '0', 1, NULL, '2025-02-25 23:46:07', 0, NULL, NULL, NULL),
(2, 'datahihi1100', '$2y$12$M8dSb6I7nba1dcLWOk3MteCtIjA3/MuCqZzat229c2RW0hSgervzm', 'Nguyễn Duy Đạt', '0', 'datahihi1100@gmail.com', NULL, '+84377482313', '12541, 338, 34', 0, NULL, '2025-03-07 16:40:49', 0, NULL, NULL, NULL),
(3, 'anh077688', '$2y$12$A13E2zu25Rm4w66n9HhLue.c0mFQJ9VB1C7FX7T3QIBpLf33Rs8BC', 'Nguyễn Mnh Anh', NULL, 'anh077688@gmail.com', NULL, '+84846447189', '09383, 261, 27', 0, '2025-02-24 00:24:37', '2025-03-26 04:36:26', 0, NULL, NULL, NULL),
(4, 'anh1234', '$2y$12$SbnHevFsatBu8n2olNJIyuQ/v5VNgXzjPcB3pDpjPclw8w6NY13wW', 'Nguyễn Duy Anh', NULL, 'anh1234@gmail.com', NULL, '0233116789', '02725, 082, 10', 0, '2025-03-14 15:30:11', '2025-03-14 15:30:11', 0, NULL, NULL, NULL),
(5, 'minh1002', '$2y$12$QuJS8.3QYHcER9ee/tOrzeDf1XKhsQcJQ6MirP11lWSqoVDiQUc/.', 'Nguyễn Duy Minh', NULL, 'minh1002@gmail.com', NULL, '0356778912', '09331, 256, 27', 0, '2025-03-18 07:46:57', '2025-03-18 07:46:57', 0, NULL, NULL, NULL),
(6, 'minhkk1122', '$2y$12$LcH0oLl7rtpRCuqVqFA4iut8FJCgJ1LkG5W9PALTDeeF0N.JJjxmO', 'Nguyễn Duy Minh', NULL, 'minhkk1122@gmail.com', NULL, '02311122222', NULL, 0, '2025-03-26 03:58:36', '2025-05-05 01:11:13', 1, 'scam', '2025-05-05 01:11:13', NULL),
(7, 'datndph42403', '$2y$12$Yb/dIkq2BAVBJQANUoy9ZOGhU9Zm0tMU3EAmZRikl12B9W7MvDYVK', 'Nguyễn Duy Đạt', NULL, 'datndph42403@gmail.com', NULL, '0234567890', NULL, 0, '2025-05-05 01:20:43', '2025-05-05 01:20:43', 0, NULL, NULL, NULL),
(8, 'huy', '$2y$12$9La41TKsd9L4HR/C.UcPJeO18PJgibfPhjsFxs6lyKCWU6rc/S6k2', 'Gia Huy', NULL, 'huy@gmail.com', NULL, '0193482784', '', 0, '2025-05-16 02:59:27', '2025-05-16 02:59:27', 0, NULL, NULL, NULL),
(9, 'hu6kg90087', '$2y$12$.a9Cx/WMd02EtH4fHEi2Pegdng0WGrHTmIA9PbMkLpvRAIvmQFC7C', 'Trần Huy', NULL, 'hu6kg90087@gmail.com', NULL, '0922857593', NULL, 0, '2025-05-18 09:43:36', '2025-05-18 09:43:36', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_history_changes`
--

CREATE TABLE `user_history_changes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `field_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `change_by` bigint UNSIGNED NOT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_history_changes`
--

INSERT INTO `user_history_changes` (`id`, `user_id`, `field_name`, `old_value`, `new_value`, `change_by`, `content`, `updated_at`) VALUES
(1, 3, 'status', '0', '1', 1, 'User banned with reason: Thông tin không hợp lệ', '2025-03-03 06:40:32'),
(2, 2, 'username', 'datahihi1', 'datahihi1100', 1, 'Changed username from datahihi1 to datahihi1100', '2025-03-03 06:42:32'),
(3, 3, 'status', '1', '0', 1, 'User unbanned', '2025-03-03 06:43:18'),
(4, 3, 'status', '0', '1', 1, 'Người dùng bị khóa tài khoản với lí do: Spam', '2025-03-05 06:36:39'),
(5, 3, 'status', '1', '0', 1, 'Gỡ khóa tài khoản người dùng!', '2025-03-07 16:39:22'),
(6, 3, 'status', '0', '1', 1, 'Người dùng bị khóa tài khoản với lí do: Scam lừa đảo', '2025-03-07 16:39:39'),
(7, 2, 'password', '*', '*', 2, 'Người dùng cập nhật mật khẩu mới', '2025-03-07 16:40:49'),
(8, 3, 'status', '1', '0', 1, 'Gỡ khóa tài khoản người dùng!', '2025-03-14 08:36:59'),
(9, 3, 'password', 'Không hiển thị', 'Không hiển thị', 3, 'Người dùng đặt lại mật khẩu', '2025-03-26 04:31:30'),
(10, 3, 'password', 'Không hiển thị', 'Không hiển thị', 3, 'Người dùng đặt lại mật khẩu', '2025-03-26 04:31:54'),
(11, 3, 'password', 'Không hiển thị', 'Không hiển thị', 3, 'Người dùng đặt lại mật khẩu', '2025-03-26 04:32:47'),
(12, 3, 'password', 'Không hiển thị', 'Không hiển thị', 3, 'Người dùng đặt lại mật khẩu', '2025-03-26 04:36:26'),
(13, 6, 'status', '0', '1', 1, 'Người dùng bị khóa tài khoản với lí do: Scam', '2025-04-04 02:45:51'),
(14, 6, 'status', '1', '0', 1, 'Gỡ khóa tài khoản người dùng!', '2025-05-04 19:21:30'),
(15, 6, 'status', '0', '1', 1, 'Người dùng bị khóa tài khoản với lí do: scam', '2025-05-05 01:11:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_ship_address`
--

CREATE TABLE `user_ship_address` (
  `id` int NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `receiver_name` varchar(255) DEFAULT NULL,
  `receiver_address` varchar(255) DEFAULT NULL,
  `receiver_number` varchar(255) DEFAULT NULL,
  `is_default` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` int NOT NULL,
  `min_order_value` int NOT NULL,
  `max_discount_value` int NOT NULL,
  `start_date` timestamp NOT NULL,
  `end_date` timestamp NOT NULL,
  `usage_limit` int NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `discount_type`, `discount_value`, `min_order_value`, `max_discount_value`, `start_date`, `end_date`, `usage_limit`, `status`, `created_at`, `updated_at`) VALUES
(1, 'SALE50', 'percentage', 50, 500000, 250000, '2023-12-31 10:00:00', '2025-12-31 09:59:59', 100, 1, '2025-03-11 23:15:09', '2025-03-11 23:15:09'),
(2, 'FREESHIP', 'fixed', 30000, 200000, 30000, '2024-02-29 10:00:00', '2025-12-31 09:59:59', 200, 1, '2025-03-11 23:15:09', '2025-03-11 23:15:09'),
(3, 'NEWUSER', 'percentage', 20, 300000, 100000, '2023-12-31 10:00:00', '2025-12-31 09:59:59', 50, 1, '2025-03-11 23:15:09', '2025-03-11 23:15:09');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `carts_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_comments_product_id_foreign` (`product_id`),
  ADD KEY `product_comments_user_id_foreign` (`user_id`),
  ADD KEY `product_comments_parent_id_foreign` (`parent_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `likes_user_id_foreign` (`user_id`),
  ADD KEY `likes_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_voucher_id_foreign` (`voucher_id`),
  ADD KEY `orders_product_name_id_foreign` (`product_name_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_variant_id_foreign` (`product_variant_id`);

--
-- Chỉ mục cho bảng `order_item_histories`
--
ALTER TABLE `order_item_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_histories_order_id_foreign` (`order_id`),
  ADD KEY `order_item_histories_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`);

--
-- Chỉ mục cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD KEY `product_id` (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `product_details`
--
ALTER TABLE `product_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product_image_gallerys`
--
ALTER TABLE `product_image_gallerys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`),
  ADD KEY `product_variants_size_id_foreign` (`size_id`),
  ADD KEY `product_variants_color_id_foreign` (`color_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Chỉ mục cho bảng `user_history_changes`
--
ALTER TABLE `user_history_changes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_history_changes_user_id_foreign` (`user_id`),
  ADD KEY `user_history_changes_change_by_foreign` (`change_by`);

--
-- Chỉ mục cho bảng `user_ship_address`
--
ALTER TABLE `user_ship_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1235;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `order_item_histories`
--
ALTER TABLE `order_item_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=326;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `product_details`
--
ALTER TABLE `product_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `product_image_gallerys`
--
ALTER TABLE `product_image_gallerys`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `user_history_changes`
--
ALTER TABLE `user_history_changes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `user_ship_address`
--
ALTER TABLE `user_ship_address`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `carts_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `product_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_comments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_product_name_id_foreign` FOREIGN KEY (`product_name_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `product_categories_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `product_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Các ràng buộc cho bảng `product_details`
--
ALTER TABLE `product_details`
  ADD CONSTRAINT `product_details_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Các ràng buộc cho bảng `product_image_gallerys`
--
ALTER TABLE `product_image_gallerys`
  ADD CONSTRAINT `product_image_gallerys_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Các ràng buộc cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variants_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_history_changes`
--
ALTER TABLE `user_history_changes`
  ADD CONSTRAINT `user_history_changes_change_by_foreign` FOREIGN KEY (`change_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_history_changes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_ship_address`
--
ALTER TABLE `user_ship_address`
  ADD CONSTRAINT `user_ship_address_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
