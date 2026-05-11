-- Default blog cards for blogs.php (run after blogs_table.sql or after visiting site once so `blogs` exists).
-- Re-run safe: removes only these three titles, then inserts them again.

DELETE FROM `blogs` WHERE `title` IN (
  'Top 7 Health Benefits of Eating Fresh Fruits Daily',
  'Why Fruit Baskets Make the Perfect Healthy Gift',
  'How Fruitwala Prepares Fresh Fruit Boxes Every Morning'
);

INSERT INTO `blogs` (`sort_order`, `title`, `excerpt`, `author`, `category`, `image`, `content`, `is_active`) VALUES
(10,
 'Top 7 Health Benefits of Eating Fresh Fruits Daily',
 'Discover how adding fresh seasonal fruits to your daily breakfast can improve immunity, digestion, and overall energy levels.',
 'Fruitwala Team',
 'Health Tips',
 'assets/images/19.png',
 'Eating fresh fruits every day is one of the simplest ways to support your health. Seasonal produce delivers vitamins, minerals, and fibre in a form your body recognises and absorbs well.\n\nStart with one serving at breakfast, add fruit as snacks, and rotate colours through the week so you benefit from a wide range of nutrients.\n\nSmall habits compound: better hydration from water-rich fruits, steadier energy from natural sugars paired with fibre, and happier digestion from whole-food fibre.',
 1),
(20,
 'Why Fruit Baskets Make the Perfect Healthy Gift',
 'Looking for a thoughtful and healthy gift? Fresh fruit baskets are ideal for celebrations, corporate gifting, and loved ones.',
 'Fruitwala Team',
 'Gifting Ideas',
 'assets/images/20.png',
 'When you want to celebrate without excess sugar, a curated fruit basket is a bright, colourful alternative. It works for birthdays, thank-you gifts, and corporate milestones because it feels premium yet wholesome.\n\nChoose seasonal picks and add a handwritten note. Small touches make the gift more memorable and show you care about wellbeing as well as the occasion.\n\nWe can help you size the basket for the group and pick fruits that travel well if the gift will be carried or shipped.',
 1),
(30,
 'How Fruitwala Prepares Fresh Fruit Boxes Every Morning',
 'Take a look at our hygienic preparation process and how we ensure every fruit box is fresh, safe, and beautifully packed.',
 'Kitchen',
 'BTS',
 'assets/images/21.png',
 'Each morning our team sorts, washes where appropriate, and packs fruit under strict hygiene checks. Temperature and timing matter: we plan routes so boxes reach customers when produce is still at its best.\n\nBoxes are assembled in a clean workspace with attention to bruising, ripeness, and presentation so what you open at home matches what you saw online.\n\nIf you would like to know more about sourcing or handling, contact our team any time.',
 1);
