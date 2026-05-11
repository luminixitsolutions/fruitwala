-- Default portfolio reels for portfolio.php (same paths as legacy hardcoded cards).
-- Re-run safe: removes only these `video` paths, then re-inserts.

DELETE FROM `portfolio_items` WHERE `video` IN (
  'assets/videos/reels/reel1.mp4',
  'assets/videos/reels/reel2.mp4',
  'assets/videos/reels/reel3.mp4',
  'assets/videos/reels/reel4.mp4',
  'assets/videos/reels/reel5.mp4',
  'assets/videos/reels/reel13.mp4',
  'assets/videos/reels/reel7.mp4',
  'assets/videos/reels/reel8.mp4',
  'assets/videos/reels/reel9.mp4',
  'assets/videos/reels/reel10.mp4',
  'assets/videos/reels/reel11.mp4',
  'assets/videos/reels/reel12.mp4',
  'assets/videos/reels/reel14.mp4',
  'assets/videos/reels/reel6.mp4'
);

INSERT INTO `portfolio_items` (`sort_order`, `label`, `video`, `cover`, `alt`, `is_active`) VALUES
(10, 'Reel 1', 'assets/videos/reels/reel1.mp4', 'assets/videos/reels/cover1.png', 'Fruitwala Reel', 1),
(20, 'Reel 2', 'assets/videos/reels/reel2.mp4', 'assets/videos/reels/cover2.png', 'Fruitwala Reel', 1),
(30, 'Reel 3', 'assets/videos/reels/reel3.mp4', 'assets/videos/reels/cover3.jpg', 'Fruitwala Reel', 1),
(40, 'Reel 4', 'assets/videos/reels/reel4.mp4', 'assets/videos/reels/cover4.jpg', 'Fruitwala Reel', 1),
(50, 'Reel 5', 'assets/videos/reels/reel5.mp4', 'assets/videos/reels/cover5.jpg', 'Fruitwala Reel', 1),
(60, 'Reel 6', 'assets/videos/reels/reel13.mp4', 'assets/videos/reels/cover13.png', 'Fruitwala Reel', 1),
(70, 'Reel 7', 'assets/videos/reels/reel7.mp4', 'assets/videos/reels/cover7.jpg', 'Fruitwala Reel', 1),
(80, 'Reel 8', 'assets/videos/reels/reel8.mp4', 'assets/videos/reels/cover8.jpg', 'Fruitwala Reel', 1),
(90, 'Reel 9', 'assets/videos/reels/reel9.mp4', 'assets/videos/reels/cover9.jpg', 'Fruitwala Reel', 1),
(100, 'Reel 10', 'assets/videos/reels/reel10.mp4', 'assets/videos/reels/cover10.jpg', 'Fruitwala Reel', 1),
(110, 'Reel 11', 'assets/videos/reels/reel11.mp4', 'assets/videos/reels/cover11.jpg', 'Fruitwala Reel', 1),
(120, 'Reel 12', 'assets/videos/reels/reel12.mp4', 'assets/videos/reels/cover12.jpg', 'Fruitwala Reel', 1),
(130, 'Reel 13', 'assets/videos/reels/reel14.mp4', 'assets/videos/reels/cover14.jpg', 'Fruitwala Reel', 1),
(140, 'Reel 14', 'assets/videos/reels/reel6.mp4', 'assets/videos/reels/cover6.jpg', 'Fruitwala Reel', 1);
