<?php
/**
 * Portfolio reels for the public portfolio page and admin.
 *
 * @param mysqli $conn
 */
function fruitwala_portfolio_ensure_table(mysqli $conn): void
{
    $sql = <<<'SQL'
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL;
    mysqli_query($conn, $sql);
}

/**
 * @return array<int, array{id:int,sort_order:int,label:string,video:string,cover:string,alt:string,is_active:int}>
 */
function fruitwala_get_portfolio_items(mysqli $conn, bool $activeOnly): array
{
    $items = [];
    $sql = $activeOnly
        ? 'SELECT id, sort_order, label, video, cover, alt, is_active FROM portfolio_items WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        : 'SELECT id, sort_order, label, video, cover, alt, is_active FROM portfolio_items ORDER BY sort_order ASC, id ASC';
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = [
                'id' => (int) $row['id'],
                'sort_order' => (int) $row['sort_order'],
                'label' => (string) $row['label'],
                'video' => (string) $row['video'],
                'cover' => (string) $row['cover'],
                'alt' => (string) $row['alt'],
                'is_active' => (int) $row['is_active'],
            ];
        }
        mysqli_free_result($result);
    }
    return $items;
}
