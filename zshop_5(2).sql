-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- 主機: localhost:3306
-- 產生時間： 2021 年 08 月 20 日 18:04
-- 伺服器版本: 10.1.48-MariaDB-0ubuntu0.18.04.1
-- PHP 版本： 7.2.24-0ubuntu0.18.04.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `zshop_5`
--

-- --------------------------------------------------------

--
-- 資料表結構 `carts`
--

CREATE TABLE `carts` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `carts`
--

INSERT INTO `carts` (`id`, `product_id`, `user_id`, `quantity`, `created_at`, `updated_at`) VALUES
(25, 1, 3, 1, '2021-08-20 02:02:04', '2021-08-20 02:02:04');

-- --------------------------------------------------------

--
-- 資料表結構 `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_parent` tinyint(1) NOT NULL DEFAULT '1',
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `categories`
--

INSERT INTO `categories` (`id`, `title`, `slug`, `is_parent`, `parent_id`, `status`, `created_at`, `updated_at`) VALUES
(1, '3C', '3C_All', 1, NULL, 'active', '2021-08-18 01:49:00', '2021-08-18 01:49:00'),
(2, '日常', 'daily_necessities_All', 1, NULL, 'active', '2021-08-18 01:50:00', '2021-08-18 01:50:00'),
(3, '筆電', 'notebook_All', 0, 1, 'active', '2021-08-18 01:51:00', '2021-08-18 01:51:00'),
(4, '桌電', 'desktop_All', 0, 1, 'active', '2021-08-18 01:52:00', '2021-08-18 01:52:00'),
(5, '電腦螢幕', 'monitor_All', 0, 1, 'active', '2021-08-18 01:53:00', '2021-08-18 01:53:00'),
(6, '通訊', 'telenoticias_All', 1, NULL, 'active', '2021-08-18 01:54:00', '2021-08-18 01:54:00'),
(7, '家電', 'appliances_All', 1, NULL, 'active', '2021-08-18 01:55:00', '2021-08-18 01:55:00'),
(8, '食品', 'food_All', 1, NULL, 'active', '2021-08-18 01:55:00', '2021-08-18 01:55:00');

-- --------------------------------------------------------

--
-- 資料表結構 `coupons`
--

CREATE TABLE `coupons` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `coupon_line` int(11) NOT NULL,
  `coupon_amount` int(11) NOT NULL,
  `coupon_type` int(11) NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `name`, `coupon_line`, `coupon_amount`, `coupon_type`, `status`, `created_at`, `updated_at`) VALUES
(1, '', '滿一千現折一百', 1000, 100, 1, 'active', '2021-08-18 16:00:00', '2021-08-18 16:00:00'),
(3, '0000', '滿一千送一百購物金', 1000, 100, 2, 'active', '2021-08-18 16:00:00', '2021-08-18 16:00:00'),
(4, '0001', '滿兩千現折兩百', 2000, 200, 1, 'active', '2021-08-18 16:00:00', '2021-08-18 16:00:00'),
(5, '0002', '滿兩千送兩百購物金', 2000, 200, 2, 'active', '2021-08-18 16:00:00', '2021-08-18 16:00:00');

-- --------------------------------------------------------

--
-- 資料表結構 `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` text COLLATE utf8_unicode_ci,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `messages`
--

INSERT INTO `messages` (`id`, `name`, `subject`, `user_id`, `order_id`, `email`, `phone`, `message`, `read_at`, `created_at`, `updated_at`) VALUES
(1, '555', NULL, NULL, NULL, 'adfdasf@gmail.com', NULL, 'ajdf;lkjdg', NULL, '2021-08-19 22:17:21', '2021-08-19 22:17:21'),
(2, 'asfda', NULL, NULL, NULL, 'asdfa@gmail.om', NULL, 'sadfasfgsadfasdf', NULL, '2021-08-19 22:51:15', '2021-08-19 22:51:15'),
(3, '666', NULL, NULL, NULL, '666@jga.com', NULL, '66666666adsfasdf', NULL, '2021-08-19 22:52:34', '2021-08-19 22:52:34');

-- --------------------------------------------------------

--
-- 資料表結構 `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2012_08_17_070117_create_user_levels_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2021_08_18_012638_create_categories_table', 2),
(5, '2021_08_18_013057_create_products_table', 2),
(6, '2021_08_18_091234_create_reward_money_histories_table', 3),
(7, '2021_08_19_010641_create_coupons_table', 4),
(8, '2021_08_19_011500_create_orders_table', 4),
(9, '2021_08_19_011642_create_messages_table', 4),
(10, '2021_08_19_015548_create_carts_table', 5),
(11, '2021_08_19_020509_create_wishlists_table', 5),
(12, '2021_08_19_021506_create_order_items_table', 5),
(13, '2021_08_20_055737_create_settings_table', 6);

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `subtotal` int(11) NOT NULL,
  `shipping_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coupon_id` int(10) UNSIGNED DEFAULT NULL,
  `reward_money` int(11) DEFAULT NULL,
  `total` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `post_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `order_items`
--

CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `is_return` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('test001@gmail.com', '$2y$10$p7ROk/814.orRvDFgvxaUuo.u32SOKJbwzn/3sMg529PB8P2kwg1W', '2021-08-17 23:38:49');

-- --------------------------------------------------------

--
-- 資料表結構 `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `summary` text COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `photo` text COLLATE utf8_unicode_ci NOT NULL,
  `stock` int(11) NOT NULL DEFAULT '1',
  `size` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inactive',
  `price` int(11) NOT NULL,
  `special_price` int(11) NOT NULL,
  `is_featured` tinyint(1) NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `subcategory_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `products`
--

INSERT INTO `products` (`id`, `title`, `slug`, `summary`, `description`, `photo`, `stock`, `size`, `state`, `status`, `price`, `special_price`, `is_featured`, `category_id`, `subcategory_id`, `created_at`, `updated_at`) VALUES
(1, 'ACER Aspire A715-75G-70V7 黑', 'A715-75G-70V7', '15吋大螢幕★GTX1650Ti獨顯★再享好禮雙重抽', '處理器：Intel® Core™ i7-10750H\r\n顯示晶片：NVIDIA® GeForce® GTX 1650Ti\r\n記憶體：8GB DDR4\r\n硬碟：512GB PCIe NVMe SSD\r\n螢幕：15.6\" FHD/IPS/霧面/LED背光\r\n無線網路：802.11a/b/g/n/acR2+ax2x2 MU-MIMO\r\n其他：Bluetooth® 5.1、Type-C\r\n軟體：Windows 10 Home', '/storage/photos/Products/A715-75G-70V7.jpg', 1, NULL, NULL, 'active', 30900, 27900, 1, 1, 3, '2021-08-18 02:09:00', '2021-08-18 02:09:00'),
(2, 'ASUS X515JP-0471S1035G1 冰柱銀\r\n15.6吋窄邊獨顯筆電', 'X515JP-0471S1035G1', '10代i5★MX330獨顯★512G PCIe\r\n', '處理器：Intel® Core™ i5-1035G1 Processor 1.0 GHz\r\n記憶體：8GB DDR4 on board\r\n硬碟：512GB M.2 NVMe™ PCIe® 3.0 SSD\r\n獨立顯卡：Nvidia GeForce MX330 2G獨顯\r\nLCD尺寸：15.6\" FHD (1920x1080)\r\n無線網路：Wi-Fi 5(802.11ac)+Bluetooth 4.1 (Dual band) 1*1\r\n光碟機：無\r\n重量：1.8kg\r\n其他：HDMI、USB3.2 Type C\r\n作業系統：Windows 10 Home 64 Bits\r\n', '/storage/photos/Products/X515JP-0471S1035G1.jpg', 100, NULL, NULL, 'active', 27900, 24900, 1, 1, 3, '2021-08-18 16:00:00', '2021-08-18 16:00:00');

-- --------------------------------------------------------

--
-- 資料表結構 `reward_money_histories`
--

CREATE TABLE `reward_money_histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `reward_item` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `reward_money_histories`
--

INSERT INTO `reward_money_histories` (`id`, `user_id`, `reward_item`, `amount`, `total`, `created_at`, `updated_at`) VALUES
(1, 3, '老闆爽給', 500, 500, '2021-08-18 09:40:00', NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `short_des` text COLLATE utf8_unicode_ci,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `settings`
--

INSERT INTO `settings` (`id`, `description`, `short_des`, `logo`, `photo`, `address`, `phone`, `email`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, '/storage/photos/Setting/zshop-logo.png', '/storage/photos/Setting/qrcode.png', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_shopping_amount` int(11) NOT NULL DEFAULT '0',
  `reward_money` int(11) NOT NULL DEFAULT '0',
  `role` enum('super_admin','admin','user') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `user_level_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `address`, `total_shopping_amount`, `reward_money`, `role`, `user_level_id`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, NULL, 'test001@gmail.com', NULL, '$2y$10$VolwguyPINsF/AfaJbmsj.UuI1I0JL27Ft7doabkwVNqCiUYKC2uC', NULL, NULL, 0, 0, 'user', 1, 'active', 'oactaMGUh6iqbEkrQXThmdZvNmjdHZhf5cit7ZU1f4IHAYCVNF4dp5LqrEmQ', '2021-08-17 00:22:50', '2021-08-17 00:22:50'),
(4, NULL, 'test002@gmail.com', NULL, '$2y$10$mm5PipcTSH43vzhSK4OZZuDc7A4FQwQR3i5zRp3EcYTDdmy66sGL2', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 00:48:04', '2021-08-17 00:48:04'),
(5, NULL, 'test003@gmail.com', NULL, '$2y$10$EBEXtxkRghk9aa6omcaCg.VbRxIdroEfsYvu4Jxf4/9mEVpoE.UYu', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 00:49:39', '2021-08-17 00:49:39'),
(6, NULL, 'test004@gmail.com', NULL, '$2y$10$2ofkOA40kv7.TW44LEg1ieYSQPiNBid20FKx/6hwKICBY9lz8vuBC', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 00:50:16', '2021-08-17 00:50:16'),
(7, NULL, 'test005@gmail.com', NULL, '$2y$10$EFraLR023TxKkLLBjOAwa.e9YXG0jxszuja4y/BaSk/aNMv.WWI6C', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 00:51:36', '2021-08-17 00:51:36'),
(8, NULL, 'test006@gmail.com', NULL, '$2y$10$HNxVKal2Gdl1gA.RByZa6uFBvhIq7nXv71Juj95plknhXhZjta19S', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 00:52:42', '2021-08-17 00:52:42'),
(9, NULL, 'test007@gmail.com', NULL, '$2y$10$HxQTnxDxbgTVZC4hhRNw4.aYCBxO0e2c0cyhiSiQTgX7SroBlnYnC', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 00:58:20', '2021-08-17 00:58:20'),
(10, NULL, 'test008@gmail.com', NULL, '$2y$10$6.dqvV3kFR7sJBWUeFWMce.bhN13DvAcxUCGbVR4alqdATxrmuluK', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 00:59:53', '2021-08-17 00:59:53'),
(11, NULL, 'test009@gmail.com', NULL, '$2y$10$8RTajD662Tgnmcj6RABqhurkWTkRg1sv3GPok/zBZS/MULvNroLXa', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 01:01:20', '2021-08-17 01:01:20'),
(12, NULL, 'test010@gmail.com', NULL, '$2y$10$6TMn5KOfecMu3v.ppuhl4.ih9aZTqzQ1bxt2V3lOErLjezhFv38qK', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 01:01:56', '2021-08-17 01:01:56'),
(13, NULL, 'test011@gmail.com', NULL, '$2y$10$bgZf4I6LfGClEbwds7BfEO1I0HCWK79WT8etV9GBAb/yI42zCgNIe', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 01:05:17', '2021-08-17 01:05:17'),
(14, NULL, 'test012@gmail.com', NULL, '$2y$10$Ch6f3zJLhMTk7QT5hA/L9eo4cqlPMsoSdXa7tknoVFuymU7giNllK', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 01:09:01', '2021-08-17 01:09:01'),
(15, NULL, 'test013@gmail.com', NULL, '$2y$10$Fr6Vq9jISoQ.SbXIqsxAD.orMZ1BsgrhcgQaqU5D1NpH3GJV6mSnm', NULL, NULL, 0, 0, 'user', 1, 'active', 'dw5wdiiIaaE0lyrJGaXpkhMlVhMXqH9hqZlEjifV6RTbCsqCmWnYjZzGu3YT', '2021-08-17 01:10:23', '2021-08-17 01:10:23'),
(16, NULL, 'test014@gmail.com', NULL, '$2y$10$qnOvW7rMNjvHTwHm9FXDVOMrd1uxcmcxV66aZ64kTV8OaUERoP6Ku', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 01:27:56', '2021-08-17 01:27:56'),
(17, NULL, 'test015@gmail.com', NULL, '$2y$10$BauUYOSKaO/hY03HpL0kUOSAm6wkDhPnJoUzrbn/Evlk8.Dqh/qCW', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 01:56:18', '2021-08-17 01:56:18'),
(18, 'test666', 'test666@gmail.com', NULL, '$2y$10$7RgmwRzBhkYDqmzPPNvOOe0HMjCyMa4r6Hz7rItxQ9rEl7oDRfIui', NULL, NULL, 0, 0, 'user', 1, 'active', 'yzKv5Vi4d70xxXWkfUPSXXnDua2bKhoi3Bq03ZVjTITVBnxSIlIYZMwY8kRr', '2021-08-17 20:27:04', '2021-08-17 20:27:04'),
(19, NULL, 'test016@gmail.com', NULL, '$2y$10$QGV4QBGgrw.9DP8WfoxQru.Jl5dXdGib/EA/erBeP94L.Ogy1uQs6', NULL, NULL, 0, 0, 'user', 1, 'active', NULL, '2021-08-17 22:44:35', '2021-08-17 22:44:35'),
(20, '017', 'test017@gmail.com', NULL, '$2y$10$QYnl4oF1Dsd3x/YL./JL4eRJLSFFCuGi1xzNbgddF4df3Ra/WCmdW', '09017017', 'gkasjg;', 0, 0, 'user', 1, 'active', 'i7p4Jq5rx0Cg5M6SnSdKzCkgbpSpaiCAX6Am7xdLEkTgeVofYMStv9FlBHad', '2021-08-17 22:47:52', '2021-08-18 01:22:41');

-- --------------------------------------------------------

--
-- 資料表結構 `user_levels`
--

CREATE TABLE `user_levels` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `level_up_line` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `user_levels`
--

INSERT INTO `user_levels` (`id`, `name`, `level_up_line`, `created_at`, `updated_at`) VALUES
(1, '普通會員', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_product_id_foreign` (`product_id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- 資料表索引 `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- 資料表索引 `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- 資料表索引 `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_user_id_foreign` (`user_id`),
  ADD KEY `messages_order_id_foreign` (`order_id`);

--
-- 資料表索引 `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_coupon_id_foreign` (`coupon_id`);

--
-- 資料表索引 `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- 資料表索引 `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- 資料表索引 `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_subcategory_id_foreign` (`subcategory_id`);

--
-- 資料表索引 `reward_money_histories`
--
ALTER TABLE `reward_money_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reward_money_histories_user_id_foreign` (`user_id`);

--
-- 資料表索引 `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_user_level_id_foreign` (`user_level_id`);

--
-- 資料表索引 `user_levels`
--
ALTER TABLE `user_levels`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`),
  ADD KEY `wishlists_user_id_foreign` (`user_id`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- 使用資料表 AUTO_INCREMENT `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用資料表 AUTO_INCREMENT `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用資料表 AUTO_INCREMENT `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用資料表 AUTO_INCREMENT `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- 使用資料表 AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用資料表 AUTO_INCREMENT `reward_money_histories`
--
ALTER TABLE `reward_money_histories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用資料表 AUTO_INCREMENT `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用資料表 AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- 使用資料表 AUTO_INCREMENT `user_levels`
--
ALTER TABLE `user_levels`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用資料表 AUTO_INCREMENT `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 已匯出資料表的限制(Constraint)
--

--
-- 資料表的 Constraints `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- 資料表的 Constraints `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- 資料表的 Constraints `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- 資料表的 Constraints `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- 資料表的 Constraints `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- 資料表的 Constraints `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- 資料表的 Constraints `reward_money_histories`
--
ALTER TABLE `reward_money_histories`
  ADD CONSTRAINT `reward_money_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- 資料表的 Constraints `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_user_level_id_foreign` FOREIGN KEY (`user_level_id`) REFERENCES `user_levels` (`id`) ON DELETE SET NULL;

--
-- 資料表的 Constraints `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `carts` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
