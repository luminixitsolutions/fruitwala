-- Fruitwala website admin + navigation (import into existing DB, e.g. fruitwal_web)

CREATE TABLE IF NOT EXISTS `nav_menus` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_nav_sort` (`sort_order`),
  KEY `idx_nav_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `home_page_fields` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `section_key` varchar(64) NOT NULL,
  `field_key` varchar(80) NOT NULL,
  `field_value` mediumtext NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_home_section_field` (`section_key`,`field_key`),
  KEY `idx_home_section` (`section_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `home_hero_slides` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `kicker` varchar(255) NOT NULL DEFAULT '',
  `headline_main` varchar(180) NOT NULL DEFAULT '',
  `headline_sub` varchar(180) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `btn_text` varchar(120) NOT NULL DEFAULT '',
  `btn_url` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_hero_slides_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `home_reels` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `video` varchar(255) NOT NULL DEFAULT '',
  `cover` varchar(255) NOT NULL DEFAULT '',
  `alt` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_home_reels_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `home_sale_banners` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `title` mediumtext NOT NULL,
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_home_sale_banners_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `home_services` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL DEFAULT '',
  `subtitle` varchar(255) NOT NULL DEFAULT '',
  `icon` varchar(120) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_home_services_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `home_instagram_tiles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `popup` varchar(255) NOT NULL DEFAULT '',
  `img` varchar(255) NOT NULL DEFAULT '',
  `alt` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_home_ig_tiles_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `home_testimonials` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `heading` varchar(255) NOT NULL DEFAULT '',
  `body` mediumtext NOT NULL,
  `author` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_home_testimonials_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `home_gallery_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `title` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_home_gallery_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `home_gallery_strip_sidebar` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(500) NOT NULL DEFAULT '',
  `meta1` varchar(255) NOT NULL DEFAULT '',
  `meta2` varchar(255) NOT NULL DEFAULT '',
  `link` varchar(512) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_home_strip_sidebar_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `about_us_content` (
  `id` tinyint unsigned NOT NULL DEFAULT 1,
  `breadcrumb_title` varchar(160) NOT NULL DEFAULT 'About us',
  `badge_text` varchar(255) NOT NULL DEFAULT 'FRESH • HEALTHY • NATURAL',
  `heading_html` mediumtext NOT NULL,
  `subtitle` mediumtext NOT NULL,
  `body_text` mediumtext NOT NULL,
  `hero_image` varchar(255) NOT NULL DEFAULT 'assets/images/18.webp',
  `hero_image_alt` varchar(255) NOT NULL DEFAULT 'Fresh Fruit Basket',
  `btn_text` varchar(120) NOT NULL DEFAULT 'Contact Us Now!',
  `btn_url` varchar(255) NOT NULL DEFAULT 'contact.php',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `company_profile` (
  `id` tinyint unsigned NOT NULL DEFAULT 1,
  `company_name` varchar(255) NOT NULL DEFAULT 'Fruitwala Breakfast',
  `address` mediumtext NOT NULL,
  `phone` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `facebook_url` varchar(512) NOT NULL DEFAULT '',
  `instagram_url` varchar(512) NOT NULL DEFAULT '',
  `twitter_url` varchar(512) NOT NULL DEFAULT '',
  `linkedin_url` varchar(512) NOT NULL DEFAULT '',
  `youtube_url` varchar(512) NOT NULL DEFAULT '',
  `whatsapp_url` varchar(512) NOT NULL DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `why_choose_us_content` (
  `id` tinyint unsigned NOT NULL DEFAULT 1,
  `breadcrumb_title` varchar(160) NOT NULL DEFAULT 'Why Choose Us',
  `badge_text` varchar(255) NOT NULL DEFAULT 'WHY FRUITWALA BREAKFAST',
  `heading_html` mediumtext NOT NULL,
  `subtitle` mediumtext NOT NULL,
  `body_text` mediumtext NOT NULL,
  `hero_image` varchar(255) NOT NULL DEFAULT 'assets/images/21.png',
  `hero_image_alt` varchar(255) NOT NULL DEFAULT 'Fresh seasonal fruits',
  `btn_text` varchar(120) NOT NULL DEFAULT 'Contact Us Now!',
  `btn_url` varchar(255) NOT NULL DEFAULT 'contact.php',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(500) NOT NULL,
  `answer` mediumtext NOT NULL,
  `sort_order` int NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_faqs_sort` (`sort_order`),
  KEY `idx_faqs_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `portfolio_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sort_order` int NOT NULL DEFAULT 0,
  `label` varchar(255) NOT NULL DEFAULT '',
  `video` varchar(255) NOT NULL DEFAULT '',
  `cover` varchar(255) NOT NULL DEFAULT '',
  `alt` varchar(255) NOT NULL DEFAULT '',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_portfolio_sort` (`sort_order`),
  KEY `idx_portfolio_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
