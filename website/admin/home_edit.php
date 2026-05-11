<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/home_table.php';
require_once __DIR__ . '/includes/home_edit_sections.php';
require_once __DIR__ . '/includes/home_edit_list_section.php';
require_once dirname(__DIR__) . '/includes/home_content.php';

admin_require_login();
fruitwala_admin_ensure_home_table($conn);

$slug = preg_replace('/[^a-z0-9_]/', '', (string) ($_GET['s'] ?? ''));
$sections = fruitwala_admin_home_edit_sections();

if ($slug === '' || !isset($sections[$slug])) {
    header('Location: home.php');
    exit;
}

$meta = $sections[$slug];
$isHeroSlides = !empty($meta['hero_slides_ui']);
$listType = isset($meta['list_ui']) && is_string($meta['list_ui']) ? $meta['list_ui'] : null;

$flash = '';
if (!empty($_SESSION['admin_flash'])) {
    $flash = (string) $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_verify()) {
    if ($isHeroSlides && isset($_POST['hero_delete_id'])) {
        fruitwala_home_ensure_hero_slides_table($conn);
        require_once __DIR__ . '/includes/upload_image.php';
        $did = (int) $_POST['hero_delete_id'];
        if ($did > 0) {
            $oldImg = '';
            $stSel = mysqli_prepare($conn, 'SELECT image FROM home_hero_slides WHERE id = ? LIMIT 1');
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
            $st = mysqli_prepare($conn, 'DELETE FROM home_hero_slides WHERE id = ? LIMIT 1');
            if ($st) {
                mysqli_stmt_bind_param($st, 'i', $did);
                mysqli_stmt_execute($st);
                mysqli_stmt_close($st);
            }
            fruitwala_admin_remove_home_hero_slide_image_file($oldImg !== '' ? $oldImg : null);
        }
        $_SESSION['admin_flash'] = 'Slide removed.';
        header('Location: home_edit.php?s=hero');
        exit;
    }

    if ($isHeroSlides && isset($_POST['hero_save'])) {
        fruitwala_home_ensure_hero_slides_table($conn);
        require_once __DIR__ . '/includes/upload_image.php';
        $slideId = (int) ($_POST['slide_id'] ?? 0);
        $kicker = str_replace("\r\n", "\n", trim((string) ($_POST['kicker'] ?? '')));
        $headlineMain = trim((string) ($_POST['headline_main'] ?? ''));
        $headlineSub = trim((string) ($_POST['headline_sub'] ?? ''));
        $description = str_replace("\r\n", "\n", trim((string) ($_POST['description'] ?? '')));
        $btnText = trim((string) ($_POST['btn_text'] ?? ''));
        $btnUrl = trim((string) ($_POST['btn_url'] ?? ''));
        $currentImage = trim((string) ($_POST['hero_image_current'] ?? ''));
        $image = trim((string) ($_POST['image'] ?? ''));
        $upload = fruitwala_admin_save_home_hero_slide_image_upload();
        if ($upload['error'] !== null) {
            $_SESSION['admin_flash'] = $upload['error'];
            if ($slideId > 0) {
                header('Location: home_edit.php?s=hero&sub=edit&id=' . $slideId);
            } else {
                header('Location: home_edit.php?s=hero&sub=add');
            }
            exit;
        }
        if ($upload['path'] !== null) {
            if ($slideId > 0 && $currentImage !== '' && $currentImage !== $upload['path']) {
                fruitwala_admin_remove_home_hero_slide_image_file($currentImage);
            }
            $image = $upload['path'];
        }

        if ($slideId > 0) {
            $st = mysqli_prepare(
                $conn,
                'UPDATE home_hero_slides SET kicker = ?, headline_main = ?, headline_sub = ?, description = ?, btn_text = ?, btn_url = ?, image = ? WHERE id = ? LIMIT 1'
            );
            if ($st) {
                mysqli_stmt_bind_param($st, 'sssssssi', $kicker, $headlineMain, $headlineSub, $description, $btnText, $btnUrl, $image, $slideId);
                mysqli_stmt_execute($st);
                mysqli_stmt_close($st);
            }
        } else {
            $next = 10;
            if ($qr = mysqli_query($conn, 'SELECT COALESCE(MAX(sort_order), 0) + 10 AS n FROM home_hero_slides')) {
                if ($r = mysqli_fetch_assoc($qr)) {
                    $next = (int) $r['n'];
                }
                mysqli_free_result($qr);
            }
            $st = mysqli_prepare(
                $conn,
                'INSERT INTO home_hero_slides (sort_order, kicker, headline_main, headline_sub, description, btn_text, btn_url, image) VALUES (?,?,?,?,?,?,?,?)'
            );
            if ($st) {
                mysqli_stmt_bind_param($st, 'isssssss', $next, $kicker, $headlineMain, $headlineSub, $description, $btnText, $btnUrl, $image);
                mysqli_stmt_execute($st);
                mysqli_stmt_close($st);
            }
        }
        $_SESSION['admin_flash'] = 'Slide saved.';
        header('Location: home_edit.php?s=hero');
        exit;
    }

    if ($listType) {
        fruitwala_admin_try_home_list_post($conn, $slug, $listType);
    }

    if (!$isHeroSlides && !$listType && !empty($meta['blocks'])) {
        require_once __DIR__ . '/includes/upload_image.php';
        $posted = isset($_POST['f']) && is_array($_POST['f']) ? $_POST['f'] : [];
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO home_page_fields (section_key, field_key, field_value) VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE field_value = VALUES(field_value)'
        );
        if ($stmt) {
            foreach ($meta['blocks'] as $block) {
                foreach ($block['fields'] as $field) {
                    $sk = (string) $field['section_key'];
                    $fk = (string) $field['field_key'];
                    if (($field['type'] ?? '') === 'image_upload') {
                        $up = fruitwala_admin_save_home_quality_icon_upload($sk);
                        if ($up['error'] !== null) {
                            mysqli_stmt_close($stmt);
                            $_SESSION['admin_flash'] = $up['error'];
                            header('Location: home_edit.php?s=' . rawurlencode($slug));
                            exit;
                        }
                        $cur = isset($posted[$sk][$fk]) && is_string($posted[$sk][$fk])
                            ? trim(str_replace("\r\n", "\n", $posted[$sk][$fk]))
                            : '';
                        if ($up['path'] !== null) {
                            if ($cur !== '' && $cur !== $up['path']) {
                                fruitwala_admin_remove_home_quality_icon_file($cur);
                            }
                            $val = $up['path'];
                        } else {
                            $val = $cur;
                        }
                    } else {
                        $raw = $posted[$sk][$fk] ?? '';
                        $val = is_string($raw) ? str_replace("\r\n", "\n", trim($raw)) : '';
                    }
                    mysqli_stmt_bind_param($stmt, 'sss', $sk, $fk, $val);
                    mysqli_stmt_execute($stmt);
                }
            }
            mysqli_stmt_close($stmt);
            $_SESSION['admin_flash'] = 'Saved successfully.';
            header('Location: home_edit.php?s=' . rawurlencode($slug));
            exit;
        }
        $_SESSION['admin_flash'] = 'Could not save. Please try again.';
        header('Location: home_edit.php?s=' . rawurlencode($slug));
        exit;
    }

    header('Location: home_edit.php?s=' . rawurlencode($slug));
    exit;
}

$home = fruitwala_home_load($conn);

$heroForm = null;
if ($isHeroSlides) {
    fruitwala_home_ensure_hero_slides_table($conn);
    fruitwala_home_maybe_migrate_hero_slides($conn);
    $sub = (string) ($_GET['sub'] ?? '');
    if (!in_array($sub, ['add', 'edit'], true)) {
        $sub = '';
    }
    $editId = (int) ($_GET['id'] ?? 0);
    if ($sub === 'add') {
        $heroForm = [
            'id' => 0,
            'kicker' => '',
            'headline_main' => '',
            'headline_sub' => '',
            'description' => '',
            'btn_text' => '',
            'btn_url' => '',
            'image' => '',
        ];
    } elseif ($sub === 'edit' && $editId > 0) {
        $editIdSafe = (int) $editId;
        $qr = mysqli_query(
            $conn,
            "SELECT id, kicker, headline_main, headline_sub, description, btn_text, btn_url, image FROM home_hero_slides WHERE id = {$editIdSafe} LIMIT 1"
        );
        if ($qr && ($row = mysqli_fetch_assoc($qr))) {
            $heroForm = $row;
            $heroForm['id'] = (int) $heroForm['id'];
            mysqli_free_result($qr);
        }
        if ($heroForm === null) {
            $_SESSION['admin_flash'] = 'Slide not found.';
            header('Location: home_edit.php?s=hero');
            exit;
        }
    } elseif ($sub === 'edit') {
        header('Location: home_edit.php?s=hero');
        exit;
    }
}

$itemForm = null;
if ($listType) {
    fruitwala_home_ensure_home_list_tables($conn);
    if ($listType === 'reels') {
        fruitwala_home_maybe_migrate_reels($conn);
    } elseif ($listType === 'sale_banners') {
        fruitwala_home_maybe_migrate_sale_banners($conn);
    } elseif ($listType === 'offer_banners') {
        fruitwala_home_maybe_migrate_offer_banners($conn);
    } elseif ($listType === 'services') {
        fruitwala_home_maybe_migrate_services($conn);
    } elseif ($listType === 'instagram') {
        fruitwala_home_maybe_migrate_instagram_tiles($conn);
    } elseif ($listType === 'testimonials') {
        fruitwala_home_maybe_migrate_testimonials($conn);
    } elseif ($listType === 'gallery') {
        fruitwala_home_maybe_migrate_gallery_items($conn);
    } elseif ($listType === 'gallery_strip_sidebar') {
        fruitwala_home_maybe_migrate_gallery_strip_sidebar_rows($conn);
    }
    $itemForm = fruitwala_admin_home_list_item_form($conn, $slug, $listType);
}

$pageTitle = (string) $meta['title'];
if ($slug === 'testimonials') {
    $activeNav = 'testimonial';
} elseif ($slug === 'gallery') {
    $activeNav = 'gallery';
} elseif ($slug === 'gallery_strip_sidebar') {
    $activeNav = 'gallery_strip_sidebar';
} else {
    $activeNav = 'home';
}
$activeHomeSlug = $slug;
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <h2><?= htmlspecialchars((string) $meta['title'], ENT_QUOTES, 'UTF-8') ?></h2>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
    <a class="btn btn-ghost btn-sm" href="home.php"><i class="fas fa-arrow-left"></i> All sections</a>
    <a class="btn btn-primary btn-sm" href="../index.php" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> View site</a>
  </div>
</div>

<?php if ($flash !== ''): ?>
  <div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<?php if ($isHeroSlides): ?>
  <?php if (is_array($heroForm)): ?>
    <p style="margin:0 0 1rem">
      <a class="btn btn-ghost btn-sm" href="home_edit.php?s=hero"><i class="fas fa-arrow-left"></i> Back to slides</a>
    </p>
    <form method="post" action="home_edit.php?s=hero" class="admin-form" enctype="multipart/form-data">
      <?= admin_csrf_field() ?>
      <input type="hidden" name="hero_save" value="1">
      <input type="hidden" name="slide_id" value="<?= (int) $heroForm['id'] ?>">
      <input type="hidden" name="hero_image_current" value="<?= htmlspecialchars((string) $heroForm['image'], ENT_QUOTES, 'UTF-8') ?>">

      <div class="admin-card" style="margin-bottom:1.25rem">
        <div class="admin-card-header"><?= (int) $heroForm['id'] > 0 ? 'Edit slide' : 'New slide' ?></div>
        <div class="admin-card-body">
          <div class="form-group">
            <label for="hero_kicker">Top line (small heading)</label>
            <input type="text" id="hero_kicker" name="kicker" value="<?= htmlspecialchars((string) $heroForm['kicker'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="hero_headline_main">Main title (animated letters, e.g. Fruitwala)</label>
            <input type="text" id="hero_headline_main" name="headline_main" value="<?= htmlspecialchars((string) ($heroForm['headline_main'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px" maxlength="180">
            <p style="margin:0.35rem 0 0;font-size:0.75rem;color:var(--admin-muted)">Leave blank to use “Fruitwala” on the site.</p>
          </div>
          <div class="form-group">
            <label for="hero_headline_sub">Second line (e.g. Breakfast)</label>
            <input type="text" id="hero_headline_sub" name="headline_sub" value="<?= htmlspecialchars((string) ($heroForm['headline_sub'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px" maxlength="180">
            <p style="margin:0.35rem 0 0;font-size:0.75rem;color:var(--admin-muted)">Leave blank to use “Breakfast” on the site.</p>
          </div>
          <div class="form-group">
            <label for="hero_description">Description</label>
            <textarea id="hero_description" name="description" rows="4" style="width:100%;max-width:640px;padding:0.65rem 0.85rem;border-radius:10px;border:1px solid var(--admin-border);background:var(--admin-surface-2);color:var(--admin-text);font-family:inherit;font-size:0.9rem"><?= htmlspecialchars((string) $heroForm['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
          </div>
          <div class="form-group">
            <label for="hero_btn_text">Button text</label>
            <input type="text" id="hero_btn_text" name="btn_text" value="<?= htmlspecialchars((string) $heroForm['btn_text'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="hero_btn_url">Button link (e.g. contact.php)</label>
            <input type="text" id="hero_btn_url" name="btn_url" value="<?= htmlspecialchars((string) $heroForm['btn_url'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
          </div>
          <div class="form-group">
            <label for="hero_image">Right image path</label>
            <input type="text" id="hero_image" name="image" value="<?= htmlspecialchars((string) $heroForm['image'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
            <p style="margin:0.35rem 0 0;font-size:0.75rem;color:var(--admin-muted)">Relative to the site root (e.g. assets/images/banner/slider1.png), or upload below.</p>
          </div>
          <?php
            $heroImg = (string) ($heroForm['image'] ?? '');
          if ($heroImg !== '') {
              $heroPreviewSrc = '../' . ltrim(str_replace('\\', '/', $heroImg), '/');
              ?>
          <div class="form-group">
            <span class="label-like" style="display:block;margin-bottom:0.35rem;font-weight:600;font-size:0.9rem">Current image preview</span>
            <div style="margin-top:0.25rem">
              <img src="<?= htmlspecialchars($heroPreviewSrc, ENT_QUOTES, 'UTF-8') ?>" alt="" style="max-height:140px;border-radius:10px;border:1px solid var(--admin-border);vertical-align:middle">
            </div>
          </div>
              <?php
          }
          ?>
          <div class="form-group">
            <label for="hero_slide_image"><?= (int) $heroForm['id'] > 0 ? 'Replace with upload' : 'Upload image' ?></label>
            <input type="file" id="hero_slide_image" name="hero_slide_image" accept="image/jpeg,image/png,image/gif,image/webp">
            <p style="margin:0.35rem 0 0;font-size:0.75rem;color:var(--admin-muted)">JPEG, PNG, GIF, or WebP. Max 5 MB. Saved under uploads/home_hero/.</p>
          </div>
        </div>
      </div>

      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save slide</button>
    </form>
  <?php else: ?>
    <?php
    $heroList = [];
    $lq = mysqli_query($conn, 'SELECT id, sort_order, kicker, headline_main, headline_sub, description, btn_text, btn_url, image FROM home_hero_slides ORDER BY sort_order ASC, id ASC');
    if ($lq) {
        while ($row = mysqli_fetch_assoc($lq)) {
            $heroList[] = $row;
        }
        mysqli_free_result($lq);
    }
    ?>
    <div class="admin-card hero-dt-card">
      <div class="admin-card-header">Hero slides</div>
      <div class="admin-card-body hero-dt-shell" style="overflow-x:auto">
        <div class="hero-dt-add-row">
          <a class="hero-dt-btn-add" href="home_edit.php?s=hero&amp;sub=add"><i class="fas fa-plus"></i> Add slide</a>
        </div>
        <table id="heroSlidesTable" class="hero-dt-table" style="width:100%">
          <thead>
            <tr>
              <th>Order</th>
              <th>Top line</th>
              <th>Button</th>
              <th>Image</th>
              <th class="hero-dt-col-actions">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($heroList as $row): ?>
              <tr>
                <td><?= (int) $row['sort_order'] ?></td>
                <td><?php
                  $hk = (string) $row['kicker'];
                  if (function_exists('mb_strlen') && function_exists('mb_substr') && mb_strlen($hk, 'UTF-8') > 80) {
                      $hk = mb_substr($hk, 0, 79, 'UTF-8') . '…';
                  } elseif (strlen($hk) > 80) {
                      $hk = substr($hk, 0, 77) . '...';
                  }
                  echo htmlspecialchars($hk, ENT_QUOTES, 'UTF-8');
                ?></td>
                <td><?php
                  $bt = (string) $row['btn_text'];
                  if (function_exists('mb_strlen') && function_exists('mb_substr') && mb_strlen($bt, 'UTF-8') > 40) {
                      $bt = mb_substr($bt, 0, 39, 'UTF-8') . '…';
                  } elseif (strlen($bt) > 40) {
                      $bt = substr($bt, 0, 37) . '...';
                  }
                  echo htmlspecialchars($bt, ENT_QUOTES, 'UTF-8');
                ?></td>
                <td><?php fruitwala_admin_home_list_image_thumb_cell((string) $row['image'], (string) $row['kicker']); ?></td>
                <td class="hero-dt-col-actions">
                  <div class="hero-dt-actions-inner">
                    <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="home_edit.php?s=hero&amp;sub=edit&amp;id=<?= (int) $row['id'] ?>" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span></a>
                    <form class="hero-dt-icon-form" method="post" action="home_edit.php?s=hero" onsubmit="return confirm('Delete this slide?');">
                      <?= admin_csrf_field() ?>
                      <input type="hidden" name="hero_delete_id" value="<?= (int) $row['id'] ?>">
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
          var $t = $('#heroSlidesTable');
          if (!$t.length) return;
          $t.DataTable({
            dom: "<'hero-dt-bar'lf>rt<'hero-dt-foot'ip>",
            order: [[0, 'asc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            columnDefs: [{ orderable: false, targets: [3, 4] }],
            language: {
              lengthMenu: 'Show _MENU_ entries',
              search: 'Search:',
              info: 'Showing _START_ to _END_ of _TOTAL_ entries',
              infoEmpty: 'Showing 0 to 0 of 0 entries',
              infoFiltered: '(filtered from _MAX_ total entries)',
              paginate: { previous: 'Previous', next: 'Next' },
              emptyTable: 'No slides yet. Click “Add slide” to create one.'
            },
            headerCallback: function (thead) {
              $(thead).find('th').last().removeClass('sorting sorting_asc sorting_desc').addClass('sorting_disabled');
            },
            initComplete: function () {
              $t.closest('.dataTables_wrapper').find('.dataTables_filter input[type="search"]').attr('placeholder', 'Search slides…');
            }
          });
        });
      })();
    </script>
  <?php endif; ?>

<?php elseif ($listType): ?>
  <?php fruitwala_admin_render_home_list_ui($conn, $slug, $listType, $itemForm); ?>

<?php else: ?>

<form method="post" action="home_edit.php?s=<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>" class="admin-form" enctype="multipart/form-data">
  <?= admin_csrf_field() ?>

  <?php foreach ($meta['blocks'] as $block): ?>
    <div class="admin-card" style="margin-bottom:1.25rem">
      <div class="admin-card-header"><?= htmlspecialchars($block['legend'], ENT_QUOTES, 'UTF-8') ?></div>
      <div class="admin-card-body">
        <?php foreach ($block['fields'] as $field): ?>
          <?php
            $sk = (string) $field['section_key'];
            $fk = (string) $field['field_key'];
            $cur = $home[$sk][$fk] ?? '';
            $fid = 'f_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $sk . '_' . $fk);
          ?>
          <div class="form-group">
            <label for="<?= htmlspecialchars($fid, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) $field['label'], ENT_QUOTES, 'UTF-8') ?></label>
            <?php if (($field['type'] ?? 'text') === 'textarea'): ?>
              <textarea id="<?= htmlspecialchars($fid, ENT_QUOTES, 'UTF-8') ?>" name="f[<?= htmlspecialchars($sk, ENT_QUOTES, 'UTF-8') ?>][<?= htmlspecialchars($fk, ENT_QUOTES, 'UTF-8') ?>]" rows="4" style="width:100%;max-width:640px;padding:0.65rem 0.85rem;border-radius:10px;border:1px solid var(--admin-border);background:var(--admin-surface-2);color:var(--admin-text);font-family:inherit;font-size:0.9rem"><?= htmlspecialchars((string) $cur, ENT_QUOTES, 'UTF-8') ?></textarea>
            <?php elseif (($field['type'] ?? 'text') === 'image_upload'): ?>
              <input type="hidden" name="f[<?= htmlspecialchars($sk, ENT_QUOTES, 'UTF-8') ?>][<?= htmlspecialchars($fk, ENT_QUOTES, 'UTF-8') ?>]" value="<?= htmlspecialchars((string) $cur, ENT_QUOTES, 'UTF-8') ?>">
              <input type="file" id="<?= htmlspecialchars($fid, ENT_QUOTES, 'UTF-8') ?>" name="home_quality_icon[<?= htmlspecialchars($sk, ENT_QUOTES, 'UTF-8') ?>]" accept="image/jpeg,image/png,image/gif,image/webp">
              <p style="margin:0.35rem 0 0;font-size:0.75rem;color:var(--admin-muted)">JPEG, PNG, GIF, or WebP. Max 5 MB. Files save under uploads/home_quality/.</p>
              <?php
                $qImg = (string) $cur;
              if ($qImg !== '') {
                  $qPreview = '../' . ltrim(str_replace('\\', '/', $qImg), '/');
                  ?>
              <div style="margin-top:0.5rem">
                <span style="display:block;margin-bottom:0.25rem;font-weight:600;font-size:0.85rem">Current image</span>
                <img src="<?= htmlspecialchars($qPreview, ENT_QUOTES, 'UTF-8') ?>" alt="" style="max-height:72px;border-radius:8px;border:1px solid var(--admin-border);vertical-align:middle">
              </div>
                  <?php
              }
              ?>
            <?php else: ?>
              <input type="text" id="<?= htmlspecialchars($fid, ENT_QUOTES, 'UTF-8') ?>" name="f[<?= htmlspecialchars($sk, ENT_QUOTES, 'UTF-8') ?>][<?= htmlspecialchars($fk, ENT_QUOTES, 'UTF-8') ?>]" value="<?= htmlspecialchars((string) $cur, ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
            <?php endif; ?>
            <?php if (!empty($field['hint'])): ?>
              <p style="margin:0.35rem 0 0;font-size:0.75rem;color:var(--admin-muted)"><?= htmlspecialchars((string) $field['hint'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>

  <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save changes</button>
</form>

<?php endif; ?>

<?php require __DIR__ . '/includes/layout_footer.php'; ?>
