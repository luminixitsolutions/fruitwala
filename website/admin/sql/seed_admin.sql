-- Seed: default admin login (development only — change password after first login).
-- Import after schema.sql. Uses INSERT IGNORE so it skips if username 'admin' already exists.
--
-- Login after import:
--   Username: admin
--   Password: Fruitwala@Dev2026
--
-- Hash generated with PHP: password_hash('Fruitwala@Dev2026', PASSWORD_DEFAULT)

INSERT IGNORE INTO `admin_users` (`username`, `password_hash`) VALUES
('admin', '$2y$10$BJGK1UHNVKGS0iJL5OOE/epaBV3/rFBksMGoZZwd1OzPD2q8Yjwvu');
