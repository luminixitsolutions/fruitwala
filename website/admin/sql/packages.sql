-- Package cards for packages.php (managed from admin Package master).

CREATE TABLE IF NOT EXISTS `packages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL DEFAULT '',
  `delivery_line` varchar(255) NOT NULL DEFAULT '',
  `sale_price` varchar(32) NOT NULL DEFAULT '',
  `mrp` varchar(32) NOT NULL DEFAULT '',
  `badge_1` varchar(120) NOT NULL DEFAULT '',
  `badge_2` varchar(120) NOT NULL DEFAULT '',
  `bullet_points` mediumtext NOT NULL,
  `book_pkg_name` varchar(64) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_packages_sort` (`sort_order`),
  KEY `idx_packages_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
