<?php
declare(strict_types=1);

/**
 * Ensure home_page_fields exists (for DBs installed before this feature).
 */
function fruitwala_admin_ensure_home_table(mysqli $conn): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $path = dirname(__DIR__) . '/sql/home_page_fields.sql';
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

    $heroPath = dirname(__DIR__) . '/sql/home_hero_slides.sql';
    if (is_readable($heroPath)) {
        $heroSql = file_get_contents($heroPath);
        if ($heroSql !== false && trim($heroSql) !== '') {
            mysqli_multi_query($conn, $heroSql);
            while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
                if ($res = mysqli_store_result($conn)) {
                    mysqli_free_result($res);
                }
            }
        }
    }

    $listPath = dirname(__DIR__) . '/sql/home_list_tables.sql';
    if (is_readable($listPath)) {
        $listSql = file_get_contents($listPath);
        if ($listSql !== false && trim($listSql) !== '') {
            mysqli_multi_query($conn, $listSql);
            while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
                if ($res = mysqli_store_result($conn)) {
                    mysqli_free_result($res);
                }
            }
        }
    }

    $aboutPath = dirname(__DIR__) . '/sql/about_us_content.sql';
    if (is_readable($aboutPath)) {
        $aboutSql = file_get_contents($aboutPath);
        if ($aboutSql !== false && trim($aboutSql) !== '') {
            mysqli_multi_query($conn, $aboutSql);
            while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
                if ($res = mysqli_store_result($conn)) {
                    mysqli_free_result($res);
                }
            }
        }
    }

    $done = true;
}
