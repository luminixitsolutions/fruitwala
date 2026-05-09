-- Homepage editable fields (key/value). Run on existing installs if not created by full schema.

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
