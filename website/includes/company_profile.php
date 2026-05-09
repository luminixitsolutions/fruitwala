<?php
declare(strict_types=1);

/**
 * Site-wide company profile (`company_profile` single row, id = 1).
 */

/**
 * @return array<string, string>
 */
function fruitwala_company_profile_defaults(): array
{
    return [
        'company_name' => 'Fruitwala Breakfast',
        'address' => 'Chhya Complex, Anmol Nagar , near DTDC courier Service , wathoda Nagpur - pin- 440024',
        'phone' => '+91 9156316001',
        'email' => 'fruitwalabreakfast@gmail.com',
        'facebook_url' => 'https://www.facebook.com/profile.php?id=61578818455992',
        'instagram_url' => 'https://www.instagram.com/fruitwala_breakfast/',
        'twitter_url' => 'https://twitter.com/fruitwalabreak',
        'linkedin_url' => '',
        'youtube_url' => 'https://www.youtube.com/shorts/hW6raQXeOC4',
        'whatsapp_url' => 'https://wa.me/918812925014',
    ];
}

function fruitwala_company_profile_ensure_table(?mysqli $conn): bool
{
    if (!$conn instanceof mysqli) {
        return false;
    }
    static $done = false;
    if ($done) {
        return true;
    }
    $path = dirname(__DIR__) . '/admin/sql/company_profile.sql';
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

function fruitwala_company_profile_ensure_default_row(?mysqli $conn): void
{
    if (!$conn instanceof mysqli) {
        return;
    }
    static $done = false;
    if ($done) {
        return;
    }
    if (!fruitwala_company_profile_ensure_table($conn)) {
        return;
    }
    $chk = mysqli_query($conn, 'SELECT id FROM company_profile WHERE id = 1 LIMIT 1');
    if ($chk && mysqli_num_rows($chk) > 0) {
        mysqli_free_result($chk);
        $done = true;

        return;
    }
    if ($chk) {
        mysqli_free_result($chk);
    }

    $d = fruitwala_company_profile_defaults();
    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO company_profile (id, company_name, address, phone, email, facebook_url, instagram_url, twitter_url, linkedin_url, youtube_url, whatsapp_url)
         VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    if (!$stmt) {
        return;
    }
    mysqli_stmt_bind_param(
        $stmt,
        'ssssssssss',
        $d['company_name'],
        $d['address'],
        $d['phone'],
        $d['email'],
        $d['facebook_url'],
        $d['instagram_url'],
        $d['twitter_url'],
        $d['linkedin_url'],
        $d['youtube_url'],
        $d['whatsapp_url']
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $done = true;
}

/**
 * @return array<string, string>
 */
function fruitwala_company_profile_load(?mysqli $conn): array
{
    $defaults = fruitwala_company_profile_defaults();
    if (!$conn instanceof mysqli || !fruitwala_company_profile_ensure_table($conn)) {
        return $defaults;
    }
    fruitwala_company_profile_ensure_default_row($conn);

    $sql = 'SELECT company_name, address, phone, email, facebook_url, instagram_url, twitter_url, linkedin_url, youtube_url, whatsapp_url FROM company_profile WHERE id = 1 LIMIT 1';
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

/**
 * Use in href when empty means "no link".
 */
function fruitwala_company_profile_url_or_hash(string $url): string
{
    $t = trim($url);

    return $t !== '' ? $t : '#';
}
