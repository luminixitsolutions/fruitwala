-- Single-row site-wide company / contact details (see includes/company_profile.php).

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
