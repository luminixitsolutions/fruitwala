-- Seed: default header navigation (matches previous static menu).
-- Import after schema.sql. Removes only these default URLs, then inserts (safe to re-run).

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
