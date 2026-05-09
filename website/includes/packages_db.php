<?php

declare(strict_types=1);

/**
 * Public package cards (`packages` table).
 */

function fruitwala_packages_sql_path(): string
{
    return dirname(__DIR__) . '/admin/sql/packages.sql';
}

function fruitwala_packages_ensure_table(mysqli $conn): bool
{
    static $done = false;
    if ($done) {
        return true;
    }
    $path = fruitwala_packages_sql_path();
    if (!is_readable($path)) {
        return false;
    }
    $sql = file_get_contents($path);
    if ($sql === false || trim($sql) === '') {
        return false;
    }
    if (!mysqli_multi_query($conn, $sql)) {
        return false;
    }
    while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
        if ($res = mysqli_store_result($conn)) {
            mysqli_free_result($res);
        }
    }
    $done = true;
    fruitwala_packages_maybe_seed_defaults($conn);

    return true;
}

function fruitwala_packages_maybe_seed_defaults(mysqli $conn): void
{
    $c = 0;
    if ($q = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM packages')) {
        if ($r = mysqli_fetch_assoc($q)) {
            $c = (int) $r['c'];
        }
        mysqli_free_result($q);
    }
    if ($c > 0) {
        return;
    }
    $rows = [
        [
            10,
            'Weekly Trial Pack',
            '6 Days Delivery (Monday – Saturday)',
            '666',
            '900',
            'Sunday Off',
            'Free Delivery',
            "Fresh seasonal fruit box delivered daily\nHygienically cut and packed fruits\nPerfect for individuals & small families\nBalanced mix of 5–7 fruit varieties\nBoosts immunity & daily nutrition\nMorning doorstep delivery\nBox approx weight 600 gram",
            'Weekly',
            'assets/images/22.png',
        ],
        [
            20,
            'Monthly Value Pack',
            '26 Days Delivery (Full Month)',
            '2749',
            '3600',
            'Sunday Off',
            'Free Delivery',
            "Daily fresh fruit box for 26 days\nPremium fruit selection every week\nBest for families & working professionals\nConsistent quality & portion control\nImproves digestion & energy levels\nPriority delivery support\nBox approx weight 600 gram",
            'Monthly',
            'assets/images/23.png',
        ],
    ];
    $st = mysqli_prepare(
        $conn,
        'INSERT INTO packages (sort_order, title, delivery_line, sale_price, mrp, badge_1, badge_2, bullet_points, book_pkg_name, image, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,1)'
    );
    if (!$st) {
        return;
    }
    foreach ($rows as $row) {
        $sort = (int) $row[0];
        $title = (string) $row[1];
        $delivery = (string) $row[2];
        $sale = (string) $row[3];
        $mrp = (string) $row[4];
        $b1 = (string) $row[5];
        $b2 = (string) $row[6];
        $bullets = (string) $row[7];
        $book = (string) $row[8];
        $img = (string) $row[9];
        mysqli_stmt_bind_param($st, 'isssssssss', $sort, $title, $delivery, $sale, $mrp, $b1, $b2, $bullets, $book, $img);
        mysqli_stmt_execute($st);
    }
    mysqli_stmt_close($st);
}

/**
 * @return list<array<string, mixed>>
 */
function fruitwala_packages_load_public(mysqli $conn): array
{
    if (!fruitwala_packages_ensure_table($conn)) {
        return [];
    }
    $out = [];
    $sql = 'SELECT id, sort_order, title, delivery_line, sale_price, mrp, badge_1, badge_2, bullet_points, book_pkg_name, image
            FROM packages WHERE is_active = 1 ORDER BY sort_order ASC, id ASC';
    if ($q = mysqli_query($conn, $sql)) {
        while ($r = mysqli_fetch_assoc($q)) {
            $out[] = $r;
        }
        mysqli_free_result($q);
    }

    return $out;
}

/**
 * @return list<string>
 */
function fruitwala_package_bullet_lines(string $raw): array
{
    $raw = str_replace("\r\n", "\n", $raw);
    $lines = explode("\n", $raw);
    $out = [];
    foreach ($lines as $line) {
        $t = trim($line);
        if ($t !== '') {
            $out[] = $t;
        }
    }

    return $out;
}
