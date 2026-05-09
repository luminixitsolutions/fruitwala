<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
admin_require_login();

$flash = '';
if (!empty($_SESSION['admin_flash'])) {
    $flash = (string) $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_verify()) {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'add') {
        $title = trim((string) ($_POST['title'] ?? ''));
        $url = trim((string) ($_POST['url'] ?? ''));
        $sort = (int) ($_POST['sort_order'] ?? 0);
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($title === '' || $url === '') {
            $_SESSION['admin_flash'] = 'Title and URL are required.';
        } else {
            $stmt = mysqli_prepare($conn, 'INSERT INTO nav_menus (title, url, sort_order, is_active) VALUES (?, ?, ?, ?)');
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'ssii', $title, $url, $sort, $active);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['admin_flash'] = 'Menu item added.';
            } else {
                $_SESSION['admin_flash'] = 'Could not save. Check database tables (run install).';
            }
        }
        header('Location: menus.php');
        exit;
    }

    if ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $title = trim((string) ($_POST['title'] ?? ''));
        $url = trim((string) ($_POST['url'] ?? ''));
        $sort = (int) ($_POST['sort_order'] ?? 0);
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($id < 1 || $title === '' || $url === '') {
            $_SESSION['admin_flash'] = 'Invalid data.';
        } else {
            $stmt = mysqli_prepare($conn, 'UPDATE nav_menus SET title = ?, url = ?, sort_order = ?, is_active = ? WHERE id = ?');
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'ssiii', $title, $url, $sort, $active, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['admin_flash'] = 'Menu item updated.';
            }
        }
        header('Location: menus.php');
        exit;
    }

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = mysqli_prepare($conn, 'DELETE FROM nav_menus WHERE id = ?');
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['admin_flash'] = 'Menu item removed.';
            }
        }
        header('Location: menus.php');
        exit;
    }
}

$editRow = null;
$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
if ($editId > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT id, title, url, sort_order, is_active FROM nav_menus WHERE id = ? LIMIT 1');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $editId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $editRow = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
    }
}

$rows = [];
$r = mysqli_query($conn, 'SELECT id, title, url, sort_order, is_active FROM nav_menus ORDER BY sort_order ASC, id ASC');
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        $rows[] = $row;
    }
    mysqli_free_result($r);
}

$pageTitle = 'Navigation';
$activeNav = 'menus';
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <h2>Navigation</h2>
</div>

<?php if ($flash !== ''): ?>
  <?php
    $flashErr = (strpos($flash, 'required') !== false)
      || (strpos($flash, 'Invalid') !== false)
      || (strpos($flash, 'Could not') !== false);
  ?>
  <div class="alert <?= $flashErr ? 'alert-error' : 'alert-success' ?>">
    <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>

<div class="admin-card" style="margin-bottom:1.25rem">
  <div class="admin-card-header"><?= $editRow ? 'Edit menu item' : 'Add menu item' ?></div>
  <div class="admin-card-body">
    <?php if ($editRow): ?>
      <form method="post" class="admin-form" action="menus.php">
        <?= admin_csrf_field() ?>
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= (int) $editRow['id'] ?>">
        <div class="form-group">
          <label for="title">Label</label>
          <input type="text" id="title" name="title" required maxlength="120" value="<?= htmlspecialchars((string) $editRow['title'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
          <label for="url">URL (relative path, e.g. contact.php)</label>
          <input type="text" id="url" name="url" required maxlength="255" value="<?= htmlspecialchars((string) $editRow['url'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
          <label for="sort_order">Sort order (lower = first)</label>
          <input type="number" id="sort_order" name="sort_order" value="<?= (int) $editRow['sort_order'] ?>">
        </div>
        <div class="form-check">
          <input type="checkbox" id="is_active" name="is_active" value="1" <?= (int) $editRow['is_active'] ? 'checked' : '' ?>>
          <label for="is_active" style="margin:0;color:var(--admin-text)">Visible on website</label>
        </div>
        <div style="margin-top:1rem;display:flex;gap:0.5rem;flex-wrap:wrap">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save changes</button>
          <a class="btn btn-ghost" href="menus.php">Cancel</a>
        </div>
      </form>
    <?php else: ?>
      <form method="post" class="admin-form" action="menus.php">
        <?= admin_csrf_field() ?>
        <input type="hidden" name="action" value="add">
        <div class="form-group">
          <label for="title">Label</label>
          <input type="text" id="title" name="title" required maxlength="120" placeholder="e.g. Contact Us">
        </div>
        <div class="form-group">
          <label for="url">URL</label>
          <input type="text" id="url" name="url" required maxlength="255" placeholder="e.g. contact.php">
        </div>
        <div class="form-group">
          <label for="sort_order">Sort order</label>
          <input type="number" id="sort_order" name="sort_order" value="0">
        </div>
        <div class="form-check">
          <input type="checkbox" id="is_active" name="is_active" value="1" checked>
          <label for="is_active" style="margin:0;color:var(--admin-text)">Visible on website</label>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top:0.75rem"><i class="fas fa-plus"></i> Add item</button>
      </form>
    <?php endif; ?>
  </div>
</div>

<div class="admin-card">
  <div class="admin-card-header">All menu items</div>
  <div class="admin-card-body" style="padding:0">
    <?php if ($rows === []): ?>
      <p style="padding:1.25rem;margin:0;color:var(--admin-muted)">No items yet. Run <a href="install.php" style="color:var(--admin-accent)">install</a> or add one above.</p>
    <?php else: ?>
      <div class="admin-table-wrap">
        <table class="admin-table">
          <thead>
            <tr>
              <th>Order</th>
              <th>Label</th>
              <th>URL</th>
              <th>Status</th>
              <th style="text-align:right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td><?= (int) $row['sort_order'] ?></td>
                <td><?= htmlspecialchars((string) $row['title'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><code style="color:var(--admin-muted);font-size:0.85em"><?= htmlspecialchars((string) $row['url'], ENT_QUOTES, 'UTF-8') ?></code></td>
                <td>
                  <?php if ((int) $row['is_active']): ?>
                    <span class="badge badge-on">Live</span>
                  <?php else: ?>
                    <span class="badge badge-off">Hidden</span>
                  <?php endif; ?>
                </td>
                <td style="text-align:right">
                  <div class="actions-inline" style="justify-content:flex-end">
                    <a class="btn btn-ghost btn-sm" href="menus.php?edit=<?= (int) $row['id'] ?>"><i class="fas fa-pen"></i> Edit</a>
                    <form method="post" action="menus.php" style="display:inline" onsubmit="return confirm('Delete this menu item?');">
                      <?= admin_csrf_field() ?>
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                      <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_footer.php'; ?>
