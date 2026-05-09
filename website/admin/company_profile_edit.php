<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once dirname(__DIR__) . '/includes/company_profile.php';

admin_require_login();

$flash = '';
if (!empty($_SESSION['admin_flash'])) {
    $flash = (string) $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_verify()) {
    fruitwala_company_profile_ensure_table($conn);
    fruitwala_company_profile_ensure_default_row($conn);

    $companyName = trim((string) ($_POST['company_name'] ?? ''));
    $address = str_replace("\r\n", "\n", trim((string) ($_POST['address'] ?? '')));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $facebookUrl = trim((string) ($_POST['facebook_url'] ?? ''));
    $instagramUrl = trim((string) ($_POST['instagram_url'] ?? ''));
    $twitterUrl = trim((string) ($_POST['twitter_url'] ?? ''));
    $linkedinUrl = trim((string) ($_POST['linkedin_url'] ?? ''));
    $youtubeUrl = trim((string) ($_POST['youtube_url'] ?? ''));
    $whatsappUrl = trim((string) ($_POST['whatsapp_url'] ?? ''));

    if ($companyName === '') {
        $_SESSION['admin_flash'] = 'Company name is required.';
    } else {
        $stmt = mysqli_prepare(
            $conn,
            'UPDATE company_profile SET company_name = ?, address = ?, phone = ?, email = ?,
             facebook_url = ?, instagram_url = ?, twitter_url = ?, linkedin_url = ?, youtube_url = ?, whatsapp_url = ?
             WHERE id = 1 LIMIT 1'
        );
        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                'ssssssssss',
                $companyName,
                $address,
                $phone,
                $email,
                $facebookUrl,
                $instagramUrl,
                $twitterUrl,
                $linkedinUrl,
                $youtubeUrl,
                $whatsappUrl
            );
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $_SESSION['admin_flash'] = 'Company profile saved.';
        } else {
            $_SESSION['admin_flash'] = 'Could not save. If this is a new install, run admin install or import sql/schema.sql.';
        }
    }
    header('Location: company_profile_edit.php');
    exit;
}

$company = fruitwala_company_profile_load($conn);

$pageTitle = 'Company profile';
$activeNav = 'company';
$activeHomeSlug = '';
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <h2>Company profile</h2>
  <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
    <a class="btn btn-primary btn-sm" href="../index.php" target="_blank" rel="noopener"><i class="fas fa-home"></i> View site</a>
  </div>
</div>

<?php if ($flash !== ''): ?>
  <div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<p style="margin:0 0 1rem;color:var(--admin-muted);font-size:0.9rem;max-width:720px">
  Name, address, phone, and email appear in the site footer (and can be reused elsewhere). Social URLs should include <code>https://</code>.
</p>

<form method="post" action="company_profile_edit.php" class="admin-form">
  <?= admin_csrf_field() ?>

  <div class="admin-card" style="margin-bottom:1.25rem">
    <div class="admin-card-header">Business details</div>
    <div class="admin-card-body">
      <div class="form-group">
        <label for="company_name">Company name</label>
        <input type="text" id="company_name" name="company_name" class="admin-input--wide" required maxlength="255" value="<?= htmlspecialchars($company['company_name'], ENT_QUOTES, 'UTF-8') ?>">
      </div>
      <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address" class="admin-input--wide" rows="3"><?= htmlspecialchars($company['address'], ENT_QUOTES, 'UTF-8') ?></textarea>
      </div>
      <div class="form-group">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($company['phone'], ENT_QUOTES, 'UTF-8') ?>">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="admin-input--wide" value="<?= htmlspecialchars($company['email'], ENT_QUOTES, 'UTF-8') ?>" placeholder="name@example.com" autocomplete="email">
      </div>
    </div>
  </div>

  <div class="admin-card" style="margin-bottom:1.25rem">
    <div class="admin-card-header">Social &amp; messaging links</div>
    <div class="admin-card-body">
      <div class="form-group">
        <label for="facebook_url">Facebook URL</label>
        <input type="url" id="facebook_url" name="facebook_url" class="admin-input--wide" value="<?= htmlspecialchars($company['facebook_url'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://www.facebook.com/..." inputmode="url" autocomplete="url">
      </div>
      <div class="form-group">
        <label for="instagram_url">Instagram URL</label>
        <input type="url" id="instagram_url" name="instagram_url" class="admin-input--wide" value="<?= htmlspecialchars($company['instagram_url'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://www.instagram.com/..." inputmode="url" autocomplete="url">
      </div>
      <div class="form-group">
        <label for="twitter_url">Twitter / X URL</label>
        <input type="url" id="twitter_url" name="twitter_url" class="admin-input--wide" value="<?= htmlspecialchars($company['twitter_url'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://twitter.com/..." inputmode="url" autocomplete="url">
      </div>
      <div class="form-group">
        <label for="linkedin_url">LinkedIn URL</label>
        <input type="url" id="linkedin_url" name="linkedin_url" class="admin-input--wide" value="<?= htmlspecialchars($company['linkedin_url'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://www.linkedin.com/..." inputmode="url" autocomplete="url">
      </div>
      <div class="form-group">
        <label for="youtube_url">YouTube URL</label>
        <input type="url" id="youtube_url" name="youtube_url" class="admin-input--wide" value="<?= htmlspecialchars($company['youtube_url'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://www.youtube.com/..." inputmode="url" autocomplete="url">
      </div>
      <div class="form-group">
        <label for="whatsapp_url">WhatsApp link</label>
        <input type="url" id="whatsapp_url" name="whatsapp_url" class="admin-input--wide" value="<?= htmlspecialchars($company['whatsapp_url'], ENT_QUOTES, 'UTF-8') ?>" placeholder="https://wa.me/..." inputmode="url" autocomplete="url">
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
</form>

<?php require __DIR__ . '/includes/layout_footer.php'; ?>
