<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/home_edit_list_section.php';
admin_require_login();

$flash = '';
if (!empty($_SESSION['admin_flash'])) {
    $flash = (string) $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_verify()) {
    $action = (string) ($_POST['action'] ?? '');
    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = mysqli_prepare($conn, 'DELETE FROM faqs WHERE id = ?');
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $_SESSION['admin_flash'] = 'FAQ removed.';
            }
        }
        header('Location: faq_view.php');
        exit;
    }
}

$rows = [];
$r = mysqli_query($conn, 'SELECT id, question, answer, sort_order, is_active FROM faqs ORDER BY sort_order ASC, id ASC');
if ($r) {
    while ($row = mysqli_fetch_assoc($r)) {
        $rows[] = $row;
    }
    mysqli_free_result($r);
}

$pageTitle = 'FAQ — View';
$activeNav = 'faq';
$faqAdminSub = 'view';
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <h2>All FAQs</h2>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
    <a class="btn btn-primary btn-sm" href="faq_add.php"><i class="fas fa-plus"></i> Add FAQ</a>
    <a class="btn btn-ghost btn-sm" href="../faq.php" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> View on site</a>
  </div>
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

<div class="admin-card hero-dt-card">
  <div class="admin-card-header">FAQ list</div>
  <div class="admin-card-body hero-dt-shell" style="overflow-x:auto">
    <p style="margin:0 0 1rem;color:var(--admin-muted);font-size:0.9rem">Search, sort columns, and paginate. Edit opens the Add screen with this entry loaded.</p>
    <table id="faqsTable" class="hero-dt-table" style="width:100%">
      <thead>
        <tr>
          <th>Order</th>
          <th>Question</th>
          <th>Answer (preview)</th>
          <th>Status</th>
          <th class="hero-dt-col-actions">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
          <?php
            $ans = (string) $row['answer'];
            $preview = strlen($ans) > 80 ? substr($ans, 0, 80) . '…' : $ans;
          ?>
          <tr>
            <td><?= (int) $row['sort_order'] ?></td>
            <td><?= htmlspecialchars((string) $row['question'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="max-width:280px;color:var(--admin-muted);font-size:0.9rem"><?= htmlspecialchars($preview, ENT_QUOTES, 'UTF-8') ?></td>
            <td>
              <?php if ((int) $row['is_active']): ?>
                <span class="badge badge-on">Live</span>
              <?php else: ?>
                <span class="badge badge-off">Hidden</span>
              <?php endif; ?>
            </td>
            <td class="hero-dt-col-actions">
              <div class="hero-dt-actions-inner">
                <a class="hero-dt-icon-btn hero-dt-icon-btn--edit" href="faq_add.php?edit=<?= (int) $row['id'] ?>" title="Edit"><i class="fas fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span></a>
                <form class="hero-dt-icon-form" method="post" action="faq_view.php" onsubmit="return confirm('Delete this FAQ?');">
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
<style>
  .visually-hidden { position: absolute !important; width: 1px !important; height: 1px !important; padding: 0 !important; margin: -1px !important; overflow: hidden !important; clip: rect(0,0,0,0) !important; white-space: nowrap !important; border: 0 !important; }
  .admin-card.hero-dt-card { box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06), 0 8px 32px rgba(15, 23, 42, 0.06); }
</style>
<?php
fruitwala_admin_home_list_datatable_assets('faqsTable', 4, 'No FAQs in the database yet. Use “Add FAQ” to create one. The public page may still show default content until rows exist.');
?>

<?php require __DIR__ . '/includes/layout_footer.php'; ?>
