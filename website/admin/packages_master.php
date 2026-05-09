<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/upload_image.php';
require_once dirname(__DIR__) . '/includes/packages_db.php';

admin_require_login();
fruitwala_packages_ensure_table($conn);

$flash = '';
if (!empty($_SESSION['admin_flash'])) {
    $flash = (string) $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_verify()) {
    if (isset($_POST['package_delete_id'])) {
        $did = (int) $_POST['package_delete_id'];
        if ($did > 0) {
            $oldImg = '';
            $stSel = mysqli_prepare($conn, 'SELECT image FROM packages WHERE id = ? LIMIT 1');
            if ($stSel) {
                mysqli_stmt_bind_param($stSel, 'i', $did);
                mysqli_stmt_execute($stSel);
                $res = mysqli_stmt_get_result($stSel);
                $imgRow = $res ? mysqli_fetch_assoc($res) : null;
                mysqli_stmt_close($stSel);
                if (is_array($imgRow)) {
                    $oldImg = (string) ($imgRow['image'] ?? '');
                }
            }
            $st = mysqli_prepare($conn, 'DELETE FROM packages WHERE id = ? LIMIT 1');
            if ($st) {
                mysqli_stmt_bind_param($st, 'i', $did);
                mysqli_stmt_execute($st);
                mysqli_stmt_close($st);
            }
            fruitwala_admin_remove_package_upload_file($oldImg !== '' ? $oldImg : null);
        }
        $_SESSION['admin_flash'] = 'Package removed.';
        header('Location: packages_master.php');
        exit;
    }

    if (isset($_POST['package_save'])) {
        $rowId = (int) ($_POST['package_row_id'] ?? 0);
        $sortOrder = (int) ($_POST['package_sort_order'] ?? 0);
        $title = trim((string) ($_POST['package_title'] ?? ''));
        $deliveryLine = trim((string) ($_POST['package_delivery_line'] ?? ''));
        $salePrice = trim((string) ($_POST['package_sale_price'] ?? ''));
        $mrp = trim((string) ($_POST['package_mrp'] ?? ''));
        $badge1 = trim((string) ($_POST['package_badge_1'] ?? ''));
        $badge2 = trim((string) ($_POST['package_badge_2'] ?? ''));
        $bullets = str_replace("\r\n", "\n", trim((string) ($_POST['package_bullets'] ?? '')));
        $bookPkg = trim((string) ($_POST['package_book_name'] ?? ''));
        $currentImage = trim((string) ($_POST['package_image_current'] ?? ''));
        $active = isset($_POST['package_is_active']) ? 1 : 0;

        if ($title === '' || $bookPkg === '') {
            $_SESSION['admin_flash'] = 'Title and booking package name are required.';
            $redir = 'packages_master.php';
            if ($rowId > 0) {
                $redir .= '?sub=edit&id=' . $rowId;
            } else {
                $redir .= '?sub=add';
            }
            header('Location: ' . $redir);
            exit;
        }

        $upload = fruitwala_admin_save_package_image_upload();
        if ($upload['error'] !== null) {
            $_SESSION['admin_flash'] = $upload['error'];
            $redir = 'packages_master.php';
            if ($rowId > 0) {
                $redir .= '?sub=edit&id=' . $rowId;
            } else {
                $redir .= '?sub=add';
            }
            header('Location: ' . $redir);
            exit;
        }

        $image = $currentImage;
        if ($upload['path'] !== null) {
            if ($rowId > 0 && $currentImage !== '' && strpos(str_replace('\\', '/', $currentImage), 'uploads/packages/') === 0) {
                fruitwala_admin_remove_package_upload_file($currentImage);
            }
            $image = $upload['path'];
        }

        if ($image === '') {
            $_SESSION['admin_flash'] = 'Please upload a package image.';
            $redir = 'packages_master.php';
            if ($rowId > 0) {
                $redir .= '?sub=edit&id=' . $rowId;
            } else {
                $redir .= '?sub=add';
            }
            header('Location: ' . $redir);
            exit;
        }

        if ($rowId > 0) {
            $st = mysqli_prepare(
                $conn,
                'UPDATE packages SET sort_order = ?, title = ?, delivery_line = ?, sale_price = ?, mrp = ?, badge_1 = ?, badge_2 = ?, bullet_points = ?, book_pkg_name = ?, image = ?, is_active = ? WHERE id = ? LIMIT 1'
            );
            if ($st) {
                mysqli_stmt_bind_param(
                    $st,
                    'isssssssssii',
                    $sortOrder,
                    $title,
                    $deliveryLine,
                    $salePrice,
                    $mrp,
                    $badge1,
                    $badge2,
                    $bullets,
                    $bookPkg,
                    $image,
                    $active,
                    $rowId
                );
                mysqli_stmt_execute($st);
                mysqli_stmt_close($st);
            }
            $_SESSION['admin_flash'] = 'Package updated.';
        } else {
            if ($sortOrder === 0) {
                $sortOrder = 10;
                if ($qr = mysqli_query($conn, 'SELECT COALESCE(MAX(sort_order), 0) + 10 AS n FROM packages')) {
                    if ($r = mysqli_fetch_assoc($qr)) {
                        $sortOrder = (int) $r['n'];
                    }
                    mysqli_free_result($qr);
                }
            }
            $st = mysqli_prepare(
                $conn,
                'INSERT INTO packages (sort_order, title, delivery_line, sale_price, mrp, badge_1, badge_2, bullet_points, book_pkg_name, image, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?)'
            );
            if ($st) {
                mysqli_stmt_bind_param(
                    $st,
                    'isssssssssi',
                    $sortOrder,
                    $title,
                    $deliveryLine,
                    $salePrice,
                    $mrp,
                    $badge1,
                    $badge2,
                    $bullets,
                    $bookPkg,
                    $image,
                    $active
                );
                mysqli_stmt_execute($st);
                mysqli_stmt_close($st);
            }
            $_SESSION['admin_flash'] = 'Package added.';
        }
        header('Location: packages_master.php');
        exit;
    }
}

$sub = (string) ($_GET['sub'] ?? '');
if (!in_array($sub, ['', 'add', 'edit'], true)) {
    $sub = '';
}

$itemForm = null;
if ($sub === 'add') {
    $itemForm = [
        'id' => 0,
        'sort_order' => 0,
        'title' => '',
        'delivery_line' => '',
        'sale_price' => '',
        'mrp' => '',
        'badge_1' => '',
        'badge_2' => '',
        'bullet_points' => '',
        'book_pkg_name' => '',
        'image' => '',
        'is_active' => 1,
    ];
} elseif ($sub === 'edit') {
    $editId = (int) ($_GET['id'] ?? 0);
    if ($editId < 1) {
        header('Location: packages_master.php');
        exit;
    }
    $st = mysqli_prepare(
        $conn,
        'SELECT id, sort_order, title, delivery_line, sale_price, mrp, badge_1, badge_2, bullet_points, book_pkg_name, image, is_active FROM packages WHERE id = ? LIMIT 1'
    );
    if ($st) {
        mysqli_stmt_bind_param($st, 'i', $editId);
        mysqli_stmt_execute($st);
        $res = mysqli_stmt_get_result($st);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($st);
        if (!is_array($row)) {
            $_SESSION['admin_flash'] = 'Package not found.';
            header('Location: packages_master.php');
            exit;
        }
        $itemForm = $row;
        $itemForm['id'] = (int) $itemForm['id'];
        $itemForm['sort_order'] = (int) $itemForm['sort_order'];
        $itemForm['is_active'] = (int) $itemForm['is_active'];
    } else {
        header('Location: packages_master.php');
        exit;
    }
}

$listRows = [];
if ($itemForm === null) {
    $q = mysqli_query(
        $conn,
        'SELECT id, sort_order, title, delivery_line, sale_price, mrp, book_pkg_name, image, is_active FROM packages ORDER BY sort_order ASC, id ASC'
    );
    if ($q) {
        while ($r = mysqli_fetch_assoc($q)) {
            $listRows[] = $r;
        }
        mysqli_free_result($q);
    }
}

$pageTitle = 'Package master';
$activeNav = 'packages';
require __DIR__ . '/includes/layout_header.php';
require_once __DIR__ . '/includes/home_edit_list_section.php';
?>

<div class="admin-topbar">
  <h2>Package master</h2>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
    <a class="btn btn-ghost btn-sm" href="index.php"><i class="fas fa-arrow-left"></i> Dashboard</a>
    <a class="btn btn-primary btn-sm" href="../packages.php" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> View packages page</a>
  </div>
</div>

<?php if ($flash !== ''): ?>
  <?php
    $flashErr = (strpos($flash, 'required') !== false)
      || (strpos($flash, 'not found') !== false)
      || (strpos($flash, 'Could not') !== false)
      || (strpos($flash, 'upload') !== false)
      || (strpos($flash, 'Upload') !== false)
      || (strpos($flash, 'Image') !== false)
      || (strpos($flash, 'JPEG') !== false)
      || (strpos($flash, 'Please upload') !== false);
  ?>
  <div class="alert <?= $flashErr ? 'alert-error' : 'alert-success' ?>">
    <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>

<?php if (is_array($itemForm)): ?>
  <?php
    $existingPkgImg = trim((string) ($itemForm['image'] ?? ''));
    $pkgImgPreviewSrc = $existingPkgImg !== '' ? '../' . ltrim(str_replace('\\', '/', $existingPkgImg), '/') : '';
  ?>
  <style>
    .package-master-form { width: 100%; max-width: 100%; }
    .package-master-card { margin-bottom: 1.25rem; width: 100%; max-width: 100%; }
    .package-master-card .admin-card-body { padding: 1.35rem 1.5rem; }
    .package-master-form .package-form-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 1rem 1.5rem;
      width: 100%;
    }
    .package-master-form .package-form-span-2 { grid-column: 1 / -1; }
    .package-master-form .package-form-grid .form-group { margin-bottom: 0; }
    .package-master-form .package-form-align-end {
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      align-items: flex-start;
    }
    .package-master-form .package-form-align-end .form-check { margin-top: 0.25rem; }
    .package-master-form input[type="text"],
    .package-master-form input[type="number"],
    .package-master-form input[type="file"],
    .package-master-form textarea {
      max-width: none;
    }
    .package-master-form .package-form-image-panel {
      grid-column: 1 / -1;
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 1rem 1.5rem;
      padding: 1.1rem 1.25rem;
      background: var(--admin-surface-2);
      border-radius: 12px;
      border: 1px solid var(--admin-border);
    }
    .package-master-form .package-form-image-panel .pkg-preview-img {
      max-height: 160px;
      max-width: 100%;
      width: auto;
      border-radius: 10px;
      border: 1px solid var(--admin-border);
      vertical-align: middle;
    }
    .package-master-form .pkg-field-hint {
      margin: 0.35rem 0 0;
      font-size: 0.75rem;
      color: var(--admin-muted);
    }
    @media (max-width: 720px) {
      .package-master-form .package-form-grid,
      .package-master-form .package-form-image-panel {
        grid-template-columns: 1fr;
      }
      .package-master-form .package-form-span-2 { grid-column: 1; }
    }
  </style>
  <p style="margin:0 0 1rem">
    <a class="btn btn-ghost btn-sm" href="packages_master.php"><i class="fas fa-arrow-left"></i> Back to list</a>
  </p>
  <form method="post" action="packages_master.php" class="admin-form package-master-form" enctype="multipart/form-data">
    <?= admin_csrf_field() ?>
    <input type="hidden" name="package_save" value="1">
    <input type="hidden" name="package_row_id" value="<?= (int) $itemForm['id'] ?>">
    <input type="hidden" name="package_image_current" value="<?= htmlspecialchars($existingPkgImg, ENT_QUOTES, 'UTF-8') ?>">
    <div class="admin-card package-master-card">
      <div class="admin-card-header"><?= (int) $itemForm['id'] > 0 ? 'Edit package' : 'New package' ?></div>
      <div class="admin-card-body">
        <div class="package-form-grid">
          <div class="form-group">
            <label for="package_sort_order">Sort order (lower = first)</label>
            <input type="number" id="package_sort_order" name="package_sort_order" value="<?= (int) $itemForm['sort_order'] ?>">
          </div>
          <div class="form-group package-form-align-end">
            <span class="label-like" style="display:block;margin-bottom:0.35rem;font-weight:600;font-size:0.8rem;color:var(--admin-muted)">Visibility</span>
            <div class="form-check">
              <input type="checkbox" id="package_is_active" name="package_is_active" value="1" <?= (int) $itemForm['is_active'] ? 'checked' : '' ?>>
              <label for="package_is_active" style="margin:0;color:var(--admin-text)">Visible on website</label>
            </div>
          </div>

          <div class="form-group package-form-span-2">
            <label for="package_title">Title</label>
            <input type="text" id="package_title" name="package_title" required maxlength="255" value="<?= htmlspecialchars((string) $itemForm['title'], ENT_QUOTES, 'UTF-8') ?>">
          </div>
          <div class="form-group package-form-span-2">
            <label for="package_delivery_line">Delivery line (e.g. 6 Days Delivery …)</label>
            <input type="text" id="package_delivery_line" name="package_delivery_line" maxlength="255" value="<?= htmlspecialchars((string) $itemForm['delivery_line'], ENT_QUOTES, 'UTF-8') ?>">
          </div>

          <div class="form-group">
            <label for="package_sale_price">Sale price (display)</label>
            <input type="text" id="package_sale_price" name="package_sale_price" maxlength="32" placeholder="666" value="<?= htmlspecialchars((string) $itemForm['sale_price'], ENT_QUOTES, 'UTF-8') ?>">
          </div>
          <div class="form-group">
            <label for="package_mrp">MRP (strikethrough)</label>
            <input type="text" id="package_mrp" name="package_mrp" maxlength="32" placeholder="900" value="<?= htmlspecialchars((string) $itemForm['mrp'], ENT_QUOTES, 'UTF-8') ?>">
          </div>

          <div class="form-group">
            <label for="package_badge_1">Badge 1</label>
            <input type="text" id="package_badge_1" name="package_badge_1" maxlength="120" placeholder="e.g. Sunday Off" value="<?= htmlspecialchars((string) $itemForm['badge_1'], ENT_QUOTES, 'UTF-8') ?>">
          </div>
          <div class="form-group">
            <label for="package_badge_2">Badge 2</label>
            <input type="text" id="package_badge_2" name="package_badge_2" maxlength="120" placeholder="e.g. Free Delivery" value="<?= htmlspecialchars((string) $itemForm['badge_2'], ENT_QUOTES, 'UTF-8') ?>">
          </div>

          <div class="form-group package-form-span-2">
            <label for="package_book_name">Booking name (<code>?PkgName=</code> in Book Now link)</label>
            <input type="text" id="package_book_name" name="package_book_name" required maxlength="64" placeholder="e.g. Weekly" value="<?= htmlspecialchars((string) $itemForm['book_pkg_name'], ENT_QUOTES, 'UTF-8') ?>">
          </div>

          <div class="form-group package-form-span-2">
            <label for="package_bullets">Bullet points (one per line)</label>
            <textarea id="package_bullets" name="package_bullets" rows="5"><?= htmlspecialchars((string) $itemForm['bullet_points'], ENT_QUOTES, 'UTF-8') ?></textarea>
          </div>

          <div class="package-form-image-panel">
            <div class="form-group">
              <?php if ($pkgImgPreviewSrc !== ''): ?>
                <span class="label-like" style="display:block;margin-bottom:0.35rem;font-weight:600;font-size:0.8rem;color:var(--admin-muted)">Current image</span>
                <div>
                  <img class="pkg-preview-img" src="<?= htmlspecialchars($pkgImgPreviewSrc, ENT_QUOTES, 'UTF-8') ?>" alt="">
                </div>
                <p class="pkg-field-hint"><?= (int) $itemForm['id'] > 0 ? 'Upload a new file below to replace it.' : '' ?></p>
              <?php else: ?>
                <span class="label-like" style="display:block;margin-bottom:0.35rem;font-weight:600;font-size:0.8rem;color:var(--admin-muted)">Preview</span>
                <p class="pkg-field-hint" style="margin:0">Choose an image to see it on the site after save.</p>
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label for="package_image"><?= (int) $itemForm['id'] > 0 ? 'Replace image' : 'Package image' ?></label>
              <input type="file" id="package_image" name="package_image" accept="image/jpeg,image/png,image/gif,image/webp">
              <p class="pkg-field-hint">JPEG, PNG, GIF, or WebP. Max 5 MB.<?= (int) $itemForm['id'] > 0 ? ' Leave empty to keep the current image.' : ' Required for a new package.' ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save package</button>
  </form>
<?php else: ?>
  <div class="admin-card hero-dt-card">
    <div class="admin-card-header">Packages</div>
    <div class="admin-card-body hero-dt-shell" style="overflow-x:auto">
      <div class="hero-dt-add-row">
        <a class="hero-dt-btn-add" href="packages_master.php?sub=add"><i class="fas fa-plus"></i> Add package</a>
      </div>
      <table id="packagesTable" class="hero-dt-table" style="width:100%">
        <thead>
          <tr>
            <th>Order</th>
            <th>Title</th>
            <th>Delivery</th>
            <th>Price</th>
            <th>Book name</th>
            <th>Status</th>
            <th>Image</th>
            <th class="hero-dt-col-actions">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($listRows as $row): ?>
            <tr>
              <td><?= (int) $row['sort_order'] ?></td>
              <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['title'], 48), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['delivery_line'], 40), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars((string) $row['sale_price'], ENT_QUOTES, 'UTF-8') ?><?php if (trim((string) $row['mrp']) !== ''): ?> <span style="color:var(--admin-muted)">/ <?= htmlspecialchars((string) $row['mrp'], ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?></td>
              <td><code style="font-size:0.8rem"><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['book_pkg_name'], 24), ENT_QUOTES, 'UTF-8') ?></code></td>
              <td>
                <?php if ((int) $row['is_active']): ?>
                  <span class="badge badge-on">Live</span>
                <?php else: ?>
                  <span class="badge badge-off">Hidden</span>
                <?php endif; ?>
              </td>
              <td class="package-dt-img-cell">
                <?php
                  $imgPath = trim((string) ($row['image'] ?? ''));
                  if ($imgPath !== '') {
                      $imgSrc = '../' . ltrim(str_replace('\\', '/', $imgPath), '/');
                      $imgAlt = htmlspecialchars(fruitwala_admin_home_list_trunc((string) ($row['title'] ?? ''), 40), ENT_QUOTES, 'UTF-8');
                      ?>
                <img class="package-dt-thumb" src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8') ?>" alt="<?= $imgAlt ?>" width="56" height="56" loading="lazy" decoding="async" onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'package-dt-img-miss',textContent:'—'}))">
                  <?php
                  } else {
                      ?><span class="package-dt-img-miss">—</span><?php
                  }
                ?>
              </td>
              <td class="hero-dt-col-actions">
                <div class="hero-dt-actions-inner">
                  <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="packages_master.php?sub=edit&amp;id=<?= (int) $row['id'] ?>" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span></a>
                  <form class="hero-dt-icon-form" method="post" action="packages_master.php" onsubmit="return confirm('Delete this package?');">
                    <?= admin_csrf_field() ?>
                    <input type="hidden" name="package_delete_id" value="<?= (int) $row['id'] ?>">
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
  <style>
    .visually-hidden { position: absolute !important; width: 1px !important; height: 1px !important; padding: 0 !important; margin: -1px !important; overflow: hidden !important; clip: rect(0,0,0,0) !important; white-space: nowrap !important; border: 0 !important; }
    .admin-card.hero-dt-card { box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06), 0 8px 32px rgba(15, 23, 42, 0.06); }
    .package-dt-img-cell { vertical-align: middle; }
    .package-dt-thumb {
      display: block;
      width: 56px;
      height: 56px;
      object-fit: cover;
      border-radius: 10px;
      border: 1px solid var(--admin-border);
      background: var(--admin-surface-2);
    }
    .package-dt-img-miss { color: var(--admin-muted); font-size: 0.9rem; }
  </style>
  <?php
    fruitwala_admin_home_list_datatable_assets('packagesTable', 7, 'No packages yet. Use “Add package” to create one.');
?>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout_footer.php'; ?>
