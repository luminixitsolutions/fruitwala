<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/home_table.php';
require_once dirname(__DIR__) . '/includes/about_us_content.php';

admin_require_login();
fruitwala_admin_ensure_home_table($conn);

$flash = '';
if (!empty($_SESSION['admin_flash'])) {
    $flash = (string) $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_verify()) {
    fruitwala_about_us_ensure_table($conn);
    fruitwala_about_us_ensure_default_row($conn);

    $breadcrumbTitle = trim((string) ($_POST['breadcrumb_title'] ?? ''));
    $badgeText = trim((string) ($_POST['badge_text'] ?? ''));
    $headingHtml = str_replace("\r\n", "\n", trim((string) ($_POST['heading_html'] ?? '')));
    $subtitle = str_replace("\r\n", "\n", trim((string) ($_POST['subtitle'] ?? '')));
    $bodyText = str_replace("\r\n", "\n", trim((string) ($_POST['body_text'] ?? '')));
    $heroImage = trim((string) ($_POST['hero_image'] ?? ''));
    $heroImageAlt = trim((string) ($_POST['hero_image_alt'] ?? ''));
    $btnText = trim((string) ($_POST['btn_text'] ?? ''));
    $btnUrl = trim((string) ($_POST['btn_url'] ?? ''));

    $stmt = mysqli_prepare(
        $conn,
        'UPDATE about_us_content SET breadcrumb_title = ?, badge_text = ?, heading_html = ?, subtitle = ?, body_text = ?,
         hero_image = ?, hero_image_alt = ?, btn_text = ?, btn_url = ? WHERE id = 1 LIMIT 1'
    );
    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            'sssssssss',
            $breadcrumbTitle,
            $badgeText,
            $headingHtml,
            $subtitle,
            $bodyText,
            $heroImage,
            $heroImageAlt,
            $btnText,
            $btnUrl
        );
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['admin_flash'] = 'About Us content saved.';
    } else {
        $_SESSION['admin_flash'] = 'Could not save. Please try again.';
    }
    header('Location: about_us_edit.php');
    exit;
}

$about = fruitwala_about_us_load($conn);

$pageTitle = 'About Us';
$activeNav = 'about';
$activeHomeSlug = '';
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <h2>About Us</h2>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
    <a class="btn btn-ghost btn-sm" href="../about-us.php" target="_blank" rel="noopener"><i class="fas fa-external-link-alt"></i> View page</a>
    <a class="btn btn-primary btn-sm" href="../index.php" target="_blank" rel="noopener"><i class="fas fa-home"></i> View site</a>
  </div>
</div>

<?php if ($flash !== ''): ?>
  <div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<p style="margin:0 0 1rem;color:var(--admin-muted);font-size:0.9rem;max-width:720px">
  Edits the main story block on <code>about-us.php</code> (breadcrumb title, image, headings, text, and call-to-action). Other sections on that page are unchanged.
</p>

<form method="post" action="about_us_edit.php" class="admin-form">
  <?= admin_csrf_field() ?>

  <div class="admin-card" style="margin-bottom:1.25rem">
    <div class="admin-card-header">Page &amp; hero image</div>
    <div class="admin-card-body">
      <div class="form-group">
        <label for="breadcrumb_title">Breadcrumb title</label>
        <input type="text" id="breadcrumb_title" name="breadcrumb_title" value="<?= htmlspecialchars($about['breadcrumb_title'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:480px">
      </div>
      <div class="form-group">
        <label for="hero_image">Hero image path</label>
        <input type="text" id="hero_image" name="hero_image" value="<?= htmlspecialchars($about['hero_image'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
      </div>
      <div class="form-group">
        <label for="hero_image_alt">Hero image alt text</label>
        <input type="text" id="hero_image_alt" name="hero_image_alt" value="<?= htmlspecialchars($about['hero_image_alt'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
      </div>
    </div>
  </div>

  <div class="admin-card" style="margin-bottom:1.25rem">
    <div class="admin-card-header">Story copy</div>
    <div class="admin-card-body">
      <div class="form-group">
        <label for="badge_text">Small badge line</label>
        <input type="text" id="badge_text" name="badge_text" value="<?= htmlspecialchars($about['badge_text'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:640px">
      </div>
      <div class="form-group">
        <label for="heading_html">Main heading (HTML: &lt;font&gt;, &lt;span&gt; for letters)</label>
        <textarea id="heading_html" name="heading_html" rows="3" style="width:100%;max-width:720px;padding:0.65rem 0.85rem;border-radius:10px;border:1px solid var(--admin-border);background:var(--admin-surface-2);color:var(--admin-text);font-family:inherit;font-size:0.9rem"><?= htmlspecialchars($about['heading_html'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-group">
        <label for="subtitle">Lead paragraph</label>
        <textarea id="subtitle" name="subtitle" rows="3" style="width:100%;max-width:720px;padding:0.65rem 0.85rem;border-radius:10px;border:1px solid var(--admin-border);background:var(--admin-surface-2);color:var(--admin-text);font-family:inherit;font-size:0.9rem"><?= htmlspecialchars($about['subtitle'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-group">
        <label for="body_text">Supporting paragraph</label>
        <textarea id="body_text" name="body_text" rows="4" style="width:100%;max-width:720px;padding:0.65rem 0.85rem;border-radius:10px;border:1px solid var(--admin-border);background:var(--admin-surface-2);color:var(--admin-text);font-family:inherit;font-size:0.9rem"><?= htmlspecialchars($about['body_text'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
    </div>
  </div>

  <div class="admin-card" style="margin-bottom:1.25rem">
    <div class="admin-card-header">Button</div>
    <div class="admin-card-body">
      <div class="form-group">
        <label for="btn_text">Button label</label>
        <input type="text" id="btn_text" name="btn_text" value="<?= htmlspecialchars($about['btn_text'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:320px">
      </div>
      <div class="form-group">
        <label for="btn_url">Button link</label>
        <input type="text" id="btn_url" name="btn_url" value="<?= htmlspecialchars($about['btn_url'], ENT_QUOTES, 'UTF-8') ?>" style="max-width:480px">
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
</form>

<?php require __DIR__ . '/includes/layout_footer.php'; ?>
