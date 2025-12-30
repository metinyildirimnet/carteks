-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Anamakine: db:3306
-- Üretim Zamanı: 27 Ara 2025, 21:25:54
-- Sunucu sürümü: 8.0.44
-- PHP Sürümü: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `laravel`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Oto Bakım Ürünleri', 'adsşlakdaşl', '2025-12-23 22:23:00', '2025-12-27 17:26:51');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `failed_jobs`
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
-- Tablo için tablo yapısı `homepage_blocks`
--

CREATE TABLE `homepage_blocks` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product',
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `show_on_desktop` tinyint(1) NOT NULL DEFAULT '1',
  `show_on_mobile` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `linkable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkable_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `homepage_blocks`
--

INSERT INTO `homepage_blocks` (`id`, `title`, `slug`, `description`, `type`, `image_path`, `link_url`, `sort_order`, `is_active`, `show_on_desktop`, `show_on_mobile`, `created_at`, `updated_at`, `linkable_type`, `linkable_id`) VALUES
(1, 'Öne Çıkan Ürünler', 'one-cikan-urunler', 'Öne Çıkan Ürünlerimiz', 'product', NULL, NULL, 1, 1, 1, 1, '2025-12-22 23:18:41', '2025-12-26 22:20:53', NULL, NULL),
(2, 'Yeni Yıl İndirimleri', 'yeni-yil-indirimleri', 'Yeni Yıl İndirimleri', 'product', NULL, NULL, 2, 1, 1, 1, '2025-12-22 23:20:25', '2025-12-26 22:20:53', NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `homepage_block_product`
--

CREATE TABLE `homepage_block_product` (
  `homepage_block_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `homepage_block_product`
--

INSERT INTO `homepage_block_product` (`homepage_block_id`, `product_id`, `sort_order`) VALUES
(1, 4, 3),
(1, 5, 0),
(1, 6, 1),
(1, 7, 2),
(2, 4, 0),
(2, 5, 0),
(2, 6, 0),
(2, 7, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `jobs`
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
-- Tablo için tablo yapısı `job_batches`
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
-- Tablo için tablo yapısı `marquee_items`
--

CREATE TABLE `marquee_items` (
  `id` bigint UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `marquee_items`
--

INSERT INTO `marquee_items` (`id`, `content`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Yeni Sitemize Hoş Geldinizzz', 1, NULL, '2025-12-26 22:05:22', '2025-12-26 22:18:22'),
(2, 'Yeni Sitemize Hoş Geldinizzzz', 1, NULL, '2025-12-26 22:05:29', '2025-12-26 22:17:20');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `menu_groups`
--

CREATE TABLE `menu_groups` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `menu_groups`
--

INSERT INTO `menu_groups` (`id`, `title`, `key`, `created_at`, `updated_at`) VALUES
(1, 'Hızlı Linkler', 'footer_1', '2025-12-27 17:45:11', '2025-12-27 18:44:25'),
(2, 'Kurumsal', 'footer_2', '2025-12-27 17:45:11', '2025-12-27 18:46:07'),
(3, 'Müşteri Hizmetleri', 'footer_3', '2025-12-27 17:45:11', '2025-12-27 18:46:38'),
(4, 'Yasal', 'footer_4', '2025-12-27 17:45:11', '2025-12-27 18:48:40');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint UNSIGNED NOT NULL,
  `menu_group_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `linkable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkable_id` bigint UNSIGNED DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_group_id`, `title`, `url`, `target`, `linkable_type`, `linkable_id`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ana Sayfa', 'https://cartek.sitedemolari.com.tr', '_self', NULL, NULL, 0, '2025-12-27 18:44:52', '2025-12-27 19:11:13'),
(2, 1, 'Ürünler', 'https://google.com/urunler', '_self', NULL, NULL, 1, '2025-12-27 18:45:22', '2025-12-27 18:52:01'),
(3, 1, 'Paketler', 'https://google.com/paketler', '_self', NULL, NULL, 2, '2025-12-27 18:45:39', '2025-12-27 18:52:01'),
(4, 1, 'Sipariş Takip', 'https://google.com/siparis-takip', '_self', NULL, NULL, 3, '2025-12-27 18:46:00', '2025-12-27 18:52:01'),
(5, 2, 'Hakkımızda', NULL, '_self', 'App\\Models\\Page', 1, 0, '2025-12-27 18:46:18', '2025-12-27 18:46:18'),
(6, 3, 'Sıkça Sorulan Sorular', NULL, '_self', 'App\\Models\\Page', 3, 0, '2025-12-27 18:48:02', '2025-12-27 18:48:02'),
(7, 3, 'İade ve Değişim', NULL, '_self', 'App\\Models\\Page', 4, 0, '2025-12-27 18:48:18', '2025-12-27 18:48:18'),
(8, 3, 'Garanti Koşulları', NULL, '_self', 'App\\Models\\Page', 5, 0, '2025-12-27 18:48:32', '2025-12-27 18:48:32'),
(9, 4, 'Kullanım Koşulları', NULL, '_self', 'App\\Models\\Page', 6, 0, '2025-12-27 18:50:00', '2025-12-27 18:50:00'),
(10, 4, 'Gizlilik Politikası', NULL, '_self', 'App\\Models\\Page', 7, 0, '2025-12-27 18:50:15', '2025-12-27 18:50:15'),
(11, 4, 'Çerez Politikası', NULL, '_self', 'App\\Models\\Page', 8, 0, '2025-12-27 18:50:29', '2025-12-27 18:50:29'),
(12, 4, 'Mesafeli Satış Sözleşmesi', NULL, '_self', 'App\\Models\\Page', 9, 0, '2025-12-27 18:50:47', '2025-12-27 18:50:47');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_22_122553_create_products_table', 1),
(5, '2025_12_22_122621_create_product_images_table', 1),
(6, '2025_12_22_133404_create_categories_table', 1),
(7, '2025_12_22_133504_add_category_id_to_products_table', 1),
(8, '2025_12_22_225201_create_homepage_blocks_table', 2),
(9, '2025_12_22_225235_create_homepage_block_product_table', 2),
(10, '2025_12_22_230846_create_orders_table', 3),
(11, '2025_12_22_230925_create_order_items_table', 3),
(12, '2025_12_23_003828_add_ip_and_user_agent_to_orders_table', 4),
(13, '2025_12_23_004056_add_customer_info_to_orders_table', 4),
(14, '2025_12_23_004821_create_order_statuses_table', 5),
(15, '2025_12_23_004923_add_order_status_id_to_orders_table', 5),
(16, '2025_12_23_005951_add_payment_method_to_orders_table', 6),
(17, '2025_12_23_112813_make_address_fields_nullable_in_orders_table', 7),
(18, '2025_12_23_220346_create_settings_table', 8),
(19, '2025_12_23_225102_add_slug_to_products_table', 9),
(20, '2025_12_24_144551_make_slug_unique_and_not_nullable_in_homepage_blocks_table', 10),
(21, '2025_12_24_151336_add_sort_order_to_homepage_block_product_table', 11),
(22, '2025_12_24_154742_remove_unique_from_sort_order_in_homepage_blocks_table', 12),
(23, '2025_12_24_175236_add_view_count_to_products_table', 13),
(24, '2025_12_24_203545_create_order_discounts_table', 14),
(25, '2025_12_24_203320_add_discount_amount_to_orders_table', 15),
(26, '2025_12_24_204927_add_discount_amount_to_orders_table_again', 15),
(27, '2025_12_24_213818_create_product_reviews_table', 16),
(28, '2025_12_24_234635_create_product_recommendations_table', 17),
(29, '2025_12_26_164115_add_is_package_to_products_table', 18),
(30, '2025_12_26_164640_create_package_product_table', 18),
(31, '2025_12_26_183738_add_order_code_to_orders_table', 19),
(32, '2025_12_26_185011_add_soft_deletes_to_orders_table', 20),
(33, '2025_12_26_190155_add_soft_deletes_to_products_table', 21),
(34, '2025_12_26_215357_create_marquee_items_table', 22),
(35, '2025_12_27_093438_add_usage_areas_to_products_table', 23),
(36, '2025_12_27_153926_add_incomplete_order_sms_fields_to_orders_table', 24),
(37, '2025_12_27_154435_make_user_id_nullable_in_orders_table', 25),
(38, '2025_12_27_160000_create_pages_table', 26),
(39, '2025_12_27_180000_create_menu_groups_table', 27),
(40, '2025_12_27_180001_create_menu_items_table', 27),
(41, '2025_12_27_200000_create_slides_table', 28),
(42, '2025_12_27_210000_add_type_and_visuals_to_homepage_blocks_table', 29);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `order_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `discount_amount` decimal(8,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status_id` bigint UNSIGNED NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incomplete_order_sms_sent` tinyint(1) NOT NULL DEFAULT '0',
  `incomplete_order_sms_sent_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `total_amount`, `discount_amount`, `created_at`, `updated_at`, `ip_address`, `user_agent`, `customer_name`, `customer_phone`, `customer_address`, `customer_city`, `customer_district`, `order_status_id`, `payment_method`, `incomplete_order_sms_sent`, `incomplete_order_sms_sent_at`, `deleted_at`) VALUES
(20, 'AABTVWYD', 1, 2694.00, 22.45, '2025-12-26 18:55:34', '2025-12-27 16:03:15', '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'metin yıldırım', '+90 534 012 98 23', 'prof. baki komsuoglu blv. no.:448/11', 'kocaeli', 'izmit', 1, 'bank_transfer', 1, '2025-12-27 16:03:15', NULL),
(21, '7YQ76XLA', 1, 475.00, 25.00, '2025-12-26 21:40:18', '2025-12-26 21:40:23', '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'metin yıldırım', '+90 534 012 98 23', 'adasdada', 'kocaeli', 'istanbul', 2, 'bank_transfer', 0, NULL, NULL),
(22, 'PHPPAPHK', 1, 1472.50, 77.50, '2025-12-26 21:42:16', '2025-12-26 21:42:21', '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'metin yıldırım', '+90 534 012 98 23', 'adasd adadas asdadsaa asda', 'kocaeli', 'izmit', 2, 'bank_transfer', 0, NULL, NULL),
(23, '3QSV9YIX', NULL, 3000.00, 0.00, '2025-12-27 10:32:24', '2025-12-27 10:32:26', '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'metin yıldırım', '+90 534 012 98 23', 'asdsadfghg sfdsfss', 'kocaeli', 'izmit', 2, 'card_on_delivery', 0, NULL, NULL),
(24, 'QCATQMLD', NULL, 500.00, 0.00, '2025-12-27 10:39:45', '2025-12-27 10:39:49', '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'metin yıldırım', '+90 534 012 98 23', 'asdsada dada', 'kocae', 'liizmit', 2, 'card_on_delivery', 0, NULL, NULL),
(25, 'TW8W2RND', NULL, 6200.00, 0.00, '2025-12-27 16:56:12', '2025-12-27 16:56:18', '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'metin yıldırım', '+90 534 012 98 23', 'ada ada adad', 'test', 'test', 2, 'cash_on_delivery', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `order_discounts`
--

CREATE TABLE `order_discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `order_discounts`
--

INSERT INTO `order_discounts` (`id`, `order_id`, `type`, `description`, `amount`, `percentage`, `created_at`, `updated_at`) VALUES
(2, 20, 'bank_transfer_discount', '%5 Havale Ödeme İndirimi', 22.45, 5.00, '2025-12-26 18:55:55', '2025-12-26 18:55:55'),
(3, 21, 'bank_transfer_discount', '%5 Havale Ödeme İndirimi', 25.00, 5.00, '2025-12-26 21:40:23', '2025-12-26 21:40:23'),
(4, 22, 'bank_transfer_discount', '%5 Havale Ödeme İndirimi', 77.50, 5.00, '2025-12-26 21:42:21', '2025-12-26 21:42:21');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(43, 20, 8, 6, 449.00, '2025-12-26 18:55:55', '2025-12-26 18:59:59'),
(45, 21, 6, 1, 500.00, '2025-12-26 21:40:23', '2025-12-26 21:40:23'),
(47, 22, 5, 1, 1550.00, '2025-12-26 21:42:21', '2025-12-26 21:42:21'),
(49, 23, 6, 6, 500.00, '2025-12-27 10:32:26', '2025-12-27 10:32:26'),
(51, 24, 6, 1, 500.00, '2025-12-27 10:39:49', '2025-12-27 10:39:49'),
(53, 25, 5, 4, 1550.00, '2025-12-27 16:56:18', '2025-12-27 16:56:18');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `order_statuses`
--

CREATE TABLE `order_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `order_statuses`
--

INSERT INTO `order_statuses` (`id`, `name`, `color`, `created_at`, `updated_at`) VALUES
(1, 'Tamamlanmayan', '#ffc107', '2025-12-23 00:53:16', '2025-12-23 00:53:16'),
(2, 'Yeni Sipariş', '#17a2b8', '2025-12-23 00:53:16', '2025-12-23 00:53:16'),
(3, 'Kargoya Verildi', '#007bff', '2025-12-23 00:53:16', '2025-12-23 00:53:16'),
(4, 'Teslim Edildi', '#28a745', '2025-12-23 00:53:16', '2025-12-23 00:53:16'),
(5, 'İptal Edildi', '#dc3545', '2025-12-23 00:53:16', '2025-12-23 00:53:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `package_product`
--

CREATE TABLE `package_product` (
  `id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `package_product`
--

INSERT INTO `package_product` (`id`, `package_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 8, 4, 1, NULL, NULL),
(2, 8, 5, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `is_published` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `is_published`, `created_at`, `updated_at`) VALUES
(1, 'Hakkımızda', 'hakkimizda', '<p>Hakkımızda sayfasının i&ccedil;eriği budur.</p>', 1, '2025-12-27 17:27:23', '2025-12-27 17:31:24'),
(2, 'İletişim', 'iletisim', NULL, 1, '2025-12-27 18:46:30', '2025-12-27 18:46:30'),
(3, 'Sıkça Sorulan Sorular', 'sikca-sorulan-sorular', '<p>Sık&ccedil;a Sorulan Sorular</p>', 1, '2025-12-27 18:46:55', '2025-12-27 18:46:55'),
(4, 'İade ve Değişim', 'iade-ve-degisim', '<p>İade ve Değişim</p>', 1, '2025-12-27 18:47:31', '2025-12-27 18:47:31'),
(5, 'Garanti Koşulları', 'garanti-kosullari', '<p>Garanti Koşulları</p>', 1, '2025-12-27 18:47:43', '2025-12-27 18:47:43'),
(6, 'Kullanım Koşulları', 'kullanim-kosullari', '<p>Kullanım Koşulları</p>', 1, '2025-12-27 18:48:53', '2025-12-27 18:48:53'),
(7, 'Gizlilik Politikası', 'gizlilik-politikasi', '<p>Gizlilik Politikası</p>', 1, '2025-12-27 18:49:04', '2025-12-27 18:49:04'),
(8, 'Çerez Politikası', 'cerez-politikasi', '<p>&Ccedil;erez Politikası</p>', 1, '2025-12-27 18:49:13', '2025-12-27 18:49:13'),
(9, 'Mesafeli Satış Sözleşmesi', 'mesafeli-satis-sozlesmesi', '<p>Mesafeli Satış S&ouml;zleşmesi</p>', 1, '2025-12-27 18:49:21', '2025-12-27 18:49:21');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `view_count` int UNSIGNED NOT NULL DEFAULT '0',
  `discounted_price` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `is_package` tinyint(1) NOT NULL DEFAULT '0',
  `usage_areas` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `products`
--

INSERT INTO `products` (`id`, `title`, `slug`, `description`, `price`, `view_count`, `discounted_price`, `created_at`, `updated_at`, `category_id`, `is_package`, `usage_areas`, `deleted_at`) VALUES
(4, '2 Adet Protek Sıvı Seramik Kaplama', '2-adet-protek-sivi-seramik-kaplama', 'Protek sıvı seramik kaplama ile aracınızı tüm dış etkenlerden koruyabilir ayrıca güzel bir görünüme sahip olabilirsiniz.Ayrıca su itici özelliği sayesinde aracınızı tüm sıvı etkenlerden koruyacaktır.', 749.00, 88, 500.00, '2025-12-23 22:21:23', '2025-12-26 20:49:32', 1, 0, NULL, NULL),
(5, 'Cilalı Fırçasız Araç Yıkama Şampuanı 20LT', 'cilali-fircasiz-arac-yikama-sampuani-20lt', 'Cilalı Fırçasız Araç Yıkama Şampuanı 20LT', 2000.00, 52, 1550.00, '2025-12-23 22:22:54', '2025-12-27 16:55:49', 1, 0, NULL, NULL),
(6, 'Motor Ve Jant Temizleyici 2 Adet', 'motor-ve-jant-temizleyici-2-adet', 'Protek motor ve jant temizleme ilacı sayesinde arabanızın motor ve jant kısmında çıkmayan güçlü yağ vb. lekeleri kolaylıkla temizleyebilirsiniz.', 700.00, 12, 500.00, '2025-12-23 22:26:07', '2025-12-27 09:33:09', 1, 0, NULL, NULL),
(7, 'Plastik Yenileyici Ve Koruyucu 2 Adet', 'plastik-yenileyici-ve-koruyucu-2-adet', 'Plastik Yenileyici Ve Koruyucu 2 Adet', 600.00, 15, 500.00, '2025-12-23 22:26:42', '2025-12-27 09:42:40', 1, 0, '[\"Otomotiv\"]', NULL),
(8, 'Yılbaşı Kampanya Paketi', 'yilbasi-kampanya-paketi', 'Yılbaşı Kampanya Paketi', 500.00, 118, 449.00, '2025-12-26 18:11:50', '2025-12-27 17:06:33', 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `created_at`, `updated_at`) VALUES
(5, 4, 'products/Aqfe9PqryUP1cDznNo5szEpgQsJUlTDp7ANFiQlW.png', '2025-12-23 22:21:23', '2025-12-23 22:21:23'),
(6, 5, 'products/iSYfm6cAa0ucCVYh0zfpQOR0wfdzDrVJhwZtI0ut.png', '2025-12-23 22:22:54', '2025-12-23 22:22:54'),
(7, 6, 'products/3CG7QAkzYUHOuik0pccREV3VhSAXcnqvUeZ7vR6f.png', '2025-12-23 22:26:08', '2025-12-23 22:26:08'),
(9, 7, 'products/IeIUreSoiGZo0VKvo33rUJG9jdiUlI3O6iFV0RKM.png', '2025-12-25 19:49:21', '2025-12-25 19:49:21'),
(10, 8, 'products/RFH5v1x1YSZdrWidOxxF5J3raS15sw6h66SKTvP2.jpg', '2025-12-26 18:11:50', '2025-12-26 18:11:50');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_recommendations`
--

CREATE TABLE `product_recommendations` (
  `product_id` bigint UNSIGNED NOT NULL,
  `recommended_product_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product_recommendations`
--

INSERT INTO `product_recommendations` (`product_id`, `recommended_product_id`) VALUES
(5, 4),
(7, 4),
(7, 5),
(7, 6),
(5, 7);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 4, NULL, 4, 'Çok güzel bir ürün.', 1, '2025-12-24 22:04:10', '2025-12-24 22:04:10'),
(3, 4, NULL, 5, NULL, 1, '2025-12-24 22:12:05', '2025-12-24 22:12:05');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sessions`
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
-- Tablo döküm verisi `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5EsHIF2Yp1EZhZgxq55oi5zApWSgCmoLmMq8HzBI', 1, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVTFScnRnNHBEeDZIaVQyR2tVd2t6SHk3Q0RLM0ZycXlVSzRRSE9OMyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vcHJvZHVjdHMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjIxOiJhZG1pbi5wcm9kdWN0cy5jcmVhdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1766442469),
('7DiQ8102SD3sxBlJAlrIx1NBL42E6mQrENxZQEVt', 1, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNUszVjNpUmNzc0tycWtnMmo3VXg1MWxaMTkwbW9CdzNTYTA0Zjk5ZCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1766442792),
('bCjoafLqAeJxDrwDl5sGh4Kwg8V4anvdOqOBlXBs', 1, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUkIycW5BdXpnTzFnajBuQjU1aE5CeUd4T2poRHVTZ2hJU29RaVMydCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1766442230),
('cMEjockcgSA5mVmyTB3u7vohm1KxzEeNlKNTYI2T', 1, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRW9lVkJXVzZubW5qUkpOVzlkbVJEVW1STmt1NlM2aHdLQ01FczUzaSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vcHJvZHVjdHMiO3M6NToicm91dGUiO3M6MjA6ImFkbWluLnByb2R1Y3RzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1766442621),
('gaTGrfyxN5dhlyITjp3U9sJH4I2eh0NbvZnhPY3O', 1, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYWl4TUtEMVNFTDlmS3dic3ZBdWhYM2o5b0pNQXJneEthUXZlVGxRMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1766440566),
('JbKAiPapn5CY3yzl3PugGlf0dzlogy0JsRF1Z6Xd', 1, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMnQwZjhEMmRVWEoxQ1l0cUQyaXM1dWoxenllcHVTaTlleGlZdlJpaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1766441390),
('JELBTEjHRMn4EnGrtNdx4AFP7Xz7VNuuWA1LvBJZ', 1, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibWcyMzIxVXBrTDVTRXJVM0FwVGZYQXZPY1NyakxraVBFYWxYSldyWSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vcHJvZHVjdHMiO3M6NToicm91dGUiO3M6MjA6ImFkbWluLnByb2R1Y3RzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1766442105),
('z9qTOagcscdsOHIcB7UKQF2OpWgOKgWNli6iU6kN', 1, '192.168.65.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.2 Safari/605.1.15', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiT1oyWmw2Wlc0VWpJTDY5d1RMUDdoYXB3TmJEQ0c3elR1S1VQQ1VBUyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vcHJvZHVjdHMiO3M6NToicm91dGUiO3M6MjA6ImFkbWluLnByb2R1Y3RzLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1766442302);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'site_title', 'Cartek Oto', '2025-12-23 22:09:00', '2025-12-25 08:06:44'),
(2, 'whatsapp_active', '0', '2025-12-23 22:09:00', '2025-12-25 08:06:27'),
(3, 'whatsapp_number', '905340129823', '2025-12-23 22:09:00', '2025-12-23 22:35:14'),
(4, 'contact_phone', NULL, '2025-12-23 22:09:00', '2025-12-23 22:09:00'),
(5, 'contact_address', NULL, '2025-12-23 22:09:00', '2025-12-23 22:09:00'),
(6, 'site_logo', 'logos/2JZDFDaObjlR3HmFBCBeNuASMIM6rG4SdLctGu6y.png', '2025-12-23 22:09:00', '2025-12-26 18:32:32'),
(7, 'havale_odeme_indirimi', '5', '2025-12-23 22:09:00', '2025-12-23 22:14:57'),
(8, 'order_completion_sms_template', 'Sayın {{ customer_name }}, sitemiz vermiş olduğunuz {{ order_code }} nolu siparişiniz başarıyla alınmıştır siparişiniz en kısa sürede kargoya verilecektir. Soru ve sorunlarınız için 05079826861 numarasından bize ulaşabilirsiniz.', '2025-12-26 21:36:12', '2025-12-26 21:41:54'),
(9, 'sms_api_url', 'https://metinyildirim.net/api/smsapi.php', '2025-12-26 21:36:12', '2025-12-26 21:36:12'),
(10, 'sms_sender_number', 'GULTEKKIMYA', '2025-12-26 21:36:12', '2025-12-27 15:51:36'),
(11, 'social_facebook_url', 'https://facebook.com#facebook', '2025-12-26 22:30:58', '2025-12-26 22:30:58'),
(12, 'social_twitter_url', 'https://facebook.com#twitter', '2025-12-26 22:30:58', '2025-12-26 22:30:58'),
(13, 'social_instagram_url', 'https://facebook.com#instagram', '2025-12-26 22:30:58', '2025-12-26 22:30:58'),
(14, 'social_linkedin_url', 'https://facebook.com#linkedin', '2025-12-26 22:30:58', '2025-12-26 22:30:58'),
(15, 'incomplete_order_sms_template', 'Sayın {{ customer_name }}, sitemiz vermiş olduğunuz {{ order_code }} nolu siparişiniz yarım kalmıştır. Siparişinizi tamamlamak için {{ resume_link }} adresini ziyaret edin.', '2025-12-27 15:50:17', '2025-12-27 16:03:05'),
(16, 'facebook_pixel_id', '803219326104184', '2025-12-27 16:52:31', '2025-12-27 16:55:47');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `slides`
--

CREATE TABLE `slides` (
  `id` bigint UNSIGNED NOT NULL,
  `desktop_image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `button_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkable_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `slides`
--

INSERT INTO `slides` (`id`, `desktop_image_path`, `mobile_image_path`, `content`, `button_text`, `button_url`, `linkable_type`, `linkable_id`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'slides/SDDQCHuzm928HrKDOrSaVoUSOcrID7uoun7oPRUS.png', 'slides/ECaU3cvOCw4CIc4Eyi0hr04Qg8k5vKPpojLw1kYD.png', NULL, NULL, NULL, 'App\\Models\\Category', NULL, 1, 0, '2025-12-27 19:27:57', '2025-12-27 19:28:48'),
(2, 'slides/2Zry3RDoA8AgJtfUahWrx54G9mvxnKldy1PpYMD6.png', 'slides/xorMUfsrS47XnfoZswy6InBt2yARYLlbqvbRfVnc.png', NULL, NULL, NULL, 'App\\Models\\Category', NULL, 1, 0, '2025-12-27 19:29:45', '2025-12-27 19:29:45');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2025-12-22 21:53:38', '$2y$12$5qKImKYc1OEUha5QmywLG.VaiikpO9L9BGGvgm93mTDrbtGOEvQWK', 'LYJ1YmiVFf0Kqm4PUvG3Q15msdWbKMYIkJJEHpEUpvM5MOD3ZA2qrKSyqZmV', '2025-12-22 21:53:39', '2025-12-22 21:53:39');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Tablo için indeksler `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Tablo için indeksler `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Tablo için indeksler `homepage_blocks`
--
ALTER TABLE `homepage_blocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `homepage_blocks_slug_unique` (`slug`),
  ADD KEY `homepage_blocks_linkable_type_linkable_id_index` (`linkable_type`,`linkable_id`);

--
-- Tablo için indeksler `homepage_block_product`
--
ALTER TABLE `homepage_block_product`
  ADD PRIMARY KEY (`homepage_block_id`,`product_id`),
  ADD KEY `homepage_block_product_product_id_foreign` (`product_id`);

--
-- Tablo için indeksler `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Tablo için indeksler `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `marquee_items`
--
ALTER TABLE `marquee_items`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `menu_groups`
--
ALTER TABLE `menu_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu_groups_key_unique` (`key`);

--
-- Tablo için indeksler `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_menu_group_id_foreign` (`menu_group_id`),
  ADD KEY `menu_items_linkable_type_linkable_id_index` (`linkable_type`,`linkable_id`);

--
-- Tablo için indeksler `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_order_status_id_foreign` (`order_status_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Tablo için indeksler `order_discounts`
--
ALTER TABLE `order_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_discounts_order_id_foreign` (`order_id`);

--
-- Tablo için indeksler `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Tablo için indeksler `order_statuses`
--
ALTER TABLE `order_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `package_product`
--
ALTER TABLE `package_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_product_package_id_foreign` (`package_id`),
  ADD KEY `package_product_product_id_foreign` (`product_id`);

--
-- Tablo için indeksler `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Tablo için indeksler `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Tablo için indeksler `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Tablo için indeksler `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Tablo için indeksler `product_recommendations`
--
ALTER TABLE `product_recommendations`
  ADD PRIMARY KEY (`product_id`,`recommended_product_id`),
  ADD KEY `product_recommendations_recommended_product_id_foreign` (`recommended_product_id`);

--
-- Tablo için indeksler `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_reviews_product_id_foreign` (`product_id`),
  ADD KEY `product_reviews_user_id_foreign` (`user_id`);

--
-- Tablo için indeksler `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Tablo için indeksler `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Tablo için indeksler `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `slides_linkable_type_linkable_id_index` (`linkable_type`,`linkable_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `homepage_blocks`
--
ALTER TABLE `homepage_blocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `marquee_items`
--
ALTER TABLE `marquee_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `menu_groups`
--
ALTER TABLE `menu_groups`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Tablo için AUTO_INCREMENT değeri `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `order_discounts`
--
ALTER TABLE `order_discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Tablo için AUTO_INCREMENT değeri `order_statuses`
--
ALTER TABLE `order_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `package_product`
--
ALTER TABLE `package_product`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Tablo için AUTO_INCREMENT değeri `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tablo için AUTO_INCREMENT değeri `slides`
--
ALTER TABLE `slides`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `homepage_block_product`
--
ALTER TABLE `homepage_block_product`
  ADD CONSTRAINT `homepage_block_product_homepage_block_id_foreign` FOREIGN KEY (`homepage_block_id`) REFERENCES `homepage_blocks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `homepage_block_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_group_id_foreign` FOREIGN KEY (`menu_group_id`) REFERENCES `menu_groups` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_order_status_id_foreign` FOREIGN KEY (`order_status_id`) REFERENCES `order_statuses` (`id`),
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `order_discounts`
--
ALTER TABLE `order_discounts`
  ADD CONSTRAINT `order_discounts_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `package_product`
--
ALTER TABLE `package_product`
  ADD CONSTRAINT `package_product_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `product_recommendations`
--
ALTER TABLE `product_recommendations`
  ADD CONSTRAINT `product_recommendations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_recommendations_recommended_product_id_foreign` FOREIGN KEY (`recommended_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
