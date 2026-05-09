CREATE TABLE IF NOT EXISTS `blogs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL,
  `excerpt` text NOT NULL,
  `author` varchar(120) NOT NULL DEFAULT 'Fruitwala Team',
  `category` varchar(120) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `content` mediumtext NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_blogs_sort` (`sort_order`),
  KEY `idx_blogs_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
