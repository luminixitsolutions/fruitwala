-- Default FAQ rows (same Q&A as public faq.php / includes/faqs.php fallback).
-- Safe to re-run: inserts only when `faqs` has no rows yet.

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
) AS `seed_rows`
WHERE NOT EXISTS (SELECT 1 FROM `faqs` LIMIT 1);
