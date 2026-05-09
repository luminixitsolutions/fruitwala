-- Full seed in one file (run after schema.sql on your database, e.g. fruitwal_web).
-- Safe to re-run: default nav URLs are deleted then re-inserted; admin uses INSERT IGNORE.
--
-- Default admin (change password in production):
--   Username: admin
--   Password: Fruitwala@Dev2026

DELETE FROM `nav_menus` WHERE `url` IN (
  'index.php',
  'about-us.php',
  'why-choose-us.php',
  'packages.php',
  'diet_consultation.php',
  'blogs.php',
  'faq.php',
  'portfolio.php',
  'contact.php'
);

INSERT INTO `nav_menus` (`title`, `url`, `sort_order`, `is_active`) VALUES
('Home', 'index.php', 10, 1),
('About Us', 'about-us.php', 20, 1),
('Why Choose Us', 'why-choose-us.php', 25, 1),
('Packages', 'packages.php', 30, 1),
('Diet Consultation', 'diet_consultation.php', 40, 1),
('Blogs', 'blogs.php', 50, 1),
('FAQ', 'faq.php', 60, 1),
('Portfolio', 'portfolio.php', 70, 1),
('Contact Us', 'contact.php', 80, 1);

INSERT IGNORE INTO `admin_users` (`username`, `password_hash`) VALUES
('admin', '$2y$10$BJGK1UHNVKGS0iJL5OOE/epaBV3/rFBksMGoZZwd1OzPD2q8Yjwvu');

-- FAQ defaults (same as website faq.php); only when `faqs` is empty. See also faqs_seed.sql.
INSERT INTO `faqs` (`question`, `answer`, `sort_order`, `is_active`)
SELECT `question`, `answer`, `sort_order`, `is_active` FROM (
  SELECT 'Where do your fruits come from?' AS `question`, 'We source our fruits daily from trusted local farms and wholesale markets to ensure maximum freshness, quality, and taste in every fruit box.' AS `answer`, 10 AS `sort_order`, 1 AS `is_active`
  UNION ALL SELECT 'Are the fruits cleaned and hygienically packed?', 'Yes, all fruits are carefully washed, sorted, and hygienically packed in clean, safe boxes before delivery to maintain health and safety standards.', 20, 1
  UNION ALL SELECT 'Do you offer customized fruit baskets?', 'Absolutely! We create customized fruit baskets for birthdays, events, corporate gifts, and special occasions. You can choose fruits, box styles, and quantities.', 30, 1
  UNION ALL SELECT 'What areas do you deliver to?', 'We currently deliver to selected local areas. Contact us on Instagram or WhatsApp to confirm delivery availability in your location.', 40, 1
  UNION ALL SELECT 'How fresh are your fruit boxes?', 'Our fruit boxes are prepared fresh on the same day of delivery to ensure maximum taste, nutrition, and freshness.', 50, 1
  UNION ALL SELECT 'Can I place bulk or corporate orders?', 'Yes, we accept bulk and corporate orders for offices, events, and gifting. Special packaging and pricing are available for large orders.', 60, 1
  UNION ALL SELECT 'How do I place an order?', 'You can place an order directly through our Instagram page or by contacting us via WhatsApp. Soon, ordering will also be available directly on our website.', 70, 1
  UNION ALL SELECT 'Do you deliver on the same day?', 'Same-day delivery is available for orders placed before our daily cut-off time. Please contact us early to confirm availability.', 80, 1
) AS `seed_faq_rows`
WHERE NOT EXISTS (SELECT 1 FROM `faqs` LIMIT 1);
