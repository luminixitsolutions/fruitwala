<?php
declare(strict_types=1);

/**
 * Ensure blogs table exists (for installs before this feature).
 */
function fruitwala_blogs_ensure_table(mysqli $conn): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $path = dirname(__DIR__) . '/admin/sql/blogs_table.sql';
    if (!is_readable($path)) {
        return;
    }
    $sql = file_get_contents($path);
    if ($sql === false || trim($sql) === '') {
        return;
    }
    mysqli_multi_query($conn, $sql);
    while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
        if ($res = mysqli_store_result($conn)) {
            mysqli_free_result($res);
        }
    }
    $done = true;
}

/**
 * @return list<array<string, mixed>>
 */
function fruitwala_blogs_list_active(mysqli $conn): array
{
    fruitwala_blogs_ensure_table($conn);
    $rows = [];
    $q = mysqli_query(
        $conn,
        'SELECT id, title, excerpt, author, category, image FROM blogs WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
    );
    if ($q) {
        while ($r = mysqli_fetch_assoc($q)) {
            $rows[] = $r;
        }
        mysqli_free_result($q);
    }

    return $rows;
}

/**
 * @return array<string, mixed>|null
 */
function fruitwala_blogs_get_by_id(mysqli $conn, int $id, bool $allowInactive = false): ?array
{
    if ($id < 1) {
        return null;
    }
    fruitwala_blogs_ensure_table($conn);
    $stmt = mysqli_prepare(
        $conn,
        $allowInactive
            ? 'SELECT id, title, excerpt, author, category, image, content, is_active FROM blogs WHERE id = ? LIMIT 1'
            : 'SELECT id, title, excerpt, author, category, image, content, is_active FROM blogs WHERE id = ? AND is_active = 1 LIMIT 1'
    );
    if (!$stmt) {
        return null;
    }
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = $res ? mysqli_fetch_assoc($res) : null;
    mysqli_stmt_close($stmt);
    if (!is_array($row)) {
        return null;
    }

    return $row;
}

function fruitwala_blogs_trunc(string $s, int $max): string
{
    $s = trim($s);
    if ($max < 1 || $s === '') {
        return $s;
    }
    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($s, 'UTF-8') <= $max) {
            return $s;
        }

        return rtrim(mb_substr($s, 0, $max - 1, 'UTF-8')) . '…';
    }
    if (strlen($s) <= $max) {
        return $s;
    }

    return rtrim(substr($s, 0, $max - 1)) . '…';
}

function fruitwala_blogs_format_body_html(string $raw): string
{
    $raw = trim(str_replace("\r\n", "\n", $raw));
    if ($raw === '') {
        return '';
    }
    $parts = preg_split("/\n\s*\n/", $raw);
    if (!is_array($parts) || $parts === []) {
        return '<p class="blog_desc py-3">' . nl2br(htmlspecialchars($raw, ENT_QUOTES, 'UTF-8')) . '</p>';
    }
    $html = '';
    foreach ($parts as $p) {
        $p = trim((string) $p);
        if ($p === '') {
            continue;
        }
        $html .= '<p class="blog_desc py-3">' . nl2br(htmlspecialchars($p, ENT_QUOTES, 'UTF-8')) . '</p>';
    }

    return $html;
}
