<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once dirname(__DIR__) . '/includes/portfolio_items.php';
require_once __DIR__ . '/includes/upload_image.php';

admin_require_login();
fruitwala_portfolio_ensure_table($conn);

$flash = '';
if (!empty($_SESSION['admin_flash'])) {
    $flash = (string) $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_verify()) {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'add') {
        $label = trim((string) ($_POST['label'] ?? ''));
        $video = trim((string) ($_POST['video'] ?? ''));
        $alt = trim((string) ($_POST['alt'] ?? ''));
        $sort = (int) ($_POST['sort_order'] ?? 0);
        $active = isset($_POST['is_active']) ? 1 : 0;
        $upload = fruitwala_admin_save_portfolio_cover_upload();
        if ($upload['error'] !== null) {
            $_SESSION['admin_flash'] = $upload['error'];
            header('Location: portfolio_items.php?sub=add');
            exit;
        }
        if ($upload['path'] === null || $upload['path'] === '') {
            $_SESSION['admin_flash'] = 'Please choose a cover image to upload.';
            header('Location: portfolio_items.php?sub=add');
            exit;
        }
        if ($video === '') {
            $_SESSION['admin_flash'] = 'Video path is required.';
            header('Location: portfolio_items.php?sub=add');
            exit;
        }
        $cover = $upload['path'];
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO portfolio_items (sort_order, label, video, cover, alt, is_active) VALUES (?, ?, ?, ?, ?, ?)'
        );
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'issssi', $sort, $label, $video, $cover, $alt, $active);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['admin_flash'] = 'Portfolio item added.';
        } else {
            $_SESSION['admin_flash'] = 'Could not save. Check the database.';
        }
        header('Location: portfolio_items.php');
        exit;
    }

    if ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $label = trim((string) ($_POST['label'] ?? ''));
        $video = trim((string) ($_POST['video'] ?? ''));
        $coverCurrent = trim((string) ($_POST['portfolio_cover_current'] ?? ''));
        $alt = trim((string) ($_POST['alt'] ?? ''));
        $sort = (int) ($_POST['sort_order'] ?? 0);
        $active = isset($_POST['is_active']) ? 1 : 0;
        $upload = fruitwala_admin_save_portfolio_cover_upload();
        if ($upload['error'] !== null) {
            $_SESSION['admin_flash'] = $upload['error'];
            header('Location: portfolio_items.php' . ($id > 0 ? '?sub=edit&id=' . $id : ''));
            exit;
        }
        if ($upload['path'] !== null && $upload['path'] !== '') {
            fruitwala_admin_remove_portfolio_upload_file($coverCurrent);
            $cover = $upload['path'];
        } else {
            $cover = $coverCurrent;
        }
        if ($id < 1 || $video === '' || $cover === '') {
            $_SESSION['admin_flash'] = 'Video is required. Upload a new cover or keep the existing one.';
            header('Location: portfolio_items.php' . ($id > 0 ? '?sub=edit&id=' . $id : ''));
            exit;
        }
        $stmt = mysqli_prepare(
            $conn,
            'UPDATE portfolio_items SET sort_order = ?, label = ?, video = ?, cover = ?, alt = ?, is_active = ? WHERE id = ?'
        );
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'issssii', $sort, $label, $video, $cover, $alt, $active, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['admin_flash'] = 'Portfolio item updated.';
        }
        header('Location: portfolio_items.php');
        exit;
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $oldCover = '';
            $stc = mysqli_prepare($conn, 'SELECT cover FROM portfolio_items WHERE id = ? LIMIT 1');
            if ($stc) {
                mysqli_stmt_bind_param($stc, 'i', $id);
                mysqli_stmt_execute($stc);
                $rc = mysqli_stmt_get_result($stc);
                if ($rc && ($crow = mysqli_fetch_assoc($rc))) {
                    $oldCover = (string) ($crow['cover'] ?? '');
                }
                if ($rc) {
                    mysqli_free_result($rc);
                }
                mysqli_stmt_close($stc);
            }
            fruitwala_admin_remove_portfolio_upload_file($oldCover !== '' ? $oldCover : null);
            $stmt = mysqli_prepare($conn, 'DELETE FROM portfolio_items WHERE id = ?');
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['admin_flash'] = 'Portfolio item removed.';
            }
        }
        header('Location: portfolio_items.php');
        exit;
    }
}

$sub = (string) ($_GET['sub'] ?? '');
$onForm = in_array($sub, ['add', 'edit'], true);
$editRow = null;
$editId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($sub === 'edit' && $editId > 0) {
    $stmt = mysqli_prepare(
        $conn,
        'SELECT id, sort_order, label, video, cover, alt, is_active FROM portfolio_items WHERE id = ? LIMIT 1'
    );
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $editId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $editRow = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
    }
    if (!$editRow) {
        $_SESSION['admin_flash'] = 'Item not found.';
        header('Location: portfolio_items.php');
        exit;
    }
} elseif ($sub === 'edit') {
    header('Location: portfolio_items.php');
    exit;
}

$listRows = [];
if (!$onForm) {
    $r = mysqli_query(
        $conn,
        'SELECT id, sort_order, label, video, cover, alt, is_active FROM portfolio_items ORDER BY sort_order ASC, id ASC'
    );
    if ($r) {
        while ($row = mysqli_fetch_assoc($r)) {
            $listRows[] = $row;
        }
        mysqli_free_result($r);
    }
}

$pageTitle = 'Portfolio';
$activeNav = 'portfolio';
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <h2>Portfolio</h2>
  <div class="admin-topbar-actions">
    <?php if ($onForm): ?>
      <a class="btn btn-ghost btn-sm" href="portfolio_items.php"><i class="fas fa-arrow-left"></i> Back to list</a>
    <?php else: ?>
      <a class="btn btn-primary btn-sm" href="portfolio_items.php?sub=add"><i class="fas fa-plus"></i> Add item</a>
      <a class="btn btn-ghost btn-sm" href="../portfolio.php" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> View page</a>
    <?php endif; ?>
  </div>
</div>

<?php if ($flash !== ''): ?>
  <?php
    $flashErr = (strpos($flash, 'required') !== false)
      || (strpos($flash, 'Invalid') !== false)
      || (strpos($flash, 'not found') !== false)
      || (strpos($flash, 'Could not') !== false)
      || (strpos($flash, 'upload') !== false)
      || (strpos($flash, 'Upload') !== false)
      || (strpos($flash, 'Please choose') !== false)
      || (strpos($flash, 'Please upload') !== false)
      || (strpos($flash, 'JPEG') !== false)
      || (strpos($flash, 'PNG') !== false)
      || (strpos($flash, 'GIF') !== false)
      || (strpos($flash, 'WebP') !== false)
      || (strpos($flash, 'between 1 byte') !== false);
  ?>
  <div class="alert <?= $flashErr ? 'alert-error' : 'alert-success' ?>">
    <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>

<?php if ($sub === 'add' || ($sub === 'edit' && $editRow)): ?>
  <?php
    $existingCover = $editRow ? trim((string) $editRow['cover']) : '';
    $coverPreviewSrc = $existingCover !== '' ? '../' . ltrim(str_replace('\\', '/', $existingCover), '/') : '';
  ?>
  <style>
    .portfolio-master-form { width: 100%; max-width: 100%; }
    .portfolio-master-card { margin-bottom: 1.25rem; width: 100%; max-width: 100%; }
    .portfolio-master-card .admin-card-body { padding: 1.1rem 1.25rem; }
    .portfolio-master-form .portfolio-form-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.85rem 1.25rem;
      width: 100%;
      align-items: start;
    }
    .portfolio-master-form .portfolio-form-span-2 { grid-column: 1 / -1; }
    .portfolio-master-form .portfolio-form-grid > .form-group { margin-bottom: 0; }
    .portfolio-master-form input[type="text"],
    .portfolio-master-form input[type="number"],
    .portfolio-master-form input[type="file"] {
      max-width: none;
    }
    .portfolio-master-form .portfolio-form-align-end {
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      align-items: flex-start;
    }
    .portfolio-master-form .portfolio-form-align-end .form-check { margin-top: 0.2rem; }
    .portfolio-master-form .portfolio-form-media-panel {
      display: grid;
      grid-template-columns: minmax(0, auto) minmax(0, 1fr);
      gap: 0.85rem 1.25rem;
      padding: 0.85rem 1rem;
      background: var(--admin-surface-2);
      border-radius: 12px;
      border: 1px solid var(--admin-border);
      align-items: start;
    }
    .portfolio-master-form .portfolio-form-media-panel--solo {
      grid-template-columns: 1fr;
    }
    .portfolio-master-form .portfolio-cover-preview-img {
      display: block;
      max-height: 120px;
      max-width: 100%;
      width: auto;
      border-radius: 10px;
      border: 1px solid var(--admin-border);
    }
    .portfolio-master-form .portfolio-field-hint {
      margin: 0.3rem 0 0;
      font-size: 0.75rem;
      color: var(--admin-muted);
    }
    .portfolio-master-form .portfolio-form-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 0.15rem;
      padding-top: 0.35rem;
    }
    @media (max-width: 720px) {
      .portfolio-master-form .portfolio-form-grid,
      .portfolio-master-form .portfolio-form-media-panel {
        grid-template-columns: 1fr;
      }
      .portfolio-master-form .portfolio-form-span-2 { grid-column: 1; }
    }
  </style>
  <div class="admin-card portfolio-master-card">
    <div class="admin-card-header"><?= $editRow ? 'Edit portfolio item' : 'Add portfolio item' ?></div>
    <div class="admin-card-body">
      <form method="post" class="admin-form portfolio-master-form" action="portfolio_items.php" enctype="multipart/form-data">
        <?= admin_csrf_field() ?>
        <input type="hidden" name="action" value="<?= $editRow ? 'update' : 'add' ?>">
        <?php if ($editRow): ?>
          <input type="hidden" name="id" value="<?= (int) $editRow['id'] ?>">
          <input type="hidden" name="portfolio_cover_current" value="<?= htmlspecialchars((string) $editRow['cover'], ENT_QUOTES, 'UTF-8') ?>">
        <?php endif; ?>
        <div class="portfolio-form-grid">
          <div class="form-group">
            <label for="label">Label (optional, for admin list)</label>
            <input type="text" id="label" name="label" maxlength="255" placeholder="e.g. Summer promo reel"
              value="<?= $editRow ? htmlspecialchars((string) $editRow['label'], ENT_QUOTES, 'UTF-8') : '' ?>">
          </div>
          <div class="form-group">
            <label for="sort_order">Sort order (lower = first)</label>
            <input type="number" id="sort_order" name="sort_order" value="<?= $editRow ? (int) $editRow['sort_order'] : '0' ?>">
          </div>

          <div class="form-group portfolio-form-span-2">
            <label for="video">Video path</label>
            <input type="text" class="admin-input--wide" id="video" name="video" required maxlength="255" placeholder="assets/videos/reels/reel1.mp4"
              value="<?= $editRow ? htmlspecialchars((string) $editRow['video'], ENT_QUOTES, 'UTF-8') : '' ?>">
          </div>

          <div class="portfolio-form-media-panel portfolio-form-span-2<?= $existingCover === '' ? ' portfolio-form-media-panel--solo' : '' ?>">
            <?php if ($existingCover !== ''): ?>
              <div class="form-group">
                <span class="label-like" style="display:block;margin-bottom:0.35rem;font-weight:600;font-size:0.8rem;color:var(--admin-muted)">Current cover</span>
                <img class="portfolio-cover-preview-img" src="<?= htmlspecialchars($coverPreviewSrc, ENT_QUOTES, 'UTF-8') ?>" alt="">
              </div>
            <?php endif; ?>
            <div class="form-group">
              <label for="portfolio_cover"><?= $editRow ? 'Replace cover image' : 'Cover image' ?></label>
              <input type="file" id="portfolio_cover" name="portfolio_cover" accept="image/jpeg,image/png,image/gif,image/webp"<?= $editRow ? '' : ' required' ?>>
              <p class="portfolio-field-hint">JPEG, PNG, GIF, or WebP. Max 5 MB. Saved under <code>uploads/portfolio/</code><?= $editRow ? ' on your server. Leave empty to keep the current cover.' : '.' ?></p>
            </div>
          </div>

          <div class="form-group">
            <label for="alt">Image alt text</label>
            <input type="text" id="alt" name="alt" maxlength="255" placeholder="Fruitwala reel"
              value="<?= $editRow ? htmlspecialchars((string) $editRow['alt'], ENT_QUOTES, 'UTF-8') : '' ?>">
          </div>
          <div class="form-group portfolio-form-align-end">
            <span class="label-like" style="display:block;margin-bottom:0.35rem;font-weight:600;font-size:0.8rem;color:var(--admin-muted)">Visibility</span>
            <div class="form-check">
              <input type="checkbox" id="is_active" name="is_active" value="1" <?= (!$editRow || (int) $editRow['is_active']) ? 'checked' : '' ?>>
              <label for="is_active" style="margin:0;color:var(--admin-text)">Visible on website</label>
            </div>
          </div>

          <div class="portfolio-form-actions portfolio-form-span-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?= $editRow ? 'Save changes' : 'Add item' ?></button>
            <a class="btn btn-ghost" href="portfolio_items.php">Cancel</a>
          </div>
        </div>
      </form>
    </div>
  </div>

<?php else: ?>

  <div class="admin-card hero-dt-card">
    <div class="admin-card-header">All portfolio items</div>
    <div class="admin-card-body hero-dt-shell" style="overflow-x:auto">
      <div class="hero-dt-add-row">
        <a class="hero-dt-btn-add" href="portfolio_items.php?sub=add"><i class="fas fa-plus"></i> Add item</a>
      </div>
      <table id="portfolioItemsTable" class="hero-dt-table" style="width:100%">
        <thead>
          <tr>
            <th>Order</th>
            <th>Label</th>
            <th>Cover</th>
            <th>Video</th>
            <th>Status</th>
            <th class="hero-dt-col-actions">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($listRows as $row): ?>
            <tr>
              <td><?= (int) $row['sort_order'] ?></td>
              <td><?php
                $lb = (string) $row['label'];
                if ($lb === '') {
                    echo '<span style="color:var(--admin-muted)">—</span>';
                } else {
                    if (function_exists('mb_strlen') && function_exists('mb_substr') && mb_strlen($lb, 'UTF-8') > 60) {
                        $lb = mb_substr($lb, 0, 59, 'UTF-8') . '…';
                    } elseif (strlen($lb) > 60) {
                        $lb = substr($lb, 0, 57) . '...';
                    }
                    echo htmlspecialchars($lb, ENT_QUOTES, 'UTF-8');
                }
              ?></td>
              <td><?php
                $cv = (string) $row['cover'];
                if ($cv !== '' && strpos(str_replace('\\', '/', $cv), 'uploads/portfolio/') === 0) {
                    $thumbSrc = '../' . ltrim(str_replace('\\', '/', $cv), '/');
                    echo '<img src="' . htmlspecialchars($thumbSrc, ENT_QUOTES, 'UTF-8') . '" alt="" style="max-height:44px;border-radius:6px;border:1px solid var(--admin-border);vertical-align:middle">';
                } elseif ($cv !== '') {
                    if (function_exists('mb_strlen') && function_exists('mb_substr') && mb_strlen($cv, 'UTF-8') > 40) {
                        $cv = mb_substr($cv, 0, 39, 'UTF-8') . '…';
                    } elseif (strlen($cv) > 40) {
                        $cv = substr($cv, 0, 37) . '...';
                    }
                    echo '<code style="font-size:0.8rem">' . htmlspecialchars($cv, ENT_QUOTES, 'UTF-8') . '</code>';
                } else {
                    echo '<span style="color:var(--admin-muted)">—</span>';
                }
              ?></td>
              <td><code style="font-size:0.8rem"><?php
                $vd = (string) $row['video'];
                if (function_exists('mb_strlen') && function_exists('mb_substr') && mb_strlen($vd, 'UTF-8') > 40) {
                    $vd = mb_substr($vd, 0, 39, 'UTF-8') . '…';
                } elseif (strlen($vd) > 40) {
                    $vd = substr($vd, 0, 37) . '...';
                }
                echo htmlspecialchars($vd, ENT_QUOTES, 'UTF-8');
              ?></code></td>
              <td>
                <?php if ((int) $row['is_active']): ?>
                  <span class="badge badge-on">Live</span>
                <?php else: ?>
                  <span class="badge badge-off">Hidden</span>
                <?php endif; ?>
              </td>
              <td class="hero-dt-col-actions">
                <div class="hero-dt-actions-inner">
                  <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="portfolio_items.php?sub=edit&amp;id=<?= (int) $row['id'] ?>" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span></a>
                  <form class="hero-dt-icon-form" method="post" action="portfolio_items.php" onsubmit="return confirm('Delete this item?');">
                    <?= admin_csrf_field() ?>
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
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
        var $t = $('#portfolioItemsTable');
        if (!$t.length) return;
        $t.DataTable({
          dom: "<'hero-dt-bar'lf>rt<'hero-dt-foot'ip>",
          order: [[0, 'asc']],
          pageLength: 10,
          lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
          columnDefs: [{ orderable: false, targets: 5 }],
          language: {
            lengthMenu: 'Show _MENU_ entries',
            search: 'Search:',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            infoEmpty: 'Showing 0 to 0 of 0 entries',
            infoFiltered: '(filtered from _MAX_ total entries)',
            paginate: { previous: 'Previous', next: 'Next' },
            emptyTable: 'No portfolio items yet. Use “Add item” to create one.'
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

<?php endif; ?>

<?php require __DIR__ . '/includes/layout_footer.php'; ?>
