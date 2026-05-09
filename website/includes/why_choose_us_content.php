<?php
declare(strict_types=1);

/**
 * Why Choose Us page content (`why_choose_us_content` single row, id = 1).
 */

/**
 * @return array<string, string>
 */
function fruitwala_why_choose_us_defaults(): array
{
    return [
        'breadcrumb_title' => 'Why Choose Us',
        'badge_text' => 'WHY FRUITWALA BREAKFAST',
        'heading_html' => 'Why Choose <font><span>F</span><span>r</span><span>u</span><span>i</span><span>t</span><span>w</span><span>a</span><span>l</span><span>a</span></font>',
        'subtitle' => 'Fresh fruits, hygienic packing, and curated boxes — everything you need for a healthy breakfast and joyful gifting.',
        'body_text' => 'We combine farm-fresh quality with careful cleaning and beautiful presentation. Explore the three pillars that make Fruitwala Breakfast a trusted choice for families, offices, and celebrations.',
        'hero_image' => 'assets/images/21.png',
        'hero_image_alt' => 'Fresh seasonal fruits',
        'btn_text' => 'Contact Us Now!',
        'btn_url' => 'contact.php',
    ];
}

function fruitwala_why_choose_us_ensure_table(mysqli $conn): bool
{
    static $done = false;
    if ($done) {
        return true;
    }
    $path = dirname(__DIR__) . '/admin/sql/why_choose_us_content.sql';
    if (!is_readable($path)) {
        return false;
    }
    $sql = file_get_contents($path);
    if ($sql === false || trim($sql) === '') {
        return false;
    }
    mysqli_multi_query($conn, $sql);
    while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
        if ($res = mysqli_store_result($conn)) {
            mysqli_free_result($res);
        }
    }
    $done = true;
    return true;
}

function fruitwala_why_choose_us_ensure_default_row(mysqli $conn): void
{
    static $done = false;
    if ($done) {
        return;
    }
    if (!fruitwala_why_choose_us_ensure_table($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT id FROM why_choose_us_content WHERE id = 1 LIMIT 1');
    if ($chk && mysqli_num_rows($chk) > 0) {
        mysqli_free_result($chk);
        $done = true;
        return;
    }
    if ($chk) {
        mysqli_free_result($chk);
    }

    $d = fruitwala_why_choose_us_defaults();
    $b0 = $d['breadcrumb_title'];
    $b1 = $d['badge_text'];
    $b2 = $d['heading_html'];
    $b3 = $d['subtitle'];
    $b4 = $d['body_text'];
    $b5 = $d['hero_image'];
    $b6 = $d['hero_image_alt'];
    $b7 = $d['btn_text'];
    $b8 = $d['btn_url'];
    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO why_choose_us_content (id, breadcrumb_title, badge_text, heading_html, subtitle, body_text, hero_image, hero_image_alt, btn_text, btn_url)
         VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    if (!$stmt) {
        return;
    }
    mysqli_stmt_bind_param($stmt, 'sssssssss', $b0, $b1, $b2, $b3, $b4, $b5, $b6, $b7, $b8);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $done = true;
}

/**
 * @return array<string, string>
 */
function fruitwala_why_choose_us_load(mysqli $conn): array
{
    $defaults = fruitwala_why_choose_us_defaults();
    if (!fruitwala_why_choose_us_ensure_table($conn)) {
        return $defaults;
    }
    fruitwala_why_choose_us_ensure_default_row($conn);

    $sql = 'SELECT breadcrumb_title, badge_text, heading_html, subtitle, body_text, hero_image, hero_image_alt, btn_text, btn_url FROM why_choose_us_content WHERE id = 1 LIMIT 1';
    if (!$res = mysqli_query($conn, $sql)) {
        return $defaults;
    }
    $row = mysqli_fetch_assoc($res);
    mysqli_free_result($res);
    if (!is_array($row)) {
        return $defaults;
    }
    $out = $defaults;
    foreach ($row as $k => $v) {
        $out[(string) $k] = (string) $v;
    }
    return $out;
}

function fruitwala_why_choose_us_h(array $row, string $key): string
{
    $v = $row[$key] ?? '';

    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

/**
 * Limited HTML for heading (font + span letters + basic inline).
 */
function fruitwala_why_choose_us_heading_html(array $row): string
{
    $v = (string) ($row['heading_html'] ?? '');

    return strip_tags($v, '<font><b><strong><i><em><span><br>');
}
