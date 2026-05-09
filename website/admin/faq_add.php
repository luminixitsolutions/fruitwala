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
        $question = trim((string) ($_POST['question'] ?? ''));
        $answer = trim((string) ($_POST['answer'] ?? ''));
        $sort = (int) ($_POST['sort_order'] ?? 0);
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($question === '' || $answer === '') {
            $_SESSION['admin_flash'] = 'Question and answer are required.';
            header('Location: faq_add.php');
            exit;
        }
        $stmt = mysqli_prepare($conn, 'INSERT INTO faqs (question, answer, sort_order, is_active) VALUES (?, ?, ?, ?)');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssii', $question, $answer, $sort, $active);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['admin_flash'] = 'FAQ added.';
        } else {
            $_SESSION['admin_flash'] = 'Could not save. Create the `faqs` table (see admin/sql/schema.sql).';
            header('Location: faq_add.php');
            exit;
        }
        header('Location: faq_view.php');
        exit;
    }

    if ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $question = trim((string) ($_POST['question'] ?? ''));
        $answer = trim((string) ($_POST['answer'] ?? ''));
        $sort = (int) ($_POST['sort_order'] ?? 0);
        $active = isset($_POST['is_active']) ? 1 : 0;
        if ($id < 1 || $question === '' || $answer === '') {
            $_SESSION['admin_flash'] = 'Invalid data.';
            header('Location: ' . ($id > 0 ? 'faq_add.php?edit=' . $id : 'faq_add.php'));
            exit;
        }
        $stmt = mysqli_prepare($conn, 'UPDATE faqs SET question = ?, answer = ?, sort_order = ?, is_active = ? WHERE id = ?');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssiii', $question, $answer, $sort, $active, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['admin_flash'] = 'FAQ updated.';
        }
        header('Location: faq_view.php');
        exit;
    }
}

$editRow = null;
$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
if ($editId > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT id, question, answer, sort_order, is_active FROM faqs WHERE id = ? LIMIT 1');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $editId);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $editRow = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);
    }
    if (!$editRow) {
        $_SESSION['admin_flash'] = 'FAQ not found.';
        header('Location: faq_view.php');
        exit;
    }
}

$pageTitle = $editRow ? 'FAQ — Edit' : 'FAQ — Add';
$activeNav = 'faq';
$faqAdminSub = 'add';
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <h2><?= $editRow ? 'Edit FAQ' : 'Add FAQ' ?></h2>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
    <a class="btn btn-ghost btn-sm" href="faq_view.php"><i class="fas fa-list"></i> View all</a>
    <a class="btn btn-ghost btn-sm" href="../faq.php" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> View on site</a>
  </div>
</div>

<?php if ($flash !== ''): ?>
  <?php
    $flashErr = (strpos($flash, 'required') !== false)
      || (strpos($flash, 'Invalid') !== false)
      || (strpos($flash, 'Could not') !== false)
      || (strpos($flash, 'not found') !== false);
  ?>
  <div class="alert <?= $flashErr ? 'alert-error' : 'alert-success' ?>">
    <?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?>
  </div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-header"><?= $editRow ? 'Edit entry' : 'New entry' ?></div>
  <div class="admin-card-body">
    <?php if ($editRow): ?>
      <form method="post" class="admin-form" action="faq_add.php">
        <?= admin_csrf_field() ?>
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= (int) $editRow['id'] ?>">
        <div class="form-group">
          <label for="question">Question</label>
          <input type="text" id="question" name="question" required maxlength="500" value="<?= htmlspecialchars((string) $editRow['question'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="form-group">
          <label for="answer">Answer</label>
          <textarea id="answer" name="answer" required rows="6" style="width:100%;min-height:120px"><?= htmlspecialchars((string) $editRow['answer'], ENT_QUOTES, 'UTF-8') ?></textarea>
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
          <a class="btn btn-ghost" href="faq_view.php">Cancel</a>
        </div>
      </form>
    <?php else: ?>
      <form method="post" class="admin-form" action="faq_add.php">
        <?= admin_csrf_field() ?>
        <input type="hidden" name="action" value="add">
        <div class="form-group">
          <label for="question">Question</label>
          <input type="text" id="question" name="question" required maxlength="500" placeholder="e.g. Where do your fruits come from?">
        </div>
        <div class="form-group">
          <label for="answer">Answer</label>
          <textarea id="answer" name="answer" required rows="6" style="width:100%;min-height:120px" placeholder="Full answer shown in the accordion on the FAQ page."></textarea>
        </div>
        <div class="form-group">
          <label for="sort_order">Sort order</label>
          <input type="number" id="sort_order" name="sort_order" value="0">
        </div>
        <div class="form-check">
          <input type="checkbox" id="is_active" name="is_active" value="1" checked>
          <label for="is_active" style="margin:0;color:var(--admin-text)">Visible on website</label>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top:0.75rem"><i class="fas fa-plus"></i> Add FAQ</button>
      </form>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/includes/layout_footer.php'; ?>
