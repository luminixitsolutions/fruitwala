<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/home_edit_list_section.php';
require_once __DIR__ . '/includes/upload_image.php';
require_once dirname(__DIR__) . '/includes/home_content.php';
admin_require_login();
fruitwala_home_ensure_home_list_tables($conn);
fruitwala_home_maybe_migrate_gallery_strip_sidebar_rows($conn);

$flash = '';
if (!empty($_SESSION['admin_flash'])) {
    $flash = (string) $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_verify()) {
    $id = (int) ($_POST['strip_sidebar_delete_id'] ?? 0);
    if ($id > 0) {
        $oldThumb = '';
        $stSel = mysqli_prepare($conn, 'SELECT thumb FROM home_gallery_strip_sidebar WHERE id = ? LIMIT 1');
        if ($stSel) {
            mysqli_stmt_bind_param($stSel, 'i', $id);
            mysqli_stmt_execute($stSel);
            $res = mysqli_stmt_get_result($stSel);
            $row = $res ? mysqli_fetch_assoc($res) : null;
            mysqli_stmt_close($stSel);
            if (is_array($row)) {
                $oldThumb = (string) ($row['thumb'] ?? '');
            }
        }
        $st = mysqli_prepare($conn, 'DELETE FROM home_gallery_strip_sidebar WHERE id = ? LIMIT 1');
        if ($st) {
            mysqli_stmt_bind_param($st, 'i', $id);
            mysqli_stmt_execute($st);
            mysqli_stmt_close($st);
        }
        fruitwala_admin_remove_gallery_strip_sidebar_thumb_file($oldThumb !== '' ? $oldThumb : null);
        $_SESSION['admin_flash'] = 'Sidebar post removed.';
    }
    header('Location: gallery_strip_sidebar_view.php');
    exit;
}

$rows = [];
$q = mysqli_query($conn, 'SELECT id, sort_order, thumb, title, meta1, meta2, link FROM home_gallery_strip_sidebar ORDER BY sort_order ASC, id ASC');
if ($q) {
    while ($r = mysqli_fetch_assoc($q)) {
        $rows[] = $r;
    }
    mysqli_free_result($q);
}

$pageTitle = 'Strip sidebar — View';
$activeNav = 'gallery_strip_sidebar';
$activeHomeSlug = '';
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <h2>Gallery strip — sidebar posts</h2>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
    <a class="btn btn-primary btn-sm" href="home_edit.php?s=gallery_strip_sidebar&amp;sub=add"><i class="fas fa-plus"></i> Add post</a>
    <a class="btn btn-ghost btn-sm" href="../index.php#gallery" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> View on site</a>
  </div>
</div>

<?php if ($flash !== ''): ?>
  <div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<div class="admin-card hero-dt-card">
  <div class="admin-card-header">All sidebar posts</div>
  <div class="admin-card-body hero-dt-shell" style="overflow-x:auto">
    <table id="galleryStripSidebarViewTable" class="hero-dt-table" style="width:100%">
      <thead>
        <tr>
          <th>Order</th>
          <th>Title</th>
          <th>Meta 1</th>
          <th>Meta 2</th>
          <th>Thumb</th>
          <th class="hero-dt-col-actions">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <tr>
            <td><?= (int) $row['sort_order'] ?></td>
            <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['title'], 56), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['meta1'], 28), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['meta2'], 28), ENT_QUOTES, 'UTF-8') ?></td>
            <td><?php fruitwala_admin_home_list_image_thumb_cell((string) $row['thumb'], (string) $row['title']); ?></td>
            <td class="hero-dt-col-actions">
              <div class="hero-dt-actions-inner">
                <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="home_edit.php?s=gallery_strip_sidebar&amp;sub=edit&amp;id=<?= (int) $row['id'] ?>" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span></a>
                <form class="hero-dt-icon-form" method="post" action="gallery_strip_sidebar_view.php" onsubmit="return confirm('Delete this post?');">
                  <?= admin_csrf_field() ?>
                  <input type="hidden" name="strip_sidebar_delete_id" value="<?= (int) $row['id'] ?>">
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
fruitwala_admin_home_list_datatable_assets('galleryStripSidebarViewTable', 5, 'No sidebar posts yet. Click “Add post” to create one.', [4]);
require __DIR__ . '/includes/layout_footer.php';
?>
