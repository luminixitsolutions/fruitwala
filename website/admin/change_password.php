<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/csrf.php';

admin_require_login();

$flash = '';
$error = '';
if (!empty($_SESSION['admin_flash'])) {
    $flash = (string) $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
}

$uid = (int) $_SESSION['admin_user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_csrf_verify()) {
    $current = (string) ($_POST['current_password'] ?? '');
    $new = (string) ($_POST['new_password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');

    if ($current === '' || $new === '' || $confirm === '') {
        $error = 'Please fill in all password fields.';
    } elseif ($new !== $confirm) {
        $error = 'New password and confirmation do not match.';
    } elseif (strlen($new) < 8) {
        $error = 'New password must be at least 8 characters.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT password_hash FROM admin_users WHERE id = ? LIMIT 1');
        if (!$stmt) {
            $error = 'Could not verify account. Try again later.';
        } else {
            mysqli_stmt_bind_param($stmt, 'i', $uid);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = $res ? mysqli_fetch_assoc($res) : null;
            mysqli_stmt_close($stmt);

            if (!$row || !password_verify($current, (string) $row['password_hash'])) {
                $error = 'Current password is incorrect.';
            } else {
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $up = mysqli_prepare($conn, 'UPDATE admin_users SET password_hash = ? WHERE id = ? LIMIT 1');
                if ($up) {
                    mysqli_stmt_bind_param($up, 'si', $hash, $uid);
                    if (mysqli_stmt_execute($up)) {
                        mysqli_stmt_close($up);
                        $_SESSION['admin_flash'] = 'Password updated.';
                        header('Location: change_password.php');
                        exit;
                    }
                    mysqli_stmt_close($up);
                }
                $error = 'Could not save password. Try again.';
            }
        }
    }
}

$pageTitle = 'Change password';
$activeNav = 'change_password';
$activeHomeSlug = '';
require __DIR__ . '/includes/layout_header.php';
?>

<div class="admin-topbar">
  <h2>Change password</h2>
</div>

<?php if ($flash !== ''): ?>
  <div class="alert alert-success"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>
<?php if ($error !== ''): ?>
  <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<form method="post" action="change_password.php" class="admin-form" autocomplete="off">
  <?= admin_csrf_field() ?>

  <div class="admin-card" style="margin-bottom:1.25rem;max-width:480px">
    <div class="admin-card-header">Update login password</div>
    <div class="admin-card-body">
      <div class="form-group">
        <label for="current_password">Current password</label>
        <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
      </div>
      <div class="form-group">
        <label for="new_password">New password</label>
        <input type="password" id="new_password" name="new_password" required minlength="8" autocomplete="new-password">
      </div>
      <div class="form-group">
        <label for="confirm_password">Confirm new password</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="8" autocomplete="new-password">
      </div>
    </div>
  </div>

  <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save password</button>
</form>

<?php require __DIR__ . '/includes/layout_footer.php'; ?>
