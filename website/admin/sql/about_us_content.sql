-- Single-row About Us page content (see about-us.php main story block).

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
