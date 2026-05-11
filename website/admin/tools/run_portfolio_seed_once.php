<?php
/**
 * CLI: php run_portfolio_seed_once.php
 * Applies ../sql/portfolio_items_seed.sql (default reels for portfolio.php).
 */
declare(strict_types=1);

$root = dirname(__DIR__, 2);
require_once $root . '/config.php';
require_once $root . '/includes/portfolio_items.php';

fruitwala_portfolio_ensure_table($conn);

$path = dirname(__DIR__) . '/sql/portfolio_items_seed.sql';
$sql = is_readable($path) ? file_get_contents($path) : false;
if ($sql === false || trim($sql) === '') {
    fwrite(STDERR, "Cannot read: {$path}\n");
    exit(1);
}

if (!mysqli_multi_query($conn, $sql)) {
    fwrite(STDERR, mysqli_error($conn) . "\n");
    exit(1);
}
while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
    if ($res = mysqli_store_result($conn)) {
        mysqli_free_result($res);
    }
}

$items = fruitwala_get_portfolio_items($conn, false);
echo "Portfolio items (all): " . count($items) . "\n";
foreach ($items as $row) {
    echo "  #{$row['id']} ({$row['sort_order']}) {$row['label']} → {$row['video']}\n";
}
echo "Done.\n";
