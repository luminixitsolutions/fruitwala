<?php
/**
 * Load active navigation items for the public site header.
 *
 * @param mysqli $conn
 * @return array<int, array{title: string, url: string}>
 */
function fruitwala_get_nav_menus(mysqli $conn): array
{
    $items = [];
    $sql = 'SELECT title, url FROM nav_menus WHERE is_active = 1 ORDER BY sort_order ASC, id ASC';
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = [
                'title' => (string) $row['title'],
                'url' => (string) $row['url'],
            ];
        }
        mysqli_free_result($result);
    }
    return $items;
}

/**
 * Fallback when DB is empty or unavailable (matches previous static menu).
 *
 * @return array<int, array{title: string, url: string}>
 */
function fruitwala_nav_menus_fallback(): array
{
    return [
        ['title' => 'Home', 'url' => 'index.php'],
        ['title' => 'About Us', 'url' => 'about-us.php'],
        ['title' => 'Why Choose Us', 'url' => 'why-choose-us.php'],
        ['title' => 'Packages', 'url' => 'packages.php'],
        ['title' => 'Diet Consultation', 'url' => 'diet_consultation.php'],
        ['title' => 'Blogs', 'url' => 'blogs.php'],
        ['title' => 'FAQ', 'url' => 'faq.php'],
        ['title' => 'Portfolio', 'url' => 'portfolio.php'],
        ['title' => 'Contact Us', 'url' => 'contact.php'],
    ];
}
