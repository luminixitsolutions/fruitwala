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
 * Plain-text snippet for img alt (strips markup from a home field).
 *
 * @param array<string, array<string, string>> $home
 */
function fruitwala_home_img_alt(array $home, string $sectionKey, string $fieldKey): string
{
    $v = strip_tags((string) ($home[$sectionKey][$fieldKey] ?? ''));
    $v = preg_replace('/\s+/u', ' ', trim($v)) ?? '';
    if (function_exists('mb_strlen') && function_exists('mb_substr') && mb_strlen($v, 'UTF-8') > 140) {
        $v = mb_substr($v, 0, 137, 'UTF-8') . '…';
    } elseif (strlen($v) > 140) {
        $v = substr($v, 0, 137) . '...';
    }

    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
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
    fruitwala_home_ensure_hero_slides_headline_columns($conn);

    return true;
}

/**
 * Add headline columns to `home_hero_slides` when missing (existing installs).
 */
function fruitwala_home_ensure_hero_slides_headline_columns(mysqli $conn): void
{
    $t = mysqli_query($conn, "SHOW TABLES LIKE 'home_hero_slides'");
    if (!$t || mysqli_num_rows($t) === 0) {
        if ($t) {
            mysqli_free_result($t);
        }

        return;
    }
    mysqli_free_result($t);

    $c1 = mysqli_query($conn, "SHOW COLUMNS FROM `home_hero_slides` LIKE 'headline_main'");
    if ($c1 && mysqli_num_rows($c1) === 0) {
        mysqli_query($conn, 'ALTER TABLE `home_hero_slides` ADD COLUMN `headline_main` varchar(180) NOT NULL DEFAULT \'\' AFTER `kicker`');
    }
    if ($c1) {
        mysqli_free_result($c1);
    }

    $c2 = mysqli_query($conn, "SHOW COLUMNS FROM `home_hero_slides` LIKE 'headline_sub'");
    if ($c2 && mysqli_num_rows($c2) === 0) {
        mysqli_query($conn, 'ALTER TABLE `home_hero_slides` ADD COLUMN `headline_sub` varchar(180) NOT NULL DEFAULT \'\' AFTER `headline_main`');
    }
    if ($c2) {
        mysqli_free_result($c2);
    }
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
 * @return list<array{id: int|string, sort_order: int, kicker: string, headline_main: string, headline_sub: string, description: string, btn_text: string, btn_url: string, image: string}>
 */
function fruitwala_home_hero_slides(mysqli $conn): array
{
    if (!fruitwala_home_ensure_hero_slides_table($conn)) {
        return fruitwala_home_hero_slides_fallback();
    }
    fruitwala_home_maybe_migrate_hero_slides($conn);
    $out = [];
    $sql = 'SELECT id, sort_order, kicker, headline_main, headline_sub, description, btn_text, btn_url, image FROM home_hero_slides ORDER BY sort_order ASC, id ASC';
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        return fruitwala_home_hero_slides_fallback();
    }
    while ($row = mysqli_fetch_assoc($res)) {
        $out[] = [
            'id' => (int) $row['id'],
            'sort_order' => (int) $row['sort_order'],
            'kicker' => (string) $row['kicker'],
            'headline_main' => (string) ($row['headline_main'] ?? ''),
            'headline_sub' => (string) ($row['headline_sub'] ?? ''),
            'description' => (string) $row['description'],
            'btn_text' => (string) $row['btn_text'],
            'btn_url' => (string) $row['btn_url'],
            'image' => (string) $row['image'],
        ];
    }
    mysqli_free_result($res);
    if ($out === []) {
        return fruitwala_home_hero_slides_fallback();
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
            'headline_main' => '',
            'headline_sub' => '',
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

function fruitwala_home_slide_headline_main_word(array $slide): string
{
    $v = trim((string) ($slide['headline_main'] ?? ''));

    return $v !== '' ? $v : 'Fruitwala';
}

/**
 * Letter spans for the animated hero title (UTF-8 safe).
 */
function fruitwala_home_slide_headline_main_chars_html(array $slide): string
{
    $word = fruitwala_home_slide_headline_main_word($slide);
    $chars = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
    if ($chars === false || $chars === []) {
        $chars = str_split($word);
    }
    $out = '';
    foreach ($chars as $ch) {
        $out .= '<span>' . htmlspecialchars($ch, ENT_QUOTES, 'UTF-8') . '</span>';
    }

    return $out;
}

function fruitwala_home_slide_headline_sub_h(array $slide): string
{
    $v = trim((string) ($slide['headline_sub'] ?? ''));

    return htmlspecialchars($v !== '' ? $v : 'Breakfast', ENT_QUOTES, 'UTF-8');
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
    $res = mysqli_query($conn, $sql);
    if (!$res) {
        return fruitwala_home_reels_fallback($conn);
    }
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
    if ($out === []) {
        return fruitwala_home_reels_fallback($conn);
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

function fruitwala_home_maybe_migrate_offer_banners(mysqli $conn): void
{
    static $ran = false;
    if ($ran) {
        return;
    }
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM home_offer_banners');
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
        'INSERT INTO home_offer_banners (sort_order, image, link) VALUES (?,?,?)'
    );
    if (!$stmt) {
        return;
    }
    $sort = 0;
    foreach ([1, 2] as $i) {
        $sk = 'sale_sm_' . $i;
        $h = $home[$sk] ?? [];
        $image = (string) ($h['image'] ?? '');
        $link = (string) ($h['link'] ?? '#!');
        $sort += 10;
        mysqli_stmt_bind_param($stmt, 'iss', $sort, $image, $link);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    $ran = true;
}

/**
 * @return list<array{id: int, sort_order: int, image: string, link: string}>
 */
function fruitwala_home_offer_banners(mysqli $conn): array
{
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return fruitwala_home_offer_banners_fallback($conn);
    }
    fruitwala_home_maybe_migrate_offer_banners($conn);
    $out = [];
    $sql = 'SELECT id, sort_order, image, link FROM home_offer_banners ORDER BY sort_order ASC, id ASC';
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = [
                'id' => (int) $row['id'],
                'sort_order' => (int) $row['sort_order'],
                'image' => (string) $row['image'],
                'link' => (string) $row['link'],
            ];
        }
        mysqli_free_result($res);
    }
    if ($out === []) {
        return fruitwala_home_offer_banners_fallback($conn);
    }

    return $out;
}

/**
 * @return list<array<string, int|string>>
 */
function fruitwala_home_offer_banners_fallback(mysqli $conn): array
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
            'image' => (string) ($h['image'] ?? ''),
            'link' => (string) ($h['link'] ?? '#!'),
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

function fruitwala_home_maybe_migrate_gallery_items(mysqli $conn): void
{
    static $ran = false;
    if ($ran) {
        return;
    }
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM home_gallery_items');
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
        'INSERT INTO home_gallery_items (sort_order, title, image) VALUES (?,?,?)'
    );
    if (!$stmt) {
        return;
    }
    $sort = 0;
    foreach ([1, 2, 3, 4] as $i) {
        $sk = 'gallery_side_' . $i;
        $h = $home[$sk] ?? [];
        $title = (string) ($h['title'] ?? '');
        $image = (string) ($h['thumb'] ?? '');
        $sort += 10;
        mysqli_stmt_bind_param($stmt, 'iss', $sort, $title, $image);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    $ran = true;
}

/**
 * @return list<array{id: int, sort_order: int, title: string, image: string}>
 */
function fruitwala_home_gallery_items(mysqli $conn): array
{
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return fruitwala_home_gallery_items_fallback($conn);
    }
    fruitwala_home_maybe_migrate_gallery_items($conn);
    $out = [];
    $sql = 'SELECT id, sort_order, title, image FROM home_gallery_items ORDER BY sort_order ASC, id ASC';
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = [
                'id' => (int) $row['id'],
                'sort_order' => (int) $row['sort_order'],
                'title' => (string) $row['title'],
                'image' => (string) $row['image'],
            ];
        }
        mysqli_free_result($res);
    }
    if ($out === []) {
        return fruitwala_home_gallery_items_fallback($conn);
    }

    return $out;
}

/**
 * @return list<array<string, int|string>>
 */
function fruitwala_home_gallery_items_fallback(mysqli $conn): array
{
    $home = fruitwala_home_load($conn);
    $out = [];
    $sort = 0;
    foreach ([1, 2, 3, 4] as $i) {
        $sk = 'gallery_side_' . $i;
        $h = $home[$sk] ?? [];
        $out[] = [
            'id' => $i,
            'sort_order' => $sort,
            'title' => (string) ($h['title'] ?? ''),
            'image' => (string) ($h['thumb'] ?? ''),
        ];
        $sort += 10;
    }

    return $out;
}

function fruitwala_home_maybe_migrate_gallery_strip_sidebar_rows(mysqli $conn): void
{
    static $ran = false;
    if ($ran) {
        return;
    }
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM home_gallery_strip_sidebar');
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
        'INSERT INTO home_gallery_strip_sidebar (sort_order, thumb, title, meta1, meta2, link) VALUES (?,?,?,?,?,?)'
    );
    if (!$stmt) {
        return;
    }
    $sort = 0;
    foreach ([1, 2, 3, 4] as $i) {
        $sk = 'gallery_side_' . $i;
        $h = $home[$sk] ?? [];
        $thumb = (string) ($h['thumb'] ?? '');
        $title = (string) ($h['title'] ?? '');
        $meta1 = (string) ($h['meta1'] ?? '');
        $meta2 = (string) ($h['meta2'] ?? '');
        $link = (string) ($h['link'] ?? '');
        if ($thumb === '' && $title === '' && $meta1 === '' && $meta2 === '' && $link === '') {
            continue;
        }
        $sort += 10;
        mysqli_stmt_bind_param($stmt, 'isssss', $sort, $thumb, $title, $meta1, $meta2, $link);
        mysqli_stmt_execute($stmt);
    }
    mysqli_stmt_close($stmt);
    $ran = true;
}

/**
 * @return list<array{id: int, sort_order: int, thumb: string, title: string, meta1: string, meta2: string, link: string}>
 */
function fruitwala_home_gallery_strip_sidebar_rows(mysqli $conn): array
{
    if (!fruitwala_home_ensure_home_list_tables($conn)) {
        return fruitwala_home_gallery_strip_sidebar_rows_fallback($conn);
    }
    fruitwala_home_maybe_migrate_gallery_strip_sidebar_rows($conn);
    $out = [];
    $sql = 'SELECT id, sort_order, thumb, title, meta1, meta2, link FROM home_gallery_strip_sidebar ORDER BY sort_order ASC, id ASC';
    if ($res = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($res)) {
            $out[] = [
                'id' => (int) $row['id'],
                'sort_order' => (int) $row['sort_order'],
                'thumb' => (string) $row['thumb'],
                'title' => (string) $row['title'],
                'meta1' => (string) $row['meta1'],
                'meta2' => (string) $row['meta2'],
                'link' => (string) $row['link'],
            ];
        }
        mysqli_free_result($res);
    }
    if ($out === []) {
        return fruitwala_home_gallery_strip_sidebar_rows_fallback($conn);
    }

    return $out;
}

/**
 * @return list<array<string, int|string>>
 */
function fruitwala_home_gallery_strip_sidebar_rows_fallback(mysqli $conn): array
{
    $home = fruitwala_home_load($conn);
    $out = [];
    $sort = 0;
    foreach ([1, 2, 3, 4] as $i) {
        $sk = 'gallery_side_' . $i;
        $h = $home[$sk] ?? [];
        $out[] = [
            'id' => $i,
            'sort_order' => $sort,
            'thumb' => (string) ($h['thumb'] ?? ''),
            'title' => (string) ($h['title'] ?? ''),
            'meta1' => (string) ($h['meta1'] ?? ''),
            'meta2' => (string) ($h['meta2'] ?? ''),
            'link' => (string) ($h['link'] ?? ''),
        ];
        $sort += 10;
    }

    return $out;
}
