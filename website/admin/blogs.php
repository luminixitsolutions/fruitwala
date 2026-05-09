<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/upload_image.php';
require_once __DIR__ . '/includes/home_edit_list_section.php';
require_once dirname(__DIR__) . '/includes/blogs_db.php';

admin_require_login();
fruitwala_blogs_ensure_table($conn);

$flash = '';
if (!empty($_SESSION['admin_flash'])) {
    $flash = (string) $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_verify()) {
    if (isset($_POST['blog_delete_id'])) {
        $did = (int) $_POST['blog_delete_id'];
        if ($did > 0) {
            $oldImg = '';
            $stSel = mysqli_prepare($conn, 'SELECT image FROM blogs WHERE id = ? LIMIT 1');
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
            $st = mysqli_prepare($conn, 'DELETE FROM blogs WHERE id = ? LIMIT 1');
            if ($st) {
                mysqli_stmt_bind_param($st, 'i', $did);
                mysqli_stmt_execute($st);
                mysqli_stmt_close($st);
            }
            fruitwala_admin_remove_blog_upload_file($oldImg !== '' ? $oldImg : null);
        }
        $_SESSION['admin_flash'] = 'Blog removed.';
        header('Location: blogs.php');
        exit;
    }

    if (isset($_POST['blog_save'])) {
        $rowId = (int) ($_POST['blog_row_id'] ?? 0);
        $title = trim((string) ($_POST['blog_title'] ?? ''));
        $excerpt = str_replace("\r\n", "\n", trim((string) ($_POST['blog_excerpt'] ?? '')));
        $author = trim((string) ($_POST['blog_author'] ?? ''));
        $category = trim((string) ($_POST['blog_category'] ?? ''));
        $content = str_replace("\r\n", "\n", trim((string) ($_POST['blog_content'] ?? '')));
        $sortOrder = (int) ($_POST['blog_sort_order'] ?? 0);
        $isActive = isset($_POST['blog_is_active']) ? 1 : 0;
        $currentImage = trim((string) ($_POST['blog_image_current'] ?? ''));
        $imagePathField = trim((string) ($_POST['blog_image_path'] ?? ''));

        if ($title === '') {
            $_SESSION['admin_flash'] = 'Title is required.';
            header('Location: blogs.php' . ($rowId > 0 ? '?sub=edit&id=' . $rowId : '?sub=add'));
            exit;
        }

        $upload = fruitwala_admin_save_blog_featured_upload();
        if ($upload['error'] !== null) {
            $_SESSION['admin_flash'] = $upload['error'];
            header('Location: blogs.php' . ($rowId > 0 ? '?sub=edit&id=' . $rowId : '?sub=add'));
            exit;
        }

        $image = $upload['path'] !== null ? $upload['path'] : ($imagePathField !== '' ? $imagePathField : $currentImage);
        if ($upload['path'] !== null) {
            if ($rowId > 0 && $currentImage !== '' && $currentImage !== $upload['path'] && strpos($currentImage, 'uploads/blogs/') === 0) {
                fruitwala_admin_remove_blog_upload_file($currentImage);
            }
        }

        if ($author === '') {
            $author = 'Fruitwala Team';
        }

        if ($rowId > 0) {
            $st = mysqli_prepare(
                $conn,
                'UPDATE blogs SET sort_order = ?, title = ?, excerpt = ?, author = ?, category = ?, image = ?, content = ?, is_active = ? WHERE id = ? LIMIT 1'
            );
            if ($st) {
                mysqli_stmt_bind_param(
                    $st,
                    'issssssii',
                    $sortOrder,
                    $title,
                    $excerpt,
                    $author,
                    $category,
                    $image,
                    $content,
                    $isActive,
                    $rowId
                );
                mysqli_stmt_execute($st);
                mysqli_stmt_close($st);
            }
            $_SESSION['admin_flash'] = 'Blog updated.';
        } else {
            $next = fruitwala_admin_home_list_next_sort($conn, 'blogs');
            $st = mysqli_prepare(
                $conn,
                'INSERT INTO blogs (sort_order, title, excerpt, author, category, image, content, is_active) VALUES (?,?,?,?,?,?,?,?)'
            );
            if ($st) {
                mysqli_stmt_bind_param(
                    $st,
                    'issssssi',
                    $next,
                    $title,
                    $excerpt,
                    $author,
                    $category,
                    $image,
                    $content,
                    $isActive
                );
                mysqli_stmt_execute($st);
                mysqli_stmt_close($st);
            }
            $_SESSION['admin_flash'] = 'Blog added.';
        }
        header('Location: blogs.php');
        exit;
    }
}

$sub = (string) ($_GET['sub'] ?? '');
if (!in_array($sub, ['add', 'edit'], true)) {
    $sub = '';
}
$editId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$itemForm = null;
if ($sub === 'add') {
    $itemForm = [
        'id' => 0,
        'sort_order' => 0,
        'title' => '',
        'excerpt' => '',
        'author' => 'Fruitwala Team',
        'category' => '',
        'image' => '',
        'content' => '',
        'is_active' => 1,
    ];
} elseif ($sub === 'edit' && $editId > 0) {
    $stmt = mysqli_prepare(
        $conn,
        'SELECT id, sort_order, title, excerpt, author, category, image, content, is_active FROM blogs WHERE id = ? LIMIT 1'
    );
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $editId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
        if (is_array($row)) {
            $itemForm = $row;
            $itemForm['id'] = (int) $itemForm['id'];
            $itemForm['sort_order'] = (int) $itemForm['sort_order'];
            $itemForm['is_active'] = (int) $itemForm['is_active'];
        }
    }
    if ($itemForm === null) {
        $_SESSION['admin_flash'] = 'Blog not found.';
        header('Location: blogs.php');
        exit;
    }
} elseif ($sub === 'edit') {
    header('Location: blogs.php');
    exit;
}

$pageTitle = 'Blogs';
$activeNav = 'blogs';
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <h2>Blogs</h2>
  <?php if ($itemForm === null): ?>
    <a class="btn btn-primary btn-sm" href="blogs.php?sub=add"><i class="fas fa-plus"></i> Add blog</a>
  <?php endif; ?>
</div>

<?php if ($flash !== ''): ?>
  <?php
    $flashErr = (strpos($flash, 'required') !== false)
      || (strpos($flash, 'Invalid') !== false)
      || (strpos($flash, 'failed') !== false)
      || (strpos($flash, 'not found') !== false)
      || (strpos($flash, 'upload') !== false)
      || (strpos($flash, 'Upload') !== false)
      || (strpos($flash, 'Image') !== false)
      || (strpos($flash, 'JPEG') !== false);
  ?>
  <div class="alert <?= $flashErr ? 'alert-error' : 'alert-success' ?>">
    <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>

<?php if ($itemForm !== null): ?>
  <?php
    $existingBlogImg = trim((string) ($itemForm['image'] ?? ''));
    $blogImgPreviewSrc = $existingBlogImg !== '' ? '../' . ltrim(str_replace('\\', '/', $existingBlogImg), '/') : '';
  ?>
  <style>
    .blog-master-form { width: 100%; max-width: 100%; }
    .blog-master-card { margin-bottom: 1.25rem; width: 100%; max-width: 100%; }
    .blog-master-card .admin-card-body { padding: 1.35rem 1.5rem; }
    .blog-master-form .blog-form-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 1rem 1.5rem;
      width: 100%;
    }
    .blog-master-form .blog-form-span-2 { grid-column: 1 / -1; }
    .blog-master-form .blog-form-grid .form-group { margin-bottom: 0; }
    .blog-master-form .blog-form-align-end {
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      align-items: flex-start;
    }
    .blog-master-form .blog-form-align-end .form-check { margin-top: 0.25rem; }
    .blog-master-form input[type="text"],
    .blog-master-form input[type="number"],
    .blog-master-form input[type="file"],
    .blog-master-form textarea {
      max-width: none;
    }
    .blog-master-form .blog-form-image-panel {
      grid-column: 1 / -1;
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 1rem 1.5rem;
      padding: 1.1rem 1.25rem;
      background: var(--admin-surface-2);
      border-radius: 12px;
      border: 1px solid var(--admin-border);
    }
    .blog-master-form .blog-form-image-panel .blog-preview-img {
      max-height: 180px;
      max-width: 100%;
      width: auto;
      border-radius: 10px;
      border: 1px solid var(--admin-border);
      vertical-align: middle;
    }
    .blog-master-form .blog-field-hint {
      margin: 0.35rem 0 0;
      font-size: 0.75rem;
      color: var(--admin-muted);
    }
    @media (max-width: 720px) {
      .blog-master-form .blog-form-grid,
      .blog-master-form .blog-form-image-panel {
        grid-template-columns: 1fr;
      }
      .blog-master-form .blog-form-span-2 { grid-column: 1; }
    }
  </style>
  <p style="margin:0 0 1rem">
    <a class="btn btn-ghost btn-sm" href="blogs.php"><i class="fas fa-arrow-left"></i> Back to list</a>
  </p>
  <form method="post" action="blogs.php" class="admin-form blog-master-form" enctype="multipart/form-data">
    <?= admin_csrf_field() ?>
    <input type="hidden" name="blog_save" value="1">
    <input type="hidden" name="blog_row_id" value="<?= (int) $itemForm['id'] ?>">
    <input type="hidden" name="blog_image_current" value="<?= htmlspecialchars($existingBlogImg, ENT_QUOTES, 'UTF-8') ?>">
    <div class="admin-card blog-master-card">
      <div class="admin-card-header"><?= (int) $itemForm['id'] > 0 ? 'Edit blog' : 'New blog' ?></div>
      <div class="admin-card-body">
        <div class="blog-form-grid">
          <div class="form-group">
            <label for="blog_sort_order">Sort order (lower = first on the site)</label>
            <input type="number" id="blog_sort_order" name="blog_sort_order" value="<?= (int) $itemForm['sort_order'] ?>">
          </div>
          <div class="form-group blog-form-align-end">
            <span class="label-like" style="display:block;margin-bottom:0.35rem;font-weight:600;font-size:0.8rem;color:var(--admin-muted)">Status</span>
            <div class="form-check">
              <input type="checkbox" id="blog_is_active" name="blog_is_active" value="1" <?= (int) $itemForm['is_active'] ? 'checked' : '' ?>>
              <label for="blog_is_active" style="margin:0;color:var(--admin-text)">Published (visible on website)</label>
            </div>
          </div>

          <div class="form-group blog-form-span-2">
            <label for="blog_title">Title</label>
            <input type="text" id="blog_title" name="blog_title" required maxlength="255" value="<?= htmlspecialchars((string) $itemForm['title'], ENT_QUOTES, 'UTF-8') ?>">
          </div>
          <div class="form-group blog-form-span-2">
            <label for="blog_excerpt">Short description (listing card)</label>
            <textarea id="blog_excerpt" name="blog_excerpt" rows="3"><?= htmlspecialchars((string) $itemForm['excerpt'], ENT_QUOTES, 'UTF-8') ?></textarea>
          </div>

          <div class="form-group">
            <label for="blog_author">Author</label>
            <input type="text" id="blog_author" name="blog_author" maxlength="120" value="<?= htmlspecialchars((string) $itemForm['author'], ENT_QUOTES, 'UTF-8') ?>">
          </div>
          <div class="form-group">
            <label for="blog_category">Category / tag (e.g. Health Tips)</label>
            <input type="text" id="blog_category" name="blog_category" maxlength="120" value="<?= htmlspecialchars((string) $itemForm['category'], ENT_QUOTES, 'UTF-8') ?>">
          </div>

          <div class="form-group blog-form-span-2">
            <label for="blog_content">Full article</label>
            <textarea id="blog_content" name="blog_content" rows="12" placeholder="Use blank lines between paragraphs."><?= htmlspecialchars((string) $itemForm['content'], ENT_QUOTES, 'UTF-8') ?></textarea>
            <p class="blog-field-hint">Paragraphs are separated by a blank line. Text is shown safely on the public page.</p>
          </div>

          <div class="blog-form-image-panel">
            <div class="form-group">
              <?php if ($blogImgPreviewSrc !== ''): ?>
                <span class="label-like" style="display:block;margin-bottom:0.35rem;font-weight:600;font-size:0.8rem;color:var(--admin-muted)">Current image</span>
                <div>
                  <img class="blog-preview-img" src="<?= htmlspecialchars($blogImgPreviewSrc, ENT_QUOTES, 'UTF-8') ?>" alt="" onerror="this.style.display='none'">
                </div>
                <p class="blog-field-hint"><?= (int) $itemForm['id'] > 0 ? 'Upload a new file on the right to replace it.' : '' ?></p>
              <?php else: ?>
                <span class="label-like" style="display:block;margin-bottom:0.35rem;font-weight:600;font-size:0.8rem;color:var(--admin-muted)">Featured image</span>
                <p class="blog-field-hint" style="margin:0">Upload a file or enter a path below. Both are optional if you add a blog without a hero image.</p>
              <?php endif; ?>
              <label for="blog_image_path" style="margin-top:1rem">Or image URL / path</label>
              <input type="text" id="blog_image_path" name="blog_image_path" maxlength="255" value="" placeholder="e.g. assets/images/19.png" autocomplete="off">
              <p class="blog-field-hint">Optional. Ignored when you upload a file (upload wins). <?= (int) $itemForm['id'] > 0 ? 'Leave blank to keep the existing image when not uploading.' : '' ?></p>
            </div>
            <div class="form-group">
              <label for="blog_featured"><?= (int) $itemForm['id'] > 0 ? 'Replace featured image' : 'Upload featured image' ?></label>
              <input type="file" id="blog_featured" name="blog_featured" accept="image/jpeg,image/png,image/gif,image/webp">
              <p class="blog-field-hint">JPEG, PNG, GIF, or WebP. Max 5 MB.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save blog</button>
  </form>
<?php else: ?>
  <?php
    $rows = [];
    $q = mysqli_query(
        $conn,
        'SELECT id, sort_order, title, excerpt, author, category, image, is_active FROM blogs ORDER BY sort_order ASC, id ASC'
    );
    if ($q) {
        while ($r = mysqli_fetch_assoc($q)) {
            $rows[] = $r;
        }
        mysqli_free_result($q);
    }
    $tableId = 'blogsAdminTable';
  ?>
  <div class="admin-card hero-dt-card">
    <div class="admin-card-header">All blogs</div>
    <div class="admin-card-body hero-dt-shell" style="overflow-x:auto">
      <div class="hero-dt-add-row">
        <a class="hero-dt-btn-add" href="blogs.php?sub=add"><i class="fas fa-plus"></i> Add blog</a>
      </div>
      <table id="<?= htmlspecialchars($tableId, ENT_QUOTES, 'UTF-8') ?>" class="hero-dt-table" style="width:100%">
        <thead>
          <tr>
            <th>Order</th>
            <th>Title</th>
            <th>Category</th>
            <th>Author</th>
            <th>Excerpt</th>
            <th>Image</th>
            <th>Status</th>
            <th class="hero-dt-col-actions">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $row): ?>
            <tr>
              <td><?= (int) $row['sort_order'] ?></td>
              <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['title'], 48), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['category'], 24), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['author'], 28), ENT_QUOTES, 'UTF-8') ?></td>
              <td><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['excerpt'], 56), ENT_QUOTES, 'UTF-8') ?></td>
              <td><code style="font-size:0.8rem"><?= htmlspecialchars(fruitwala_admin_home_list_trunc((string) $row['image'], 36), ENT_QUOTES, 'UTF-8') ?></code></td>
              <td>
                <?php if ((int) $row['is_active']): ?>
                  <span class="badge badge-on">Live</span>
                <?php else: ?>
                  <span class="badge badge-off">Draft</span>
                <?php endif; ?>
              </td>
              <td class="hero-dt-col-actions">
                <div class="hero-dt-actions-inner">
                  <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="blogs.php?sub=edit&amp;id=<?= (int) $row['id'] ?>" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span></a>
                  <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="../blog-details.php?id=<?= (int) $row['id'] ?>" target="_blank" rel="noopener" title="View on site"><i class="fas fa-external-link-alt" aria-hidden="true"></i><span class="visually-hidden">View</span></a>
                  <form class="hero-dt-icon-form" method="post" action="blogs.php" onsubmit="return confirm('Delete this blog?');">
                    <?= admin_csrf_field() ?>
                    <input type="hidden" name="blog_delete_id" value="<?= (int) $row['id'] ?>">
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
    fruitwala_admin_home_list_datatable_assets($tableId, 7, 'No blogs yet. Click “Add blog” to create one.');
endif;

require __DIR__ . '/includes/layout_footer.php';
