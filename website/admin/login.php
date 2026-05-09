<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

if (!empty($_SESSION['admin_user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim((string) ($_POST['username'] ?? ''));
    $pass = (string) ($_POST['password'] ?? '');

    if ($user === '' || $pass === '') {
        $error = 'Please enter username and password.';
    } else {
        $stmt = mysqli_prepare($conn, 'SELECT id, username, password_hash FROM admin_users WHERE username = ? LIMIT 1');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $user);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $row = $res ? mysqli_fetch_assoc($res) : null;
            mysqli_stmt_close($stmt);

            if ($row && password_verify($pass, (string) $row['password_hash'])) {
                $_SESSION['admin_user_id'] = (int) $row['id'];
                $_SESSION['admin_username'] = (string) $row['username'];
                header('Location: index.php');
                exit;
            }
        }
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in — Fruitwala Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-body admin-body--login">
<div class="admin-login-page" aria-hidden="true">
  <div class="admin-login-page__orb admin-login-page__orb--1"></div>
  <div class="admin-login-page__orb admin-login-page__orb--2"></div>
  <div class="admin-login-page__mesh"></div>
</div>
<div class="admin-login-wrap">
  <div class="admin-login-card">
    <div class="admin-login-card__accent" aria-hidden="true"></div>
    <header class="admin-login-card__head">
      <div class="admin-login-card__brand">
        <a href="../index.php" class="admin-login-logo" title="Fruitwala (opens website)">
          <img src="../logo.png" alt="Fruitwala" width="200" height="56" decoding="async" class="admin-login-logo__img" onerror="this.onerror=null;this.src='assets/fruitwala-logo.svg'">
        </a>
        <p class="admin-login-card__badge"><i class="fas fa-shield-halved" aria-hidden="true"></i> Admin</p>
      </div>
      <h1 class="admin-login-card__title">Welcome back</h1>
      <p class="admin-login-card__lead">Sign in to manage navigation, pages, and content.</p>
    </header>
    <?php if ($error !== ''): ?>
      <div class="alert alert-error admin-login-alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <form method="post" action="login.php" class="admin-form admin-login-form" autocomplete="on">
      <div class="form-group">
        <label for="username">Username</label>
        <div class="admin-login-input">
          <i class="fas fa-user" aria-hidden="true"></i>
          <input type="text" id="username" name="username" required maxlength="64" placeholder="Your username" value="<?= htmlspecialchars((string) ($_POST['username'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <div class="admin-login-input">
          <i class="fas fa-key" aria-hidden="true"></i>
          <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
        </div>
      </div>
      <button type="submit" class="btn btn-primary admin-login-submit"><i class="fas fa-right-to-bracket" aria-hidden="true"></i> Sign in</button>
    </form>
    <p class="admin-login-card__foot"><a href="../index.php">← Back to website</a></p>
  </div>
</div>
</body>
</html>
