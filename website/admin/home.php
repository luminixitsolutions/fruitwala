<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/home_table.php';
admin_require_login();
fruitwala_admin_ensure_home_table($conn);

$pageTitle = 'Home page';
$activeNav = 'home';
$activeHomeSlug = '';
require_once __DIR__ . '/includes/home_edit_sections.php';
$sections = fruitwala_admin_home_edit_sections();
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <h2>Home page</h2>
  <a class="btn btn-primary btn-sm" href="../index.php" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> View homepage</a>
</div>

<p style="color:var(--admin-muted);max-width:720px;margin:0 0 1.5rem;font-size:0.9rem;">
  Text, links, and image paths for <strong>index.php</strong> are loaded from the database when present; otherwise the built-in defaults apply.
  Pick a section below to edit fields. After saving, refresh the website to see changes.
</p>

<div class="admin-grid">
  <?php foreach ($sections as $slug => $meta): ?>
    <a class="admin-home-card" href="home_edit.php?s=<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>">
      <div class="admin-home-card-icon"><i class="fas fa-pen-to-square"></i></div>
      <div class="admin-home-card-body">
        <strong><?= htmlspecialchars((string) $meta['title'], ENT_QUOTES, 'UTF-8') ?></strong>
        <span>Edit fields</span>
      </div>
      <i class="fas fa-chevron-right admin-home-card-arrow"></i>
    </a>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . '/includes/layout_footer.php'; ?>
