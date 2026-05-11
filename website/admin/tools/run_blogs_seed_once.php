<?php
/**
 * CLI: php run_blogs_seed_once.php
 * Applies ../sql/blogs_seed.sql using website config (same DB as blogs.php).
 */
declare(strict_types=1);

$root = dirname(__DIR__, 2);
require_once $root . '/config.php';

$path = dirname(__DIR__) . '/sql/blogs_seed.sql';
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

$q = mysqli_query($conn, 'SELECT id, sort_order, title FROM blogs WHERE is_active = 1 ORDER BY sort_order ASC, id ASC');
if ($q) {
    echo "Active blogs:\n";
    while ($row = mysqli_fetch_assoc($q)) {
        echo "  #{$row['id']} ({$row['sort_order']}) {$row['title']}\n";
    }
    mysqli_free_result($q);
}
echo "Done.\n";
