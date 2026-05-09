<?php
/**
 * Homepage key/value content from `home_page_fields` merged with PHP defaults.
 */

/**
 * @return array<string, array<string, string>>
 */
function fruitwala_home_defaults(): array
{
    /** @var array<string, array<string, string>> $d */
    $d = require __DIR__ . '/home_defaults.php';
    return $d;
}

/**
 * @return array<string, array<string, string>>
 */
function fruitwala_home_load(mysqli $conn): array
{
    $merged = fruitwala_home_defaults();
    $chk = mysqli_query($conn, "SHOW TABLES LIKE 'home_page_fields'");
    if (!$chk || mysqli_num_rows($chk) === 0) {
        if ($chk) {
            mysqli_free_result($chk);
        }
        return $merged;
    }
    mysqli_free_result($chk);
    $sql = 'SELECT section_key, field_key, field_value FROM home_page_fields';
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $sk = (string) $row['section_key'];
            $fk = (string) $row['field_key'];
            if (!isset($merged[$sk])) {
                $merged[$sk] = [];
            }
            $merged[$sk][$fk] = (string) $row['field_value'];
        }
        mysqli_free_result($res);
    }
    return $merged;
}

/**
 * @param array<string, array<string, string>> $home
 */
function fruitwala_home_h(array $home, string $sectionKey, string $fieldKey): string
{
    $v = $home[$sectionKey][$fieldKey] ?? '';
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

/**
 * Limited HTML for titles (e.g. &lt;font&gt;). Strip everything else.
 *
 * @param array<string, array<string, string>> $home
 */
function fruitwala_home_title_html(array $home, string $sectionKey, string $fieldKey): string
{
    $v = $home[$sectionKey][$fieldKey] ?? '';
    return strip_tags((string) $v, '<font><b><strong><i><em><br>');
}

/**
 * Ensure `home_hero_slides` exists (same SQL as admin install).
 */
function fruitwala_home_ensure_hero_slides_table(mysqli $conn): bool
{
    static $done = false;
    if ($done) {
        return true;
    }
    $path = dirname(__DIR__) . '/admin/sql/home_hero_slides.sql';
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

/**
 * One-time copy from merged hero_slide_1 / hero_slide_2 key-value data when the slides table is empty.
 */
function fruitwala_home_maybe_migrate_hero_slides(mysqli $conn): void
{
    static $ran = false;
    if ($ran) {
        return;
    }
    if (!fruitwala_home_ensure_hero_slides_table($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM home_hero_slides');
    if (!$chk) {
        return;
    }
    $row = mysqli_fetch_assoc($chk);
    mysqli_free_result($chk);
    if ((int) ($row['c'] ?? 0) > 0) {
        $ran = true;
        return;
    }

    $home = fruitwala_home_load($conn);
    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO home_hero_slides (sort_order, kicker, description, btn_text, btn_url, image) VALUES (?,?,?,?,?,?)'
    );
    if (!$stmt) {
        return;
    }
    $sort = 0;
    foreach ([1, 2] as $i) {
        $sk = 'hero_slide_' . $i;
        $h = $home[$sk] ?? [];
        $kicker = (string) ($h['kicker'] ?? '');
        $description = (string) ($h['description'] ?? '');
        $btnText = (string) ($h['btn_text'] ?? '');
        $btnUrl = (string) ($h['btn_url'] ?? '');
        $image = (string) ($h['image'] ?? '');
        mysqli_stmt_bind_param($stmt, 'isssss', $sort, $kicker, $description, $btnText, $btnUrl, $image);
        mysqli_stmt_execute($stmt);
        $sort += 10;
    }
    mysqli_stmt_close($stmt);
    $ran = true;
}

/**
 * @return list<array{id: int|string, sort_order: int, kicker: string, description: string, btn_text: string, btn_url: string, image: string}>
 */
function fruitwala_home_hero_slides(mysqli $conn): array
{
    if (!fruitwala_home_ensure_hero_slides_table($conn)) {
        return fruitwala_home_hero_slides_fallback();
    }
    fruitwala_home_maybe_migrate_hero_slides($conn);
    $out = [];
    $sql = 'SELECT id, sort_order, kicker, description, btn_text, btn_url, image FROM home_hero_slides ORDER BY sort_order ASC, id ASC';
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = [
                'id' => (int) $row['id'],
                'sort_order' => (int) $row['sort_order'],
                'kicker' => (string) $row['kicker'],
                'description' => (string) $row['description'],
                'btn_text' => (string) $row['btn_text'],
                'btn_url' => (string) $row['btn_url'],
                'image' => (string) $row['image'],
            ];
        }
        mysqli_free_result($res);
    }
    return $out;
}

/**
 * @return list<array<string, int|string>>
 */
function fruitwala_home_hero_slides_fallback(): array
{
    $d = fruitwala_home_defaults();
    $out = [];
    $sort = 0;
    foreach ([1, 2] as $i) {
        $sk = 'hero_slide_' . $i;
        if (!isset($d[$sk])) {
            continue;
        }
        $h = $d[$sk];
        $out[] = [
            'id' => $i,
            'sort_order' => $sort,
            'kicker' => (string) ($h['kicker'] ?? ''),
            'description' => (string) ($h['description'] ?? ''),
            'btn_text' => (string) ($h['btn_text'] ?? ''),
            'btn_url' => (string) ($h['btn_url'] ?? ''),
            'image' => (string) ($h['image'] ?? ''),
        ];
        $sort += 10;
    }
    return $out;
}

function fruitwala_home_slide_h(array $slide, string $key): string
{
    $v = $slide[$key] ?? '';

    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

/**
 * @param array<string, mixed> $row
 */
function fruitwala_home_row_h(array $row, string $key): string
{
    $v = $row[$key] ?? '';

    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}

/**
 * @param array<string, mixed> $row
 */
function fruitwala_home_sale_banner_title_html(array $row): string
{
    $v = (string) ($row['title'] ?? '');

    return strip_tags($v, '<font><b><strong><i><em><br>');
}

function fruitwala_home_ensure_home_list_tables(mysqli $conn): bool
{
    static $done = false;
    if ($done) {
        return true;
    }
    $path = dirname(__DIR__) . '/admin/sql/home_list_tables.sql';
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

function fruitwala_home_maybe_migrate_reels(mysqli $conn): void
{
    static $ran = false;
    if ($ran) {
        return;
    }
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM home_reels');
    if (!$chk) {
        return;
    }
    $row = mysqli_fetch_assoc($chk);
    mysqli_free_result($chk);
    if ((int) ($row['c'] ?? 0) > 0) {
        $ran = true;

        return;
    }
    $home = fruitwala_home_load($conn);
    $stmt = mysqli_prepare($conn, 'INSERT INTO home_reels (sort_order, video, cover, alt) VALUES (?,?,?,?)');
    if (!$stmt) {
        return;
    }
    $sort = 0;
    foreach ([1, 2, 3, 4] as $i) {
        $sk = 'reel_' . $i;
        $h = $home[$sk] ?? [];
        $video = (string) ($h['video'] ?? '');
        $cover = (string) ($h['cover'] ?? '');
        $alt = (string) ($h['alt'] ?? '');
        $sort += 10;
        mysqli_stmt_bind_param($stmt, 'isss', $sort, $video, $cover, $alt);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    $ran = true;
}

/**
 * @return list<array{id: int, sort_order: int, video: string, cover: string, alt: string}>
 */
function fruitwala_home_reels(mysqli $conn): array
{
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return fruitwala_home_reels_fallback($conn);
    }
    fruitwala_home_maybe_migrate_reels($conn);
    $out = [];
    $sql = 'SELECT id, sort_order, video, cover, alt FROM home_reels ORDER BY sort_order ASC, id ASC';
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = [
                'id' => (int) $row['id'],
                'sort_order' => (int) $row['sort_order'],
                'video' => (string) $row['video'],
                'cover' => (string) $row['cover'],
                'alt' => (string) $row['alt'],
            ];
        }
        mysqli_free_result($res);
    }

    return $out;
}

/**
 * @return list<array<string, int|string>>
 */
function fruitwala_home_reels_fallback(mysqli $conn): array
{
    $home = fruitwala_home_load($conn);
    $out = [];
    $sort = 0;
    foreach ([1, 2, 3, 4] as $i) {
        $sk = 'reel_' . $i;
        $h = $home[$sk] ?? [];
        $out[] = [
            'id' => $i,
            'sort_order' => $sort,
            'video' => (string) ($h['video'] ?? ''),
            'cover' => (string) ($h['cover'] ?? ''),
            'alt' => (string) ($h['alt'] ?? ''),
        ];
        $sort += 10;
    }

    return $out;
}

function fruitwala_home_maybe_migrate_sale_banners(mysqli $conn): void
{
    static $ran = false;
    if ($ran) {
        return;
    }
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM home_sale_banners');
    if (!$chk) {
        return;
    }
    $row = mysqli_fetch_assoc($chk);
    mysqli_free_result($chk);
    if ((int) ($row['c'] ?? 0) > 0) {
        $ran = true;

        return;
    }
    $home = fruitwala_home_load($conn);
    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO home_sale_banners (sort_order, title, subtitle, image, link) VALUES (?,?,?,?,?)'
    );
    if (!$stmt) {
        return;
    }
    $sort = 0;
    foreach ([1, 2] as $i) {
        $sk = 'sale_sm_' . $i;
        $h = $home[$sk] ?? [];
        $title = (string) ($h['title'] ?? '');
        $subtitle = (string) ($h['subtitle'] ?? '');
        $image = (string) ($h['image'] ?? '');
        $link = (string) ($h['link'] ?? '');
        $sort += 10;
        mysqli_stmt_bind_param($stmt, 'issss', $sort, $title, $subtitle, $image, $link);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    $ran = true;
}

/**
 * @return list<array{id: int, sort_order: int, title: string, subtitle: string, image: string, link: string}>
 */
function fruitwala_home_sale_banners(mysqli $conn): array
{
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return fruitwala_home_sale_banners_fallback($conn);
    }
    fruitwala_home_maybe_migrate_sale_banners($conn);
    $out = [];
    $sql = 'SELECT id, sort_order, title, subtitle, image, link FROM home_sale_banners ORDER BY sort_order ASC, id ASC';
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = [
                'id' => (int) $row['id'],
                'sort_order' => (int) $row['sort_order'],
                'title' => (string) $row['title'],
                'subtitle' => (string) $row['subtitle'],
                'image' => (string) $row['image'],
                'link' => (string) $row['link'],
            ];
        }
        mysqli_free_result($res);
    }

    return $out;
}

/**
 * @return list<array<string, int|string>>
 */
function fruitwala_home_sale_banners_fallback(mysqli $conn): array
{
    $home = fruitwala_home_load($conn);
    $out = [];
    $sort = 0;
    foreach ([1, 2] as $i) {
        $sk = 'sale_sm_' . $i;
        $h = $home[$sk] ?? [];
        $out[] = [
            'id' => $i,
            'sort_order' => $sort,
            'title' => (string) ($h['title'] ?? ''),
            'subtitle' => (string) ($h['subtitle'] ?? ''),
            'image' => (string) ($h['image'] ?? ''),
            'link' => (string) ($h['link'] ?? ''),
        ];
        $sort += 10;
    }

    return $out;
}

function fruitwala_home_maybe_migrate_services(mysqli $conn): void
{
    static $ran = false;
    if ($ran) {
        return;
    }
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM home_services');
    if (!$chk) {
        return;
    }
    $row = mysqli_fetch_assoc($chk);
    mysqli_free_result($chk);
    if ((int) ($row['c'] ?? 0) > 0) {
        $ran = true;

        return;
    }
    $home = fruitwala_home_load($conn);
    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO home_services (sort_order, title, subtitle, icon) VALUES (?,?,?,?)'
    );
    if (!$stmt) {
        return;
    }
    $sort = 0;
    foreach ([1, 2, 3, 4] as $i) {
        $sk = 'service_' . $i;
        $h = $home[$sk] ?? [];
        $title = (string) ($h['title'] ?? '');
        $subtitle = (string) ($h['subtitle'] ?? '');
        $icon = (string) ($h['icon'] ?? '');
        $sort += 10;
        mysqli_stmt_bind_param($stmt, 'isss', $sort, $title, $subtitle, $icon);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    $ran = true;
}

/**
 * @return list<array{id: int, sort_order: int, title: string, subtitle: string, icon: string}>
 */
function fruitwala_home_services(mysqli $conn): array
{
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return fruitwala_home_services_fallback($conn);
    }
    fruitwala_home_maybe_migrate_services($conn);
    $out = [];
    $sql = 'SELECT id, sort_order, title, subtitle, icon FROM home_services ORDER BY sort_order ASC, id ASC';
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = [
                'id' => (int) $row['id'],
                'sort_order' => (int) $row['sort_order'],
                'title' => (string) $row['title'],
                'subtitle' => (string) $row['subtitle'],
                'icon' => (string) $row['icon'],
            ];
        }
        mysqli_free_result($res);
    }

    return $out;
}

/**
 * @return list<array<string, int|string>>
 */
function fruitwala_home_services_fallback(mysqli $conn): array
{
    $home = fruitwala_home_load($conn);
    $out = [];
    $sort = 0;
    foreach ([1, 2, 3, 4] as $i) {
        $sk = 'service_' . $i;
        $h = $home[$sk] ?? [];
        $out[] = [
            'id' => $i,
            'sort_order' => $sort,
            'title' => (string) ($h['title'] ?? ''),
            'subtitle' => (string) ($h['subtitle'] ?? ''),
            'icon' => (string) ($h['icon'] ?? ''),
        ];
        $sort += 10;
    }

    return $out;
}

function fruitwala_home_maybe_migrate_instagram_tiles(mysqli $conn): void
{
    static $ran = false;
    if ($ran) {
        return;
    }
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM home_instagram_tiles');
    if (!$chk) {
        return;
    }
    $row = mysqli_fetch_assoc($chk);
    mysqli_free_result($chk);
    if ((int) ($row['c'] ?? 0) > 0) {
        $ran = true;

        return;
    }
    $home = fruitwala_home_load($conn);
    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO home_instagram_tiles (sort_order, popup, img, alt) VALUES (?,?,?,?)'
    );
    if (!$stmt) {
        return;
    }
    $sort = 0;
    foreach ([1, 2, 3, 4, 5] as $i) {
        $sk = 'ig_' . $i;
        $h = $home[$sk] ?? [];
        $popup = (string) ($h['popup'] ?? '');
        $img = (string) ($h['img'] ?? '');
        $alt = (string) ($h['alt'] ?? '');
        $sort += 10;
        mysqli_stmt_bind_param($stmt, 'isss', $sort, $popup, $img, $alt);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    $ran = true;
}

/**
 * @return list<array{id: int, sort_order: int, popup: string, img: string, alt: string}>
 */
function fruitwala_home_instagram_tiles(mysqli $conn): array
{
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return fruitwala_home_instagram_tiles_fallback($conn);
    }
    fruitwala_home_maybe_migrate_instagram_tiles($conn);
    $out = [];
    $sql = 'SELECT id, sort_order, popup, img, alt FROM home_instagram_tiles ORDER BY sort_order ASC, id ASC';
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = [
                'id' => (int) $row['id'],
                'sort_order' => (int) $row['sort_order'],
                'popup' => (string) $row['popup'],
                'img' => (string) $row['img'],
                'alt' => (string) $row['alt'],
            ];
        }
        mysqli_free_result($res);
    }

    return $out;
}

/**
 * @return list<array<string, int|string>>
 */
function fruitwala_home_instagram_tiles_fallback(mysqli $conn): array
{
    $home = fruitwala_home_load($conn);
    $out = [];
    $sort = 0;
    foreach ([1, 2, 3, 4, 5] as $i) {
        $sk = 'ig_' . $i;
        $h = $home[$sk] ?? [];
        $out[] = [
            'id' => $i,
            'sort_order' => $sort,
            'popup' => (string) ($h['popup'] ?? ''),
            'img' => (string) ($h['img'] ?? ''),
            'alt' => (string) ($h['alt'] ?? ''),
        ];
        $sort += 10;
    }

    return $out;
}

function fruitwala_home_maybe_migrate_testimonials(mysqli $conn): void
{
    static $ran = false;
    if ($ran) {
        return;
    }
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM home_testimonials');
    if (!$chk) {
        return;
    }
    $row = mysqli_fetch_assoc($chk);
    mysqli_free_result($chk);
    if ((int) ($row['c'] ?? 0) > 0) {
        $ran = true;

        return;
    }
    $home = fruitwala_home_load($conn);
    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO home_testimonials (sort_order, heading, body, author, image) VALUES (?,?,?,?,?)'
    );
    if (!$stmt) {
        return;
    }
    $sort = 0;
    foreach ([1, 2, 3, 4, 5] as $i) {
        $sk = 'testimonial_' . $i;
        $h = $home[$sk] ?? [];
        $heading = (string) ($h['heading'] ?? '');
        $body = (string) ($h['body'] ?? '');
        $author = (string) ($h['author'] ?? '');
        $image = (string) ($h['image'] ?? '');
        $sort += 10;
        mysqli_stmt_bind_param($stmt, 'issss', $sort, $heading, $body, $author, $image);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    $ran = true;
}

/**
 * @return list<array{id: int, sort_order: int, heading: string, body: string, author: string, image: string}>
 */
function fruitwala_home_testimonials(mysqli $conn): array
{
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return fruitwala_home_testimonials_fallback($conn);
    }
    fruitwala_home_maybe_migrate_testimonials($conn);
    $out = [];
    $sql = 'SELECT id, sort_order, heading, body, author, image FROM home_testimonials ORDER BY sort_order ASC, id ASC';
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = [
                'id' => (int) $row['id'],
                'sort_order' => (int) $row['sort_order'],
                'heading' => (string) $row['heading'],
                'body' => (string) $row['body'],
                'author' => (string) $row['author'],
                'image' => (string) $row['image'],
            ];
        }
        mysqli_free_result($res);
    }

    return $out;
}

/**
 * @return list<array<string, int|string>>
 */
function fruitwala_home_testimonials_fallback(mysqli $conn): array
{
    $home = fruitwala_home_load($conn);
    $out = [];
    $sort = 0;
    foreach ([1, 2, 3, 4, 5] as $i) {
        $sk = 'testimonial_' . $i;
        $h = $home[$sk] ?? [];
        $out[] = [
            'id' => $i,
            'sort_order' => $sort,
            'heading' => (string) ($h['heading'] ?? ''),
            'body' => (string) ($h['body'] ?? ''),
            'author' => (string) ($h['author'] ?? ''),
            'image' => (string) ($h['image'] ?? ''),
        ];
        $sort += 10;
    }

    return $out;
}
