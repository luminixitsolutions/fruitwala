<?php
declare(strict_types=1);

/**
 * @param array<string, string>|null $itemForm
 */
function fruitwala_admin_home_list_redirect(string $slug, string $flash): void
{
    $_SESSION['admin_flash'] = $flash;
    header('Location: home_edit.php?s=' . rawurlencode($slug));
    exit;
}

function fruitwala_admin_home_list_next_sort(mysqli $conn, string $table): int
{
    $next = 10;
    $sql = 'SELECT COALESCE(MAX(sort_order), 0) + 10 AS n FROM `' . $table . '`';
    if ($qr = mysqli_query($conn, $sql)) {
        if ($r = mysqli_fetch_assoc($qr)) {
            $next = (int) $r['n'];
        }
        mysqli_free_result($qr);
    }

    return $next;
}

function fruitwala_admin_try_home_list_post(mysqli $conn, string $slug, string $listType): void
{
    if ($listType === 'instagram' && isset($_POST['instagram_meta_save'])) {
        fruitwala_home_ensure_home_list_tables($conn);
        $heading = str_replace("\r\n", "\n", trim((string) ($_POST['instagram_heading'] ?? '')));
        $handle = trim((string) ($_POST['instagram_handle'] ?? ''));
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO home_page_fields (section_key, field_key, field_value) VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE field_value = VALUES(field_value)'
        );
        if ($stmt) {
            $sk = 'instagram';
            $fkHeading = 'heading';
            $fkHandle = 'handle';
            mysqli_stmt_bind_param($stmt, 'sss', $sk, $fkHeading, $heading);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_param($stmt, 'sss', $sk, $fkHandle, $handle);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        fruitwala_admin_home_list_redirect($slug, 'Instagram heading saved.');
    }

    if ($listType === 'reels') {
        if (isset($_POST['reel_delete_id'])) {
            fruitwala_home_ensure_home_list_tables($conn);
            $did = (int) $_POST['reel_delete_id'];
            if ($did > 0) {
                $st = mysqli_prepare($conn, 'DELETE FROM home_reels WHERE id = ? LIMIT 1');
                if ($st) {
                    mysqli_stmt_bind_param($st, 'i', $did);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            }
            fruitwala_admin_home_list_redirect($slug, 'Reel removed.');
        }
        if (isset($_POST['reel_save'])) {
            fruitwala_home_ensure_home_list_tables($conn);
            $rowId = (int) ($_POST['reel_row_id'] ?? 0);
            $video = trim((string) ($_POST['reel_video'] ?? ''));
            $cover = trim((string) ($_POST['reel_cover'] ?? ''));
            $alt = trim((string) ($_POST['reel_alt'] ?? ''));
            if ($rowId > 0) {
                $st = mysqli_prepare($conn, 'UPDATE home_reels SET video = ?, cover = ?, alt = ? WHERE id = ? LIMIT 1');
                if ($st) {
                    mysqli_stmt_bind_param($st, 'sssi', $video, $cover, $alt, $rowId);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            } else {
                $next = fruitwala_admin_home_list_next_sort($conn, 'home_reels');
                $st = mysqli_prepare($conn, 'INSERT INTO home_reels (sort_order, video, cover, alt) VALUES (?,?,?,?)');
                if ($st) {
                    mysqli_stmt_bind_param($st, 'isss', $next, $video, $cover, $alt);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            }
            fruitwala_admin_home_list_redirect($slug, 'Reel saved.');
        }
        return;
    }

    if ($listType === 'sale_banners') {
        if (isset($_POST['sale_banner_delete_id'])) {
            fruitwala_home_ensure_home_list_tables($conn);
            $did = (int) $_POST['sale_banner_delete_id'];
            if ($did > 0) {
                $st = mysqli_prepare($conn, 'DELETE FROM home_sale_banners WHERE id = ? LIMIT 1');
                if ($st) {
                    mysqli_stmt_bind_param($st, 'i', $did);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            }
            fruitwala_admin_home_list_redirect($slug, 'Banner removed.');
        }
        if (isset($_POST['sale_banner_save'])) {
            fruitwala_home_ensure_home_list_tables($conn);
            $rowId = (int) ($_POST['sale_banner_row_id'] ?? 0);
            $title = str_replace("\r\n", "\n", trim((string) ($_POST['sale_banner_title'] ?? '')));
            $subtitle = trim((string) ($_POST['sale_banner_subtitle'] ?? ''));
            $image = trim((string) ($_POST['sale_banner_image'] ?? ''));
            $link = trim((string) ($_POST['sale_banner_link'] ?? ''));
            if ($rowId > 0) {
                $st = mysqli_prepare(
                    $conn,
                    'UPDATE home_sale_banners SET title = ?, subtitle = ?, image = ?, link = ? WHERE id = ? LIMIT 1'
                );
                if ($st) {
                    mysqli_stmt_bind_param($st, 'ssssi', $title, $subtitle, $image, $link, $rowId);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            } else {
                $next = fruitwala_admin_home_list_next_sort($conn, 'home_sale_banners');
                $st = mysqli_prepare(
                    $conn,
                    'INSERT INTO home_sale_banners (sort_order, title, subtitle, image, link) VALUES (?,?,?,?,?)'
                );
                if ($st) {
                    mysqli_stmt_bind_param($st, 'issss', $next, $title, $subtitle, $image, $link);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            }
            fruitwala_admin_home_list_redirect($slug, 'Banner saved.');
        }
        return;
    }

    if ($listType === 'services') {
        if (isset($_POST['service_row_delete_id'])) {
            fruitwala_home_ensure_home_list_tables($conn);
            $did = (int) $_POST['service_row_delete_id'];
            if ($did > 0) {
                $st = mysqli_prepare($conn, 'DELETE FROM home_services WHERE id = ? LIMIT 1');
                if ($st) {
                    mysqli_stmt_bind_param($st, 'i', $did);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            }
            fruitwala_admin_home_list_redirect($slug, 'Service removed.');
        }
        if (isset($_POST['service_row_save'])) {
            fruitwala_home_ensure_home_list_tables($conn);
            $rowId = (int) ($_POST['service_row_id'] ?? 0);
            $title = trim((string) ($_POST['service_title'] ?? ''));
            $subtitle = trim((string) ($_POST['service_subtitle'] ?? ''));
            $icon = trim((string) ($_POST['service_icon'] ?? ''));
            if ($rowId > 0) {
                $st = mysqli_prepare(
                    $conn,
                    'UPDATE home_services SET title = ?, subtitle = ?, icon = ? WHERE id = ? LIMIT 1'
                );
                if ($st) {
                    mysqli_stmt_bind_param($st, 'sssi', $title, $subtitle, $icon, $rowId);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            } else {
                $next = fruitwala_admin_home_list_next_sort($conn, 'home_services');
                $st = mysqli_prepare($conn, 'INSERT INTO home_services (sort_order, title, subtitle, icon) VALUES (?,?,?,?)');
                if ($st) {
                    mysqli_stmt_bind_param($st, 'isss', $next, $title, $subtitle, $icon);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            }
            fruitwala_admin_home_list_redirect($slug, 'Service saved.');
        }
        return;
    }

    if ($listType === 'testimonials') {
        if (isset($_POST['testimonial_delete_id'])) {
            fruitwala_home_ensure_home_list_tables($conn);
            require_once __DIR__ . '/upload_image.php';
            $did = (int) $_POST['testimonial_delete_id'];
            if ($did > 0) {
                $oldImg = '';
                $stSel = mysqli_prepare($conn, 'SELECT image FROM home_testimonials WHERE id = ? LIMIT 1');
                if ($stSel) {
                    mysqli_stmt_bind_param($stSel, 'i', $did);
                    mysqli_stmt_execute($stSel);
                    $res = mysqli_stmt_get_result($stSel);
                    $row = $res ? mysqli_fetch_assoc($res) : null;
                    mysqli_stmt_close($stSel);
                    if (is_array($row)) {
                        $oldImg = (string) ($row['image'] ?? '');
                    }
                }
                $st = mysqli_prepare($conn, 'DELETE FROM home_testimonials WHERE id = ? LIMIT 1');
                if ($st) {
                    mysqli_stmt_bind_param($st, 'i', $did);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
                fruitwala_admin_remove_testimonial_upload_file($oldImg !== '' ? $oldImg : null);
            }
            fruitwala_admin_home_list_redirect($slug, 'Testimonial removed.');
        }
        if (isset($_POST['testimonial_save'])) {
            fruitwala_home_ensure_home_list_tables($conn);
            require_once __DIR__ . '/upload_image.php';
            $rowId = (int) ($_POST['testimonial_row_id'] ?? 0);
            $heading = trim((string) ($_POST['testimonial_heading'] ?? ''));
            $body = str_replace("\r\n", "\n", trim((string) ($_POST['testimonial_body'] ?? '')));
            $author = trim((string) ($_POST['testimonial_author'] ?? ''));
            $currentImage = trim((string) ($_POST['testimonial_image_current'] ?? ''));
            $upload = fruitwala_admin_save_testimonial_photo_upload();
            if ($upload['error'] !== null) {
                fruitwala_admin_home_list_redirect($slug, $upload['error']);
            }
            $image = $currentImage;
            if ($upload['path'] !== null) {
                if ($rowId > 0 && $currentImage !== '' && $currentImage !== $upload['path']) {
                    fruitwala_admin_remove_testimonial_upload_file($currentImage);
                }
                $image = $upload['path'];
            }
            if ($rowId > 0) {
                $st = mysqli_prepare(
                    $conn,
                    'UPDATE home_testimonials SET heading = ?, body = ?, author = ?, image = ? WHERE id = ? LIMIT 1'
                );
                if ($st) {
                    mysqli_stmt_bind_param($st, 'ssssi', $heading, $body, $author, $image, $rowId);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            } else {
                $next = fruitwala_admin_home_list_next_sort($conn, 'home_testimonials');
                $st = mysqli_prepare(
                    $conn,
                    'INSERT INTO home_testimonials (sort_order, heading, body, author, image) VALUES (?,?,?,?,?)'
                );
                if ($st) {
                    mysqli_stmt_bind_param($st, 'issss', $next, $heading, $body, $author, $image);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            }
            fruitwala_admin_home_list_redirect($slug, 'Testimonial saved.');
        }
        return;
    }

    if ($listType === 'instagram') {
        if (isset($_POST['ig_tile_delete_id'])) {
            fruitwala_home_ensure_home_list_tables($conn);
            $did = (int) $_POST['ig_tile_delete_id'];
            if ($did > 0) {
                $st = mysqli_prepare($conn, 'DELETE FROM home_instagram_tiles WHERE id = ? LIMIT 1');
                if ($st) {
                    mysqli_stmt_bind_param($st, 'i', $did);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            }
            fruitwala_admin_home_list_redirect($slug, 'Image removed.');
        }
        if (isset($_POST['ig_tile_save'])) {
            fruitwala_home_ensure_home_list_tables($conn);
            $rowId = (int) ($_POST['ig_tile_row_id'] ?? 0);
            $popup = trim((string) ($_POST['ig_tile_popup'] ?? ''));
            $img = trim((string) ($_POST['ig_tile_img'] ?? ''));
            $alt = trim((string) ($_POST['ig_tile_alt'] ?? ''));
            if ($rowId > 0) {
                $st = mysqli_prepare(
                    $conn,
                    'UPDATE home_instagram_tiles SET popup = ?, img = ?, alt = ? WHERE id = ? LIMIT 1'
                );
                if ($st) {
                    mysqli_stmt_bind_param($st, 'sssi', $popup, $img, $alt, $rowId);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            } else {
                $next = fruitwala_admin_home_list_next_sort($conn, 'home_instagram_tiles');
                $st = mysqli_prepare(
                    $conn,
                    'INSERT INTO home_instagram_tiles (sort_order, popup, img, alt) VALUES (?,?,?,?)'
                );
                if ($st) {
                    mysqli_stmt_bind_param($st, 'isss', $next, $popup, $img, $alt);
                    mysqli_stmt_execute($st);
                    mysqli_stmt_close($st);
                }
            }
            fruitwala_admin_home_list_redirect($slug, 'Image saved.');
        }
    }
}

/**
 * @return array<string, mixed>|null
 */
function fruitwala_admin_home_list_item_form(mysqli $conn, string $slug, string $listType): ?array
{
    $sub = (string) ($_GET['sub'] ?? '');
    if (!in_array($sub, ['add', 'edit'], true)) {
        $sub = '';
    }
    $editId = (int) ($_GET['id'] ?? 0);

    if ($listType === 'reels') {
        if ($sub === 'add') {
            return ['id' => 0, 'video' => '', 'cover' => '', 'alt' => ''];
        }
        if ($sub === 'edit' && $editId > 0) {
            fruitwala_home_ensure_home_list_tables($conn);
            $st = mysqli_prepare($conn, 'SELECT id, video, cover, alt FROM home_reels WHERE id = ? LIMIT 1');
            if ($st) {
                mysqli_stmt_bind_param($st, 'i', $editId);
                mysqli_stmt_execute($st);
                $res = mysqli_stmt_get_result($st);
                $row = $res ? mysqli_fetch_assoc($res) : null;
                mysqli_stmt_close($st);
                if (is_array($row)) {
                    $row['id'] = (int) $row['id'];

                    return $row;
                }
            }
            $_SESSION['admin_flash'] = 'Reel not found.';
            header('Location: home_edit.php?s=' . rawurlencode($slug));
            exit;
        }
        if ($sub === 'edit') {
            header('Location: home_edit.php?s=' . rawurlencode($slug));
            exit;
        }

        return null;
    }

    if ($listType === 'sale_banners') {
        if ($sub === 'add') {
            return ['id' => 0, 'title' => '', 'subtitle' => '', 'image' => '', 'link' => ''];
        }
        if ($sub === 'edit' && $editId > 0) {
            fruitwala_home_ensure_home_list_tables($conn);
            $st = mysqli_prepare(
                $conn,
                'SELECT id, title, subtitle, image, link FROM home_sale_banners WHERE id = ? LIMIT 1'
            );
            if ($st) {
                mysqli_stmt_bind_param($st, 'i', $editId);
                mysqli_stmt_execute($st);
                $res = mysqli_stmt_get_result($st);
                $row = $res ? mysqli_fetch_assoc($res) : null;
                mysqli_stmt_close($st);
                if (is_array($row)) {
                    $row['id'] = (int) $row['id'];

                    return $row;
                }
            }
            $_SESSION['admin_flash'] = 'Banner not found.';
            header('Location: home_edit.php?s=' . rawurlencode($slug));
            exit;
        }
        if ($sub === 'edit') {
            header('Location: home_edit.php?s=' . rawurlencode($slug));
            exit;
        }

        return null;
    }

    if ($listType === 'services') {
        if ($sub === 'add') {
            return ['id' => 0, 'title' => '', 'subtitle' => '', 'icon' => ''];
        }
        if ($sub === 'edit' && $editId > 0) {
            fruitwala_home_ensure_home_list_tables($conn);
            $st = mysqli_prepare($conn, 'SELECT id, title, subtitle, icon FROM home_services WHERE id = ? LIMIT 1');
            if ($st) {
                mysqli_stmt_bind_param($st, 'i', $editId);
                mysqli_stmt_execute($st);
                $res = mysqli_stmt_get_result($st);
                $row = $res ? mysqli_fetch_assoc($res) : null;
                mysqli_stmt_close($st);
                if (is_array($row)) {
                    $row['id'] = (int) $row['id'];

                    return $row;
                }
            }
            $_SESSION['admin_flash'] = 'Service not found.';
            header('Location: home_edit.php?s=' . rawurlencode($slug));
            exit;
        }
        if ($sub === 'edit') {
            header('Location: home_edit.php?s=' . rawurlencode($slug));
            exit;
        }

        return null;
    }

    if ($listType === 'testimonials') {
        if ($sub === 'add') {
            return ['id' => 0, 'heading' => '', 'body' => '', 'author' => '', 'image' => ''];
        }
        if ($sub === 'edit' && $editId > 0) {
            fruitwala_home_ensure_home_list_tables($conn);
            $st = mysqli_prepare(
                $conn,
                'SELECT id, heading, body, author, image FROM home_testimonials WHERE id = ? LIMIT 1'
            );
            if ($st) {
                mysqli_stmt_bind_param($st, 'i', $editId);
                mysqli_stmt_execute($st);
                $res = mysqli_stmt_get_result($st);
                $row = $res ? mysqli_fetch_assoc($res) : null;
                mysqli_stmt_close($st);
                if (is_array($row)) {
                    $row['id'] = (int) $row['id'];

                    return $row;
                }
            }
            $_SESSION['admin_flash'] = 'Testimonial not found.';
            header('Location: home_edit.php?s=' . rawurlencode($slug));
            exit;
        }
        if ($sub === 'edit') {
            header('Location: home_edit.php?s=' . rawurlencode($slug));
            exit;
        }

        return null;
    }

    if ($listType === 'instagram') {
        if ($sub === 'add') {
            return ['id' => 0, 'popup' => '', 'img' => '', 'alt' => ''];
        }
        if ($sub === 'edit' && $editId > 0) {
            fruitwala_home_ensure_home_list_tables($conn);
            $st = mysqli_prepare(
                $conn,
                'SELECT id, popup, img, alt FROM home_instagram_tiles WHERE id = ? LIMIT 1'
            );
            if ($st) {
                mysqli_stmt_bind_param($st, 'i', $editId);
                mysqli_stmt_execute($st);
                $res = mysqli_stmt_get_result($st);
                $row = $res ? mysqli_fetch_assoc($res) : null;
                mysqli_stmt_close($st);
                if (is_array($row)) {
                    $row['id'] = (int) $row['id'];

                    return $row;
                }
            }
            $_SESSION['admin_flash'] = 'Image not found.';
            header('Location: home_edit.php?s=' . rawurlencode($slug));
            exit;
        }
        if ($sub === 'edit') {
            header('Location: home_edit.php?s=' . rawurlencode($slug));
            exit;
        }

        return null;
    }

    return null;
}

function fruitwala_admin_home_list_trunc(string $s, int $max): string
{
    if (function_exists('mb_strlen') && function_exists('mb_substr') && mb_strlen($s, 'UTF-8') > $max) {
        return mb_substr($s, 0, $max - 1, 'UTF-8') . '…';
    }
    if (strlen($s) > $max) {
        return substr($s, 0, $max - 3) . '...';
    }

    return $s;
}

/**
 * Href from admin/ to a file path stored relative to the site root (e.g. assets/..., uploads/...).
 */
function fruitwala_admin_site_relative_href(string $siteRelativePath): string
{
    $p = str_replace('\\', '/', trim($siteRelativePath));
    if ($p === '') {
        return '';
    }

    return '../' . ltrim($p, '/');
}

/**
 * @param array<string, mixed> $row
 */
function fruitwala_admin_testimonial_photo_cell(array $row): void
{
    $imgPath = (string) ($row['image'] ?? '');
    if ($imgPath === '') {
        echo '<span class="admin-dt-missing" style="color:var(--admin-muted);font-size:0.85rem">—</span>';

        return;
    }
    $src = htmlspecialchars(fruitwala_admin_site_relative_href($imgPath), ENT_QUOTES, 'UTF-8');
    $alt = htmlspecialchars(fruitwala_admin_home_list_trunc((string) ($row['author'] ?? ''), 32), ENT_QUOTES, 'UTF-8');
    echo '<img class="admin-testimonial-dt-thumb" src="' . $src . '" alt="' . $alt . '" width="44" height="44" loading="lazy" style="object-fit:cover;border-radius:50%;vertical-align:middle;border:1px solid var(--admin-border)">';
}

/**
 * @param array<string, mixed>|null $itemForm
 */
function fruitwala_admin_render_home_list_ui(mysqli $conn, string $slug, string $listType, ?array $itemForm): void
{
    $escSlug = htmlspecialchars($slug, ENT_QUOTES, 'UTF-8');
    $base = 'home_edit.php?s=' . rawurlencode($slug);

    if (is_array($itemForm)) {
        if ($listType === 'reels') {
            ?>
    <p style="margin:0 0 1rem">
      <a class="btn btn-ghost btn-sm" href="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-arrow-left"></i> Back to reels</a>
    </p>
    <form method="post" action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>" class="admin-form">
      <?= admin_csrf_field() ?>
      <input type="hidden" name="reel_save" value="1">
      <input type="hidden" name="reel_row_id" value="<?= (int) $itemForm['id'] ?>">
      <div class="admin-card" style="margin-bottom:1.25rem">
        <div class="admin-card-header"><?= (int) $itemForm['id'] > 0 ? 'Edit reel' : 'New reel' ?></div>
        <div class="admin-card-body">
          <div class="form-group">
            <label for="reel_video">Video path (.mp4)</label>
            <input type="text" id="reel_video" name="reel_video" value="<?= htmlspecialchars((string) $itemForm['video'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="reel_cover">Cover image</label>
            <input type="text" id="reel_cover" name="reel_cover" value="<?= htmlspecialchars((string) $itemForm['cover'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="reel_alt">Alt text</label>
            <input type="text" id="reel_alt" name="reel_alt" value="<?= htmlspecialchars((string) $itemForm['alt'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save reel</button>
    </form>
            <?php
            return;
        }
        if ($listType === 'sale_banners') {
            ?>
    <p style="margin:0 0 1rem">
      <a class="btn btn-ghost btn-sm" href="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-arrow-left"></i> Back to banners</a>
    </p>
    <form method="post" action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>" class="admin-form">
      <?= admin_csrf_field() ?>
      <input type="hidden" name="sale_banner_save" value="1">
      <input type="hidden" name="sale_banner_row_id" value="<?= (int) $itemForm['id'] ?>">
      <div class="admin-card" style="margin-bottom:1.25rem">
        <div class="admin-card-header"><?= (int) $itemForm['id'] > 0 ? 'Edit banner' : 'New banner' ?></div>
        <div class="admin-card-body">
          <div class="form-group">
            <label for="sale_banner_title">Title (HTML allowed: &lt;font&gt;)</label>
            <textarea id="sale_banner_title" name="sale_banner_title" rows="3" style="width:100%;max-width:640px;padding:0.65rem 0.85rem;border-radius:10px;border:1px solid var(--admin-border);background:var(--admin-surface-2);color:var(--admin-text);font-family:inherit;font-size:0.9rem"><?= htmlspecialchars((string) $itemForm['title'], ENT_QUOTES, 'UTF-8') ?></textarea>
          </div>
          <div class="form-group">
            <label for="sale_banner_subtitle">Subtitle</label>
            <input type="text" id="sale_banner_subtitle" name="sale_banner_subtitle" value="<?= htmlspecialchars((string) $itemForm['subtitle'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="sale_banner_image">Background image</label>
            <input type="text" id="sale_banner_image" name="sale_banner_image" value="<?= htmlspecialchars((string) $itemForm['image'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="sale_banner_link">Link URL</label>
            <input type="text" id="sale_banner_link" name="sale_banner_link" value="<?= htmlspecialchars((string) $itemForm['link'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save banner</button>
    </form>
            <?php
            return;
        }
        if ($listType === 'services') {
            ?>
    <p style="margin:0 0 1rem">
      <a class="btn btn-ghost btn-sm" href="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-arrow-left"></i> Back to services</a>
    </p>
    <form method="post" action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>" class="admin-form">
      <?= admin_csrf_field() ?>
      <input type="hidden" name="service_row_save" value="1">
      <input type="hidden" name="service_row_id" value="<?= (int) $itemForm['id'] ?>">
      <div class="admin-card" style="margin-bottom:1.25rem">
        <div class="admin-card-header"><?= (int) $itemForm['id'] > 0 ? 'Edit service' : 'New service' ?></div>
        <div class="admin-card-body">
          <div class="form-group">
            <label for="service_title">Title</label>
            <input type="text" id="service_title" name="service_title" value="<?= htmlspecialchars((string) $itemForm['title'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="service_subtitle">Subtitle</label>
            <input type="text" id="service_subtitle" name="service_subtitle" value="<?= htmlspecialchars((string) $itemForm['subtitle'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="service_icon">Font Awesome class (e.g. fas fa-shipping-fast)</label>
            <input type="text" id="service_icon" name="service_icon" value="<?= htmlspecialchars((string) $itemForm['icon'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save service</button>
    </form>
            <?php
            return;
        }
        if ($listType === 'testimonials') {
            ?>
    <p style="margin:0 0 1rem">
      <a class="btn btn-ghost btn-sm" href="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-arrow-left"></i> Back to testimonials</a>
    </p>
    <form method="post" action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>" class="admin-form" enctype="multipart/form-data">
      <?= admin_csrf_field() ?>
      <input type="hidden" name="testimonial_save" value="1">
      <input type="hidden" name="testimonial_row_id" value="<?= (int) $itemForm['id'] ?>">
      <input type="hidden" name="testimonial_image_current" value="<?= htmlspecialchars((string) $itemForm['image'], ENT_QUOTES, 'UTF-8') ?>">
      <div class="admin-card" style="margin-bottom:1.25rem">
        <div class="admin-card-header"><?= (int) $itemForm['id'] > 0 ? 'Edit testimonial' : 'New testimonial' ?></div>
        <div class="admin-card-body">
          <div class="form-group">
            <label for="testimonial_heading">Heading</label>
            <input type="text" id="testimonial_heading" name="testimonial_heading" value="<?= htmlspecialchars((string) $itemForm['heading'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="testimonial_body">Quote</label>
            <textarea id="testimonial_body" name="testimonial_body" rows="4" style="width:100%;max-width:640px;padding:0.65rem 0.85rem;border-radius:10px;border:1px solid var(--admin-border);background:var(--admin-surface-2);color:var(--admin-text);font-family:inherit;font-size:0.9rem"><?= htmlspecialchars((string) $itemForm['body'], ENT_QUOTES, 'UTF-8') ?></textarea>
          </div>
          <div class="form-group">
            <label for="testimonial_author">Name / location</label>
            <input type="text" id="testimonial_author" name="testimonial_author" value="<?= htmlspecialchars((string) $itemForm['author'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <?php
            $existingImg = (string) ($itemForm['image'] ?? '');
          if ($existingImg !== '') {
              $previewSrc = '../' . ltrim(str_replace('\\', '/', $existingImg), '/');
              ?>
          <div class="form-group">
            <span class="label-like" style="display:block;margin-bottom:0.35rem;font-weight:600;font-size:0.9rem">Current photo</span>
            <div style="margin-top:0.25rem">
              <img src="<?= htmlspecialchars($previewSrc, ENT_QUOTES, 'UTF-8') ?>" alt="" style="max-height:120px;border-radius:10px;border:1px solid var(--admin-border);vertical-align:middle">
            </div>
          </div>
              <?php
          }
          ?>
          <div class="form-group">
            <label for="testimonial_photo"><?= (int) $itemForm['id'] > 0 ? 'Replace photo' : 'Photo' ?></label>
            <input type="file" id="testimonial_photo" name="testimonial_photo" accept="image/jpeg,image/png,image/gif,image/webp">
            <p style="margin:0.35rem 0 0;font-size:0.75rem;color:var(--admin-muted)">JPEG, PNG, GIF, or WebP. Max 5 MB.</p>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save testimonial</button>
    </form>
            <?php
            return;
        }
        if ($listType === 'instagram') {
            ?>
    <p style="margin:0 0 1rem">
      <a class="btn btn-ghost btn-sm" href="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-arrow-left"></i> Back to images</a>
    </p>
    <form method="post" action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>" class="admin-form">
      <?= admin_csrf_field() ?>
      <input type="hidden" name="ig_tile_save" value="1">
      <input type="hidden" name="ig_tile_row_id" value="<?= (int) $itemForm['id'] ?>">
      <div class="admin-card" style="margin-bottom:1.25rem">
        <div class="admin-card-header"><?= (int) $itemForm['id'] > 0 ? 'Edit image' : 'New image' ?></div>
        <div class="admin-card-body">
          <div class="form-group">
            <label for="ig_tile_popup">Popup / large image URL</label>
            <input type="text" id="ig_tile_popup" name="ig_tile_popup" value="<?= htmlspecialchars((string) $itemForm['popup'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="ig_tile_img">Thumbnail path</label>
            <input type="text" id="ig_tile_img" name="ig_tile_img" value="<?= htmlspecialchars((string) $itemForm['img'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="ig_tile_alt">Alt text</label>
            <input type="text" id="ig_tile_alt" name="ig_tile_alt" value="<?= htmlspecialchars((string) $itemForm['alt'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save image</button>
    </form>
            <?php
            return;
        }
    }

    fruitwala_home_ensure_home_list_tables($conn);
    $home = fruitwala_home_load($conn);

    if ($listType === 'instagram') {
        $igHeading = (string) ($home['instagram']['heading'] ?? '');
        $igHandle = (string) ($home['instagram']['handle'] ?? '');
        ?>
    <form method="post" action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>" class="admin-form" style="margin-bottom:1.25rem">
      <?= admin_csrf_field() ?>
      <input type="hidden" name="instagram_meta_save" value="1">
      <div class="admin-card">
        <div class="admin-card-header">Section heading</div>
        <div class="admin-card-body">
          <div class="form-group">
            <label for="instagram_heading">Section title</label>
            <input type="text" id="instagram_heading" name="instagram_heading" value="<?= htmlspecialchars($igHeading, ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="instagram_handle">Handle under each image</label>
            <input type="text" id="instagram_handle" name="instagram_handle" value="<?= htmlspecialchars($igHandle, ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save heading</button>
    </form>
        <?php
    }

    if ($listType === 'reels') {
        $rows = [];
        $q = mysqli_query($conn, 'SELECT id, sort_order, video, cover, alt FROM home_reels ORDER BY sort_order ASC, id ASC');
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $rows[] = $r;
            }
            mysqli_free_result($q);
        }
        $tableId = 'reelsTable';
        $addLabel = 'Add reel';
        $emptyMsg = 'No reels yet. Click “Add reel” to create one.';
        ?>
    <div class="admin-card hero-dt-card">
      <div class="admin-card-header">Reels</div>
      <div class="admin-card-body hero-dt-shell" style="overflow-x:auto">
        <div class="hero-dt-add-row">
          <a class="hero-dt-btn-add" href="<?= htmlspecialchars($base . '&sub=add', ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-plus"></i> <?= htmlspecialchars($addLabel, ENT_QUOTES, 'UTF-8') ?></a>
        </div>
        <table id="<?= htmlspecialchars($tableId, ENT_QUOTES, 'UTF-8') ?>" class="hero-dt-table" style="width:100%">
          <thead>
            <tr>
              <th>Order</th>
              <th>Video</th>
              <th>Cover</th>
              <th>Alt</th>
              <th class="hero-dt-col-actions">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td><?= (int) $row['sort_order'] ?></td>
                <td><code style="font-size:0.8rem"><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['video'], 56), ENT_QUOTES, 'UTF-8') ?></code></td>
                <td><code style="font-size:0.8rem"><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['cover'], 48), ENT_QUOTES, 'UTF-8') ?></code></td>
                <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['alt'], 40), ENT_QUOTES, 'UTF-8') ?></td>
                <td class="hero-dt-col-actions">
                  <div class="hero-dt-actions-inner">
                    <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="<?= htmlspecialchars($base . '&sub=edit&id=' . (int) $row['id'], ENT_QUOTES, 'UTF-8') ?>" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span></a>
                    <form class="hero-dt-icon-form" method="post" action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Delete this reel?');">
                      <?= admin_csrf_field() ?>
                      <input type="hidden" name="reel_delete_id" value="<?= (int) $row['id'] ?>">
                      <button type="submit" class="hero-dt-icon-btn hero-dt-icon-btn--delete" title="Delete"><i class="fas fa-trash-alt" aria-hidden="true"></i><span class="visually-hidden">Delete</span></button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
        <?php
        fruitwala_admin_home_list_datatable_assets($tableId, 4, $emptyMsg);
        return;
    }

    if ($listType === 'sale_banners') {
        $rows = [];
        $q = mysqli_query($conn, 'SELECT id, sort_order, title, subtitle, image, link FROM home_sale_banners ORDER BY sort_order ASC, id ASC');
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $rows[] = $r;
            }
            mysqli_free_result($q);
        }
        $tableId = 'saleBannersTable';
        ?>
    <div class="admin-card hero-dt-card">
      <div class="admin-card-header">Sale banners</div>
      <div class="admin-card-body hero-dt-shell" style="overflow-x:auto">
        <div class="hero-dt-add-row">
          <a class="hero-dt-btn-add" href="<?= htmlspecialchars($base . '&sub=add', ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-plus"></i> Add banner</a>
        </div>
        <table id="<?= htmlspecialchars($tableId, ENT_QUOTES, 'UTF-8') ?>" class="hero-dt-table" style="width:100%">
          <thead>
            <tr>
              <th>Order</th>
              <th>Title</th>
              <th>Subtitle</th>
              <th>Image</th>
              <th class="hero-dt-col-actions">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row): ?>
              <?php
                $plainTitle = strip_tags((string) $row['title']);
                $plainTitle = fruitwala_admin_home_list_trunc(trim($plainTitle) !== '' ? $plainTitle : (string) $row['title'], 60);
                ?>
              <tr>
                <td><?= (int) $row['sort_order'] ?></td>
                <td><?= htmlspecialchars($plainTitle, ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['subtitle'], 36), ENT_QUOTES, 'UTF-8') ?></td>
                <td><code style="font-size:0.8rem"><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['image'], 40), ENT_QUOTES, 'UTF-8') ?></code></td>
                <td class="hero-dt-col-actions">
                  <div class="hero-dt-actions-inner">
                    <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="<?= htmlspecialchars($base . '&sub=edit&id=' . (int) $row['id'], ENT_QUOTES, 'UTF-8') ?>" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span></a>
                    <form class="hero-dt-icon-form" method="post" action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Delete this banner?');">
                      <?= admin_csrf_field() ?>
                      <input type="hidden" name="sale_banner_delete_id" value="<?= (int) $row['id'] ?>">
                      <button type="submit" class="hero-dt-icon-btn hero-dt-icon-btn--delete" title="Delete"><i class="fas fa-trash-alt" aria-hidden="true"></i><span class="visually-hidden">Delete</span></button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
        <?php
        fruitwala_admin_home_list_datatable_assets($tableId, 4, 'No banners yet. Click “Add banner” to create one.');
        return;
    }

    if ($listType === 'services') {
        $rows = [];
        $q = mysqli_query($conn, 'SELECT id, sort_order, title, subtitle, icon FROM home_services ORDER BY sort_order ASC, id ASC');
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $rows[] = $r;
            }
            mysqli_free_result($q);
        }
        $tableId = 'servicesTable';
        ?>
    <div class="admin-card hero-dt-card">
      <div class="admin-card-header">Services</div>
      <div class="admin-card-body hero-dt-shell" style="overflow-x:auto">
        <div class="hero-dt-add-row">
          <a class="hero-dt-btn-add" href="<?= htmlspecialchars($base . '&sub=add', ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-plus"></i> Add service</a>
        </div>
        <table id="<?= htmlspecialchars($tableId, ENT_QUOTES, 'UTF-8') ?>" class="hero-dt-table" style="width:100%">
          <thead>
            <tr>
              <th>Order</th>
              <th>Title</th>
              <th>Icon class</th>
              <th class="hero-dt-col-actions">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td><?= (int) $row['sort_order'] ?></td>
                <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['title'], 48), ENT_QUOTES, 'UTF-8') ?></td>
                <td><code style="font-size:0.8rem"><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['icon'], 40), ENT_QUOTES, 'UTF-8') ?></code></td>
                <td class="hero-dt-col-actions">
                  <div class="hero-dt-actions-inner">
                    <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="<?= htmlspecialchars($base . '&sub=edit&id=' . (int) $row['id'], ENT_QUOTES, 'UTF-8') ?>" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span></a>
                    <form class="hero-dt-icon-form" method="post" action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Delete this service?');">
                      <?= admin_csrf_field() ?>
                      <input type="hidden" name="service_row_delete_id" value="<?= (int) $row['id'] ?>">
                      <button type="submit" class="hero-dt-icon-btn hero-dt-icon-btn--delete" title="Delete"><i class="fas fa-trash-alt" aria-hidden="true"></i><span class="visually-hidden">Delete</span></button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
        <?php
        fruitwala_admin_home_list_datatable_assets($tableId, 3, 'No services yet. Click “Add service” to create one.');
        return;
    }

    if ($listType === 'testimonials') {
        fruitwala_home_maybe_migrate_testimonials($conn);
        $rows = [];
        $q = mysqli_query($conn, 'SELECT id, sort_order, heading, body, author, image FROM home_testimonials ORDER BY sort_order ASC, id ASC');
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $rows[] = $r;
            }
            mysqli_free_result($q);
        }
        $tableId = 'testimonialsTable';
        ?>
    <div class="admin-card hero-dt-card">
      <div class="admin-card-header">Testimonials</div>
      <div class="admin-card-body hero-dt-shell" style="overflow-x:auto">
        <div class="hero-dt-add-row">
          <a class="hero-dt-btn-add" href="<?= htmlspecialchars($base . '&sub=add', ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-plus"></i> Add testimonial</a>
        </div>
        <table id="<?= htmlspecialchars($tableId, ENT_QUOTES, 'UTF-8') ?>" class="hero-dt-table" style="width:100%">
          <thead>
            <tr>
              <th>Order</th>
              <th>Heading</th>
              <th>Author</th>
              <th>Quote</th>
              <th>Photo</th>
              <th class="hero-dt-col-actions">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td><?= (int) $row['sort_order'] ?></td>
                <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['heading'], 40), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['author'], 32), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['body'], 56), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?php fruitwala_admin_testimonial_photo_cell($row); ?></td>
                <td class="hero-dt-col-actions">
                  <div class="hero-dt-actions-inner">
                    <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="<?= htmlspecialchars($base . '&sub=edit&id=' . (int) $row['id'], ENT_QUOTES, 'UTF-8') ?>" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span></a>
                    <form class="hero-dt-icon-form" method="post" action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Delete this testimonial?');">
                      <?= admin_csrf_field() ?>
                      <input type="hidden" name="testimonial_delete_id" value="<?= (int) $row['id'] ?>">
                      <button type="submit" class="hero-dt-icon-btn hero-dt-icon-btn--delete" title="Delete"><i class="fas fa-trash-alt" aria-hidden="true"></i><span class="visually-hidden">Delete</span></button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
        <?php
        fruitwala_admin_home_list_datatable_assets($tableId, 5, 'No testimonials yet. Click “Add testimonial” to create one.', [4]);
        return;
    }

    if ($listType === 'instagram') {
        $rows = [];
        $q = mysqli_query($conn, 'SELECT id, sort_order, popup, img, alt FROM home_instagram_tiles ORDER BY sort_order ASC, id ASC');
        if ($q) {
            while ($r = mysqli_fetch_assoc($q)) {
                $rows[] = $r;
            }
            mysqli_free_result($q);
        }
        $tableId = 'igTilesTable';
        ?>
    <div class="admin-card hero-dt-card">
      <div class="admin-card-header">Gallery images</div>
      <div class="admin-card-body hero-dt-shell" style="overflow-x:auto">
        <div class="hero-dt-add-row">
          <a class="hero-dt-btn-add" href="<?= htmlspecialchars($base . '&sub=add', ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-plus"></i> Add image</a>
        </div>
        <table id="<?= htmlspecialchars($tableId, ENT_QUOTES, 'UTF-8') ?>" class="hero-dt-table" style="width:100%">
          <thead>
            <tr>
              <th>Order</th>
              <th>Thumbnail</th>
              <th>Alt</th>
              <th>Popup</th>
              <th class="hero-dt-col-actions">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td><?= (int) $row['sort_order'] ?></td>
                <td><code style="font-size:0.8rem"><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['img'], 44), ENT_QUOTES, 'UTF-8') ?></code></td>
                <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['alt'], 32), ENT_QUOTES, 'UTF-8') ?></td>
                <td><code style="font-size:0.8rem"><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['popup'], 40), ENT_QUOTES, 'UTF-8') ?></code></td>
                <td class="hero-dt-col-actions">
                  <div class="hero-dt-actions-inner">
                    <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="<?= htmlspecialchars($base . '&sub=edit&id=' . (int) $row['id'], ENT_QUOTES, 'UTF-8') ?>" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span></a>
                    <form class="hero-dt-icon-form" method="post" action="<?= htmlspecialchars($base, ENT_QUOTES, 'UTF-8') ?>" onsubmit="return confirm('Delete this image?');">
                      <?= admin_csrf_field() ?>
                      <input type="hidden" name="ig_tile_delete_id" value="<?= (int) $row['id'] ?>">
                      <button type="submit" class="hero-dt-icon-btn hero-dt-icon-btn--delete" title="Delete"><i class="fas fa-trash-alt" aria-hidden="true"></i><span class="visually-hidden">Delete</span></button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
        <?php
        fruitwala_admin_home_list_datatable_assets($tableId, 4, 'No images yet. Click “Add image” to create one.');
    }
}

function fruitwala_admin_home_list_datatable_assets(string $tableId, int $actionsColIndex, string $emptyTable, array $alsoDisableSortOnCols = []): void
{
    $noSortTargets = array_values(array_unique(array_merge($alsoDisableSortOnCols, [$actionsColIndex])));
    sort($noSortTargets);
    ?>
    <link rel="stylesheet" href="assets/hero-datatables.css">
    <style>
      .visually-hidden { position: absolute !important; width: 1px !important; height: 1px !important; padding: 0 !important; margin: -1px !important; overflow: hidden !important; clip: rect(0,0,0,0) !important; white-space: nowrap !important; border: 0 !important; }
      .admin-card.hero-dt-card { box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06), 0 8px 32px rgba(15, 23, 42, 0.06); }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
      (function () {
        if (typeof jQuery === 'undefined') return;
        jQuery(function ($) {
          var $t = $('#<?= htmlspecialchars(preg_replace('/[^a-zA-Z0-9_-]/', '', $tableId), ENT_QUOTES, 'UTF-8') ?>');
          if (!$t.length) return;
          $t.DataTable({
            dom: "<'hero-dt-bar'lf>rt<'hero-dt-foot'ip>",
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            columnDefs: [{ orderable: false, targets: <?= json_encode($noSortTargets) ?> }],
            language: {
              lengthMenu: 'Show _MENU_ entries',
              search: 'Search:',
              info: 'Showing _START_ to _END_ of _TOTAL_ entries',
              infoEmpty: 'Showing 0 to 0 of 0 entries',
              infoFiltered: '(filtered from _MAX_ total entries)',
              paginate: { previous: 'Previous', next: 'Next' },
              emptyTable: <?= json_encode($emptyTable, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>
            },
            headerCallback: function (thead) {
              $(thead).find('th').last().removeClass('sorting sorting_asc sorting_desc').addClass('sorting_disabled');
            },
            initComplete: function () {
              $t.closest('.dataTables_wrapper').find('.dataTables_filter input[type="search"]').attr('placeholder', 'Search…');
            }
          });
        });
      })();
    </script>
    <?php
}
