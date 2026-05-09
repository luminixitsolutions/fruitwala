<?php
declare(strict_types=1);

/**
 * About Us page content (`about_us_content` single row, id = 1).
 */

/**
 * @return array<string, string>
 */
function fruitwala_about_us_defaults(): array
{
    return [
        'breadcrumb_title' => 'About us',
        'badge_text' => 'FRESH • HEALTHY • NATURAL',
        'heading_html' => 'Your Trusted <font><span>F</span><span>r</span><span>u</span><span>i</span><span>t</span></font> Breakfast Partner',
        'subtitle' => 'Fruitwala Breakfast delivers fresh fruit boxes and premium fruit baskets prepared daily with care and hygiene.',
        'body_text' => 'We source high-quality seasonal fruits, clean and pack them in a hygienic environment, and deliver straight to your doorstep. Perfect for healthy breakfasts, office fruit breaks, and thoughtful fruit gifting.',
        'hero_image' => 'assets/images/18.webp',
        'hero_image_alt' => 'Fresh Fruit Basket',
        'btn_text' => 'Contact Us Now!',
        'btn_url' => 'contact.php',
    ];
}

function fruitwala_about_us_ensure_table(mysqli $conn): bool
{
    static $done = false;
    if ($done) {
        return true;
    }
    $path = dirname(__DIR__) . '/admin/sql/about_us_content.sql';
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

function fruitwala_about_us_ensure_default_row(mysqli $conn): void
{
    static $done = false;
    if ($done) {
        return;
    }
    if (!fruitwala_about_us_ensure_table($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT id FROM about_us_content WHERE id = 1 LIMIT 1');
    if ($chk && mysqli_num_rows($chk) > 0) {
        mysqli_free_result($chk);
        $done = true;
        return;
    }
    if ($chk) {
        mysqli_free_result($chk);
    }

    $d = fruitwala_about_us_defaults();
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
        'INSERT INTO about_us_content (id, breadcrumb_title, badge_text, heading_html, subtitle, body_text, hero_image, hero_image_alt, btn_text, btn_url)
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
function fruitwala_about_us_load(mysqli $conn): array
{
    $defaults = fruitwala_about_us_defaults();
    if (!fruitwala_about_us_ensure_table($conn)) {
        return $defaults;
    }
    fruitwala_about_us_ensure_default_row($conn);

    $sql = 'SELECT breadcrumb_title, badge_text, heading_html, subtitle, body_text, hero_image, hero_image_alt, btn_text, btn_url FROM about_us_content WHERE id = 1 LIMIT 1';
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

function fruitwala_about_us_h(array $about, string $key): string
{
    $v = $about[$key] ?? '';

    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

/**
 * Limited HTML for heading (font + span letters + basic inline).
 */
function fruitwala_about_us_heading_html(array $about): string
{
    $v = (string) ($about['heading_html'] ?? '');

    return strip_tags($v, '<font><b><strong><i><em><span><br>');
}
