-- Hero slider rows (replaces fixed hero_slide_1 / hero_slide_2 keys for the banner).

CREATE TABLE IF NOT EXISTS `home_hero_slides` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `kicker` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `btn_text` varchar(120) NOT NULL DEFAULT '',
  `btn_url` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_hero_slides_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
