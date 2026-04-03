-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 03, 2026 at 10:30 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `title`, `subtitle`, `image_path`, `link_url`, `product_id`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Trending Drop 1', 'Explore the fresh looks everyone wants', 'banners/SKCtVh9nA2k4o34oNHlINAe6DmM7hLgQeOcgtVKG.jpg', NULL, NULL, 1, 0, '2026-04-01 11:49:56', '2026-04-01 12:06:27');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'QAO Basics', 'qao-basics', 'Premium quality fashion.', 1, '2026-04-01 11:49:56', '2026-04-01 11:49:56'),
(2, 'Urban Flow', 'urban-flow', 'Premium quality fashion.', 1, '2026-04-01 11:49:56', '2026-04-01 11:49:56'),
(3, 'Luxury Line', 'luxury-line', 'Premium quality fashion.', 1, '2026-04-01 11:49:56', '2026-04-01 11:49:56');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Men', 'men', 'Latest Men styles', 1, '2026-04-01 11:49:56', '2026-04-01 11:49:56'),
(2, 'Women', 'women', 'Latest Women styles', 1, '2026-04-01 11:49:56', '2026-04-01 11:49:56'),
(3, 'Unisex', 'unisex', 'Latest Unisex styles', 1, '2026-04-01 11:49:56', '2026-04-01 11:49:56');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `usage_limit` int UNSIGNED DEFAULT NULL,
  `used` int UNSIGNED NOT NULL DEFAULT '0',
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `product_id`, `code`, `type`, `value`, `usage_limit`, `used`, `starts_at`, `ends_at`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, '10', 'percentage', 10.00, NULL, 6, '2026-03-31 11:49:00', '2026-04-15 11:49:00', 1, '2026-04-01 11:49:56', '2026-04-02 10:58:13');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventories`
--

CREATE TABLE `inventories` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `low_stock_threshold` int UNSIGNED NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventories`
--

INSERT INTO `inventories` (`id`, `product_id`, `size`, `quantity`, `low_stock_threshold`, `created_at`, `updated_at`) VALUES
(33, 9, 'S', 249, 5, '2026-04-02 03:33:15', '2026-04-02 10:16:03'),
(34, 9, 'M', 249, 5, '2026-04-02 03:33:15', '2026-04-02 10:15:46'),
(35, 9, 'L', 248, 5, '2026-04-02 03:33:15', '2026-04-02 10:56:02'),
(36, 9, 'XL', 248, 5, '2026-04-02 03:33:15', '2026-04-02 10:18:39'),
(37, 10, 'M', 333, 5, '2026-04-02 04:14:53', '2026-04-03 03:00:17'),
(38, 10, 'L', 332, 5, '2026-04-02 04:14:53', '2026-04-03 03:00:17'),
(39, 10, 'XL', 332, 5, '2026-04-02 04:14:53', '2026-04-02 08:58:40'),
(40, 11, 'S', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:15:50'),
(41, 11, 'M', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:14:53'),
(42, 11, 'L', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:14:53'),
(43, 12, '28', 250, 5, '2026-04-02 04:14:53', '2026-04-03 03:01:38'),
(44, 12, '29', 250, 5, '2026-04-02 04:14:53', '2026-04-02 04:14:53'),
(45, 12, '30', 249, 5, '2026-04-02 04:14:53', '2026-04-03 03:01:38'),
(46, 12, '31', 249, 5, '2026-04-02 04:14:53', '2026-04-03 03:01:38'),
(47, 13, 'M', 333, 5, '2026-04-02 04:14:53', '2026-04-03 03:02:01'),
(48, 13, 'L', 332, 5, '2026-04-02 04:14:53', '2026-04-03 03:02:01'),
(49, 13, 'XL', 332, 5, '2026-04-02 04:14:53', '2026-04-03 03:02:01'),
(50, 14, 'M', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:15:50'),
(51, 14, 'L', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:14:53'),
(52, 14, 'XL', 332, 5, '2026-04-02 04:14:53', '2026-04-02 10:18:39'),
(53, 15, 'S', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:15:50'),
(54, 15, 'M', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:14:53'),
(55, 15, 'L', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:14:53'),
(56, 16, 'S', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:15:50'),
(57, 16, 'M', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:14:53'),
(58, 16, 'L', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:14:53'),
(59, 17, 'S', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:15:50'),
(60, 17, 'M', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:14:53'),
(61, 17, 'L', 333, 5, '2026-04-02 04:14:53', '2026-04-02 04:14:53');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_histories`
--

CREATE TABLE `inventory_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `inventory_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `change` int NOT NULL,
  `type` enum('import','export','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_histories`
--

INSERT INTO `inventory_histories` (`id`, `inventory_id`, `user_id`, `change`, `type`, `note`, `created_at`, `updated_at`) VALUES
(1, 33, 4, -1, 'export', 'Order ORD-UBHUTM', '2026-04-01 12:20:16', '2026-04-01 12:20:16'),
(2, 40, 4, -1, 'export', 'Order ORD-BY05W4', '2026-04-01 12:35:36', '2026-04-01 12:35:36'),
(3, 36, 3, -26, 'export', 'Order ORD-P07RBH', '2026-04-01 20:26:45', '2026-04-01 20:26:45'),
(4, 37, 4, -1, 'export', 'Order ORD-DWS8B3', '2026-04-01 21:21:54', '2026-04-01 21:21:54'),
(5, 37, 4, -1, 'export', 'Order ORD-1BWZNY', '2026-04-01 21:26:54', '2026-04-01 21:26:54'),
(6, 33, 3, -1, 'export', 'Order ORD-02SVZ8', '2026-04-02 03:23:43', '2026-04-02 03:23:43'),
(7, 35, 3, -1, 'export', 'Order ORD-KKNSGD', '2026-04-02 03:39:40', '2026-04-02 03:39:40'),
(8, 40, 3, -1, 'export', 'Order ORD-KKNSGD', '2026-04-02 03:39:40', '2026-04-02 03:39:40'),
(9, 37, 3, -1, 'export', 'Order ORD-QD3FRH', '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(10, 40, 3, -1, 'export', 'Order ORD-QD3FRH', '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(11, 47, 3, -1, 'export', 'Order ORD-QD3FRH', '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(12, 50, 3, -1, 'export', 'Order ORD-QD3FRH', '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(13, 53, 3, -1, 'export', 'Order ORD-QD3FRH', '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(14, 56, 3, -1, 'export', 'Order ORD-QD3FRH', '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(15, 59, 3, -1, 'export', 'Order ORD-QD3FRH', '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(16, 43, 3, -1, 'export', 'Order ORD-QD3FRH', '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(17, 33, 3, -1, 'export', 'Order ORD-2P1PUY', '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(18, 37, 3, -1, 'export', 'Order ORD-2P1PUY', '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(19, 40, 3, -1, 'export', 'Order ORD-2P1PUY', '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(20, 47, 3, -1, 'export', 'Order ORD-2P1PUY', '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(21, 50, 3, -1, 'export', 'Order ORD-2P1PUY', '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(22, 53, 3, -1, 'export', 'Order ORD-2P1PUY', '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(23, 56, 3, -1, 'export', 'Order ORD-2P1PUY', '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(24, 59, 3, -1, 'export', 'Order ORD-2P1PUY', '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(25, 43, 3, -1, 'export', 'Order ORD-2P1PUY', '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(26, 35, 3, -1, 'export', 'Order ORD-FGXZGO', '2026-04-02 09:33:10', '2026-04-02 09:33:10');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_04_01_000100_create_categories_table', 1),
(5, '2024_04_01_000110_create_brands_table', 1),
(6, '2024_04_01_000120_create_products_table', 1),
(7, '2024_04_01_000130_create_product_images_table', 1),
(8, '2024_04_01_000140_create_discounts_table', 1),
(9, '2024_04_01_000150_create_inventories_table', 1),
(10, '2024_04_01_000160_create_inventory_histories_table', 1),
(11, '2024_04_01_000170_create_orders_table', 1),
(12, '2024_04_01_000180_create_order_items_table', 1),
(13, '2024_04_01_000190_create_banners_table', 1),
(14, '2024_04_02_000200_add_role_to_users_table', 1),
(15, '2026_04_02_000300_update_orders_status_enum', 1),
(16, '2026_04_02_000400_add_cart_items_to_users_table', 2),
(17, '2026_04_02_010000_add_stock_quantity_to_products_table', 3),
(18, '2026_04_02_010100_create_sales_reports_table', 3),
(19, '2026_04_02_020000_backfill_inventory_size_rows', 4),
(20, '2026_04_02_020100_backfill_inventory_histories_from_orders', 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('preparing','handover','in_transit','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'preparing',
  `payment_method` enum('cod','online') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `payment_status` enum('pending','paid','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL,
  `discount_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `code`, `status`, `payment_method`, `payment_status`, `subtotal`, `discount_total`, `shipping_fee`, `total`, `customer_name`, `customer_email`, `customer_phone`, `shipping_address`, `shipping_city`, `shipping_postal_code`, `notes`, `created_at`, `updated_at`) VALUES
(1, 4, 'ORD-FFLECP', 'completed', 'cod', 'pending', 45.89, 0.00, 3.99, 49.88, 'Doanh Nguyễn Ngọc', 'doanhnguyen2110@gmail.com', '0338606913', 'ha noi', 'ha noi', NULL, NULL, '2026-04-01 11:57:04', '2026-04-01 12:37:16'),
(2, 4, 'ORD-UBHUTM', 'completed', 'cod', 'pending', 169000.00, 0.00, 0.00, 169000.00, 'Doanh Nguyễn Ngọc', 'doanhnguyen2110@gmail.com', '0338606913', 'hhh', 'ha noi', NULL, NULL, '2026-04-01 12:20:16', '2026-04-01 12:37:07'),
(3, 4, 'ORD-BY05W4', 'completed', 'online', 'paid', 997000.00, 0.00, 0.00, 997000.00, 'Doanh Nguyễn Ngọc', 'doanhnguyen2110@gmail.com', '0338606913', 'âsas', 'hàn nôikj', NULL, NULL, '2026-04-01 12:35:36', '2026-04-01 12:36:57'),
(4, 3, 'ORD-P07RBH', 'completed', 'cod', 'pending', 4394000.00, 0.00, 0.00, 4394000.00, 'Admin', 'admin@gmail.com', '0338606913', 'đsasda', 'hàn nôikj', NULL, NULL, '2026-04-01 20:26:45', '2026-04-01 21:48:35'),
(5, 4, 'ORD-DWS8B3', 'completed', 'cod', 'pending', 399000.00, 0.00, 0.00, 399000.00, 'Doanh Nguyễn Ngọc', 'doanhnguyen2110@gmail.com', '0338606913', 'âsas', 'hàn nôikj', NULL, NULL, '2026-04-01 21:21:54', '2026-04-02 08:47:39'),
(6, 4, 'ORD-1BWZNY', 'completed', 'cod', 'pending', 399000.00, 0.00, 0.00, 399000.00, 'Doanh Nguyễn Ngọc', 'doanhnguyen2110@gmail.com', '0338606913', 'Số nhà 119 ngõ 136 Cầu diễn', 'Hà Nội', NULL, NULL, '2026-04-01 21:26:54', '2026-04-02 08:47:26'),
(7, 3, 'ORD-02SVZ8', 'completed', 'cod', 'pending', 169000.00, 16900.00, 39000.00, 191100.00, 'Admin', 'admin@gmail.com', '0338606913', 'aaaa', 'Hà Nội', NULL, NULL, '2026-04-02 03:23:43', '2026-04-02 03:50:33'),
(8, 3, 'ORD-KKNSGD', 'completed', 'cod', 'pending', 468000.00, 46800.00, 39000.00, 460200.00, 'Admin', 'admin@gmail.com', '0338606913', 'âss', 'Hà Nội', NULL, NULL, '2026-04-02 03:39:40', '2026-04-02 03:39:58'),
(9, 3, 'ORD-QD3FRH', 'completed', 'cod', 'pending', 3402000.00, 340200.00, 0.00, 3061800.00, 'Admin', 'admin@gmail.com', '0338606913', 'aaaa', 'Hà Nội', NULL, NULL, '2026-04-02 04:04:26', '2026-04-02 04:04:57'),
(10, 3, 'ORD-2P1PUY', 'completed', 'cod', 'pending', 3571000.00, 0.00, 0.00, 3571000.00, 'Admin', 'admin@gmail.com', '0338606913', 'dddddddddd', 'Hà Nội', NULL, NULL, '2026-04-02 04:15:50', '2026-04-02 09:29:39'),
(11, 3, 'ORD-FGXZGO', 'completed', 'cod', 'pending', 169000.00, 0.00, 39000.00, 208000.00, 'Admin', 'admin@gmail.com', '0338606913', 'aa', 'Hà Nội', NULL, NULL, '2026-04-02 09:33:10', '2026-04-02 09:33:19'),
(12, 3, 'ORD-NMVDZC', 'completed', 'cod', 'pending', 518000.00, 0.00, 0.00, 518000.00, 'Admin', 'admin@gmail.com', '0338606913', 'ssss', 'Hà Nội', NULL, NULL, '2026-04-02 10:18:39', '2026-04-02 10:18:46'),
(13, 3, 'ORD-OPMJQ0', 'completed', 'cod', 'pending', 807000.00, 80700.00, 39000.00, 765300.00, 'Admin', 'admin@gmail.com', '0338606913', 'aaaa', 'Hà Nội', NULL, NULL, '2026-04-02 10:53:15', '2026-04-02 10:53:25'),
(14, 5, 'ORD-BLZFLJ', 'completed', 'cod', 'pending', 169000.00, 16900.00, 39000.00, 191100.00, 'Doanh Nguyễn Ngọc', 'doanhchau2110@gmail.com', '0338606913', 'aaa', 'Hà Nội', NULL, NULL, '2026-04-02 10:56:02', '2026-04-02 10:58:41'),
(15, 5, 'ORD-QYJVHJ', 'completed', 'cod', 'pending', 399000.00, 39900.00, 39000.00, 398100.00, 'Doanh Nguyễn Ngọc', 'doanhchau2110@gmail.com', '0338606913', 'hhjjhjjj', 'Hà Nội', NULL, NULL, '2026-04-02 10:58:13', '2026-04-02 10:58:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `sku`, `size`, `quantity`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Fashion Item 1', 'SKU-0001', 'M', 1, 45.89, 45.89, '2026-04-01 11:57:04', '2026-04-01 11:57:04'),
(2, 2, 9, 'Áo thun basic form rộng', 'TS-201', 'S', 1, 169000.00, 169000.00, '2026-04-01 12:20:16', '2026-04-01 12:20:16'),
(3, 3, 10, 'Áo hoodie nỉ trơn unisex', 'HD-202', NULL, 1, 399000.00, 399000.00, '2026-04-01 12:35:36', '2026-04-01 12:35:36'),
(4, 3, 11, 'Áo sơ mi caro trẻ trung', 'SM-203', NULL, 1, 299000.00, 299000.00, '2026-04-01 12:35:36', '2026-04-01 12:35:36'),
(5, 3, 11, 'Áo sơ mi caro trẻ trung', 'SM-203', 'S', 1, 299000.00, 299000.00, '2026-04-01 12:35:36', '2026-04-01 12:35:36'),
(6, 4, 9, 'Áo thun basic form rộng', 'TS-201', 'XL', 26, 169000.00, 4394000.00, '2026-04-01 20:26:45', '2026-04-01 20:26:45'),
(7, 5, 10, 'Áo hoodie nỉ trơn unisex', 'HD-202', 'M', 1, 399000.00, 399000.00, '2026-04-01 21:21:54', '2026-04-01 21:21:54'),
(8, 6, 10, 'Áo hoodie nỉ trơn unisex', 'HD-202', 'M', 1, 399000.00, 399000.00, '2026-04-01 21:26:54', '2026-04-01 21:26:54'),
(9, 7, 9, 'Áo thun basic form rộng', 'TS-201', 'S', 1, 169000.00, 169000.00, '2026-04-02 03:23:43', '2026-04-02 03:23:43'),
(10, 8, 9, 'Áo thun basic form rộng', 'TS-201', 'L', 1, 169000.00, 169000.00, '2026-04-02 03:39:40', '2026-04-02 03:39:40'),
(11, 8, 11, 'Áo sơ mi caro trẻ trung', 'SM-203', 'S', 1, 299000.00, 299000.00, '2026-04-02 03:39:40', '2026-04-02 03:39:40'),
(12, 9, 10, 'Áo hoodie nỉ trơn unisex', 'HD-202', 'M', 1, 399000.00, 399000.00, '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(13, 9, 11, 'Áo sơ mi caro trẻ trung', 'SM-203', 'S', 1, 299000.00, 299000.00, '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(14, 9, 13, 'Quần short thể thao nam', 'QS-205', 'M', 1, 179000.00, 179000.00, '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(15, 9, 14, 'Quần jogger túi hộp', 'JG-206', 'M', 1, 349000.00, 349000.00, '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(16, 9, 15, 'Đầm maxi hoa nhí', 'DR-207', 'S', 1, 599000.00, 599000.00, '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(17, 9, 16, 'Đầm body ôm dáng quyến rũ', 'DR-208', 'S', 1, 599000.00, 599000.00, '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(18, 9, 17, 'Đầm suông công sở thanh lịch', 'DR-209', 'S', 1, 529000.00, 529000.00, '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(19, 9, 12, 'Quần jean skinny co giãn', 'JN-204', '28', 1, 449000.00, 449000.00, '2026-04-02 04:04:26', '2026-04-02 04:04:26'),
(20, 10, 9, 'Áo thun basic form rộng', 'TS-201', 'S', 1, 169000.00, 169000.00, '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(21, 10, 10, 'Áo hoodie nỉ trơn unisex', 'HD-202', 'M', 1, 399000.00, 399000.00, '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(22, 10, 11, 'Áo sơ mi caro trẻ trung', 'SM-203', 'S', 1, 299000.00, 299000.00, '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(23, 10, 13, 'Quần short thể thao nam', 'QS-205', 'M', 1, 179000.00, 179000.00, '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(24, 10, 14, 'Quần jogger túi hộp', 'JG-206', 'M', 1, 349000.00, 349000.00, '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(25, 10, 15, 'Đầm maxi hoa nhí', 'DR-207', 'S', 1, 599000.00, 599000.00, '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(26, 10, 16, 'Đầm body ôm dáng quyến rũ', 'DR-208', 'S', 1, 599000.00, 599000.00, '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(27, 10, 17, 'Đầm suông công sở thanh lịch', 'DR-209', 'S', 1, 529000.00, 529000.00, '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(28, 10, 12, 'Quần jean skinny co giãn', 'JN-204', '28', 1, 449000.00, 449000.00, '2026-04-02 04:15:50', '2026-04-02 04:15:50'),
(29, 11, 9, 'Áo thun basic form rộng', 'TS-201', 'L', 1, 169000.00, 169000.00, '2026-04-02 09:33:10', '2026-04-02 09:33:10'),
(30, 12, 9, 'Áo thun basic form rộng', 'TS-201', 'XL', 1, 169000.00, 169000.00, '2026-04-02 10:18:39', '2026-04-02 10:18:39'),
(31, 12, 14, 'Quần jogger túi hộp', 'JG-206', 'XL', 1, 349000.00, 349000.00, '2026-04-02 10:18:39', '2026-04-02 10:18:39'),
(32, 13, 13, 'Quần short thể thao nam', 'QS-205', 'M', 2, 179000.00, 358000.00, '2026-04-02 10:53:15', '2026-04-02 10:53:15'),
(33, 13, 12, 'Quần jean skinny co giãn', 'JN-204', '28', 1, 449000.00, 449000.00, '2026-04-02 10:53:15', '2026-04-02 10:53:15'),
(34, 14, 9, 'Áo thun basic form rộng', 'TS-201', 'L', 1, 169000.00, 169000.00, '2026-04-02 10:56:02', '2026-04-02 10:56:02'),
(35, 15, 10, 'Áo hoodie nỉ trơn unisex', 'HD-202', 'M', 1, 399000.00, 399000.00, '2026-04-02 10:58:13', '2026-04-02 10:58:13');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `is_trending` tinyint(1) NOT NULL DEFAULT '0',
  `is_new` tinyint(1) NOT NULL DEFAULT '0',
  `is_sale` tinyint(1) NOT NULL DEFAULT '0',
  `size_options` json DEFAULT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `stock_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `title`, `slug`, `sku`, `short_description`, `description`, `price`, `sale_price`, `is_trending`, `is_new`, `is_sale`, `size_options`, `cover_image`, `status`, `stock_quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES
(9, 3, 1, 'Áo thun basic form rộng', 'ao-thun-basic-form-rong', 'TS-201', 'Áo thun đơn giản dễ phối.', 'Chất cotton 100%, form oversize thoải mái, phù hợp mọi phong cách.', 199000.00, 169000.00, 1, 1, 1, '[\"S\", \"M\", \"L\", \"XL\"]', 'products/k28MCGLe6Ncf7wgLr6SQZCCChdS8kUDBIlpcqXnc.jpg', 'published', 1000, '2026-04-01 19:10:30', '2026-04-03 03:07:26', NULL),
(10, 3, 2, 'Áo hoodie nỉ trơn unisex', 'ao-hoodie-ni-tron', 'HD-202', 'Hoodie giữ ấm tốt.', 'Chất nỉ dày, mềm, giữ nhiệt tốt, phù hợp mùa lạnh.', 399000.00, NULL, 1, 0, 0, '[\"M\", \"L\", \"XL\"]', 'products/GAgSOCtIM6tfoUt4Xm7DD6MUDoyTXcmyEw5oElnQ.jpg', 'published', 998, '2026-04-01 19:10:30', '2026-04-03 03:07:02', NULL),
(11, 1, 3, 'Áo sơ mi caro trẻ trung', 'ao-so-mi-caro-tre-trung', 'SM-203', 'Sơ mi caro phong cách Hàn.', 'Thiết kế caro trẻ trung, chất vải thoáng mát, dễ phối đồ.', 349000.00, 299000.00, 0, 1, 1, '[\"S\", \"M\", \"L\"]', 'products/sY8NGCFqNPdYdEVtRUNvJyMzhv80IOUD5hXRlPNz.jpg', 'published', 1000, '2026-04-01 19:10:30', '2026-04-03 03:00:45', NULL),
(12, 1, 1, 'Quần jean skinny co giãn', 'quan-jean-skinny', 'JN-204', 'Jean ôm dáng hiện đại.', 'Chất denim co giãn, tôn dáng, phù hợp đi chơi.', 499000.00, 449000.00, 1, 1, 1, '[\"28\", \"29\", \"30\", \"31\"]', 'products/DwMUJydzMaHkn2sJsfvVJOZ532mBclNWqJxOY4O2.jpg', 'published', 1000, '2026-04-01 19:10:30', '2026-04-03 03:01:38', NULL),
(13, 1, 2, 'Quần short thể thao nam', 'quan-short-the-thao', 'QS-205', 'Short năng động.', 'Chất liệu thun lạnh, thoáng khí, phù hợp vận động.', 179000.00, NULL, 0, 1, 0, '[\"M\", \"L\", \"XL\"]', 'products/308GE7rCUU4d3Ilf0MYhyi1DtpRrxUiQbD3E6fP5.png', 'published', 1000, '2026-04-01 19:10:30', '2026-04-03 03:02:01', NULL),
(14, 1, 3, 'Quần jogger túi hộp', 'quan-jogger-tui-hop', 'JG-206', 'Jogger cá tính.', 'Thiết kế túi hộp, bo ống, phong cách streetwear.', 389000.00, 349000.00, 1, 0, 1, '[\"M\", \"L\", \"XL\"]', 'products/TBZ8dle06kuS42puPYuz6IaCBIoI5OeXpZorc4CX.jpg', 'published', 1000, '2026-04-01 19:10:30', '2026-04-03 03:02:32', NULL),
(15, 2, 1, 'Đầm maxi hoa nhí', 'dam-maxi-hoa-nhi', 'DR-207', 'Đầm dài nữ tính.', 'Chất voan nhẹ, thiết kế hoa nhí dịu dàng, phù hợp dạo phố.', 599000.00, NULL, 1, 1, 0, '[\"S\", \"M\", \"L\"]', 'products/xSNaicZa1ktb7cnjtR0hYsj5EoTSzVoS5ianVXpD.jpg', 'published', 1000, '2026-04-01 19:10:30', '2026-04-03 03:02:56', NULL),
(16, 2, 2, 'Đầm body ôm dáng quyến rũ', 'dam-body-om-dang', 'DR-208', 'Đầm ôm tôn dáng.', 'Chất liệu co giãn tốt, tôn đường cong cơ thể.', 649000.00, 599000.00, 0, 0, 1, '[\"S\", \"M\", \"L\"]', 'products/udilSOtQU3pCHrGzSZTp0xp1H0E1Rio4XgQdU3dS.jpg', 'published', 1000, '2026-04-01 19:10:30', '2026-04-03 03:03:42', NULL),
(17, 2, 3, 'Đầm suông công sở thanh lịch', 'dam-suong-cong-so', 'DR-209', 'Đầm công sở.', 'Thiết kế tối giản, phù hợp môi trường văn phòng.', 529000.00, NULL, 0, 1, 0, '[\"S\", \"M\", \"L\"]', 'products/VyNNyALp8Q25SVMR5LAK0DYinzWgTFBxtxsz1Q81.webp', 'published', 1000, '2026-04-01 19:10:30', '2026-04-03 03:04:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_reports`
--

CREATE TABLE `sales_reports` (
  `id` bigint UNSIGNED NOT NULL,
  `report_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'day',
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `total_revenue` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_sold` bigint UNSIGNED NOT NULL DEFAULT '0',
  `total_stock` bigint UNSIGNED NOT NULL DEFAULT '0',
  `export_format` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'excel',
  `export_file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_reports`
--

INSERT INTO `sales_reports` (`id`, `report_type`, `from_date`, `to_date`, `total_revenue`, `total_sold`, `total_stock`, `export_format`, `export_file_name`, `generated_by`, `created_at`, `updated_at`) VALUES
(1, 'day', '2026-04-02', '2026-04-02', 4394000.00, 26, 0, 'excel', 'bao-cao-ban-hang-day-20260402_103830.csv', 3, '2026-04-02 03:38:30', '2026-04-02 03:38:30'),
(2, 'day', '2026-04-02', '2026-04-02', 12476100.00, 48, 8990, 'excel', 'bao-cao-ban-hang-day-20260402_155030.csv', 3, '2026-04-02 08:50:30', '2026-04-02 08:50:30'),
(3, 'day', '2026-04-02', '2026-04-02', 12476100.00, 48, 8989, 'excel', 'bao-cao-ban-hang-day-20260402_155901.csv', 3, '2026-04-02 08:59:01', '2026-04-02 08:59:01');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('SIyTrM6FxRJM8Pn6GL2a11UhPNRDFc4RPPv4Bjmo', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJldERzSmIwM3RyckU3RjljWTltTHhEVU5UTXlVWkFybVJhOHhxeUJEIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC9yZXBvcnRzXC9zYWxlc1wvZXhwb3J0P3BlcmlvZD1kYXkmcmVwb3J0X2RhdGU9MjAyNi0wNC0wMiIsInJvdXRlIjoiYWRtaW4ucmVwb3J0cy5zYWxlcy5leHBvcnQifSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjN9', 1775212085);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart_items` json DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `gender`, `address`, `city`, `postal_code`, `avatar`, `cart_items`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Shop Admin', 'admin@shop.com', '2026-04-01 11:49:55', '$2y$12$9LGqRHQ/oFgeb6e5dRtO8OaJLkQRn6Rn6YuoGmL9bN7AGvQh4u1BC', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '9FRnmWlYDO', '2026-04-01 11:49:56', '2026-04-01 11:49:56'),
(2, 'Demo User', 'user@shop.com', '2026-04-01 11:49:56', '$2y$12$Ue9UoC4161FviCpRgqeyYemvXZ0mDCvN7WQxs2Aca5wBTEAZ4UUYm', 'user', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'cc0VseGYPf', '2026-04-01 11:49:56', '2026-04-01 11:49:56'),
(3, 'Admin', 'admin@gmail.com', NULL, '$2y$12$Z0a02BeeQosrZIGw1WnV2OFaV8e82aHQWphSrWJD03ZExnCSveVmS', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, '[]', 'oaGC1eD4ZjXkqTU08VWmUiopxfV2Qa58k6ScECIMc1HysNAcxgbVwcAGpvp0', '2026-04-01 11:54:40', '2026-04-02 10:53:15'),
(4, 'Doanh Nguyễn Ngọc', 'doanhnguyen2110@gmail.com', NULL, '$2y$12$3FAzXYXgvwCZXPddlyf6E.2KGbW.z6z4yaH.MqJc6oXxCXhjwt9jy', 'user', NULL, NULL, 'Số nhà 119 ngõ 136 Cầu diễn', 'Hà Nội', NULL, NULL, '{\"9:S\": {\"key\": \"9:S\", \"sku\": \"TS-201\", \"name\": \"Áo thun basic form rộng\", \"size\": \"S\", \"slug\": \"ao-thun-basic-form-rong\", \"image\": \"/storage/products/l8X3W653xxxW8aIfswcxQSB1V4mxKwb4sV0QuNbJ.jpg\", \"price\": \"169000.00\", \"quantity\": 1, \"subtotal\": 169000, \"product_id\": 9, \"available_sizes\": [\"S\", \"M\", \"L\", \"XL\"]}, \"11:S\": {\"key\": \"11:S\", \"sku\": \"SM-203\", \"name\": \"Áo sơ mi caro trẻ trung\", \"size\": \"S\", \"slug\": \"ao-so-mi-caro-tre-trung\", \"image\": \"/storage/products/n6PUPrJKqMEDlMkpiHhzC4XXoS4D42SgebSdSCTV.jpg\", \"price\": \"299000.00\", \"quantity\": 1, \"subtotal\": 299000, \"product_id\": 11, \"available_sizes\": [\"S\", \"M\", \"L\"]}}', NULL, '2026-04-01 11:56:41', '2026-04-01 21:35:17'),
(5, 'Doanh Nguyễn Ngọc', 'doanhchau2110@gmail.com', NULL, '$2y$12$cWg6e/ddjZCFdUY8rXqGAu0kV71OgwwozTflHA1oNqb03cXSES4XC', 'user', NULL, NULL, NULL, NULL, NULL, NULL, '[]', NULL, '2026-04-02 10:55:12', '2026-04-02 10:58:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `banners_product_id_foreign` (`product_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_name_unique` (`name`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discounts_code_unique` (`code`),
  ADD KEY `discounts_product_id_foreign` (`product_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventories`
--
ALTER TABLE `inventories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventories_product_id_size_unique` (`product_id`,`size`);

--
-- Indexes for table `inventory_histories`
--
ALTER TABLE `inventory_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_histories_inventory_id_foreign` (`inventory_id`),
  ADD KEY `inventory_histories_user_id_foreign` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_code_unique` (`code`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `sales_reports`
--
ALTER TABLE `sales_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_reports_generated_by_foreign` (`generated_by`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventories`
--
ALTER TABLE `inventories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `inventory_histories`
--
ALTER TABLE `inventory_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sales_reports`
--
ALTER TABLE `sales_reports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `banners`
--
ALTER TABLE `banners`
  ADD CONSTRAINT `banners_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `discounts`
--
ALTER TABLE `discounts`
  ADD CONSTRAINT `discounts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventories`
--
ALTER TABLE `inventories`
  ADD CONSTRAINT `inventories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_histories`
--
ALTER TABLE `inventory_histories`
  ADD CONSTRAINT `inventory_histories_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_reports`
--
ALTER TABLE `sales_reports`
  ADD CONSTRAINT `sales_reports_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
