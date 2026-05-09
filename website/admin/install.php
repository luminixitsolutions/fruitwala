<?php
declare(strict_types=1);

/**
 * One-time setup: creates tables, seeds navigation, creates admin user.
 * Delete or protect this file in production after running once.
 */

require_once __DIR__ . '/bootstrap.php';

$lockFile = __DIR__ . '/install.lock';
$installed = is_file($lockFile);
$messages = [];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$installed) {
    $adminUser = trim((string) ($_POST['admin_username'] ?? 'admin'));
    $adminPass = (string) ($_POST['admin_password'] ?? '');
    if ($adminUser === '' || strlen($adminPass) < 8) {
        $error = 'Username is required and password must be at least 8 characters.';
    } else {
        $schema = file_get_contents(__DIR__ . '/sql/schema.sql');
        if ($schema === false) {
            $error = 'Could not read sql/schema.sql';
        } else {
            mysqli_begin_transaction($conn);
            try {
                if (!mysqli_multi_query($conn, $schema)) {
                    throw new RuntimeException(mysqli_error($conn));
                }
                while (mysqli_more_results($conn) && mysqli_next_result($conn)) {
                    if ($res = mysqli_store_result($conn)) {
                        mysqli_free_result($res);
                    }
                }

                $check = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM nav_menus');
                $count = 0;
                if ($check && $r = mysqli_fetch_assoc($check)) {
                    $count = (int) $r['c'];
                    mysqli_free_result($check);
                }
                if ($count === 0) {
                    $seed = [
                        ['Home', 'index.php', 10],
                        ['About Us', 'about-us.php', 20],
                        ['Why Choose Us', 'why-choose-us.php', 25],
                        ['Packages', 'packages.php', 30],
                        ['Diet Consultation', 'diet_consultation.php', 40],
                        ['Blogs', 'blogs.php', 50],
                        ['FAQ', 'faq.php', 60],
                        ['Portfolio', 'portfolio.php', 70],
                        ['Contact Us', 'contact.php', 80],
                    ];
                    $ins = mysqli_prepare($conn, 'INSERT INTO nav_menus (title, url, sort_order, is_active) VALUES (?, ?, ?, 1)');
                    if (!$ins) {
                        throw new RuntimeException(mysqli_error($conn));
                    }
                    foreach ($seed as $s) {
                        [$t, $u, $o] = $s;
                        mysqli_stmt_bind_param($ins, 'ssi', $t, $u, $o);
                        mysqli_stmt_execute($ins);
                    }
                    mysqli_stmt_close($ins);
                    $messages[] = 'Seeded default navigation (8 items).';
                } else {
                    $messages[] = 'Navigation table already had data; skipped seed.';
                }

                $adminCount = 0;
                $ac = mysqli_query($conn, 'SELECT COUNT(*) AS c FROM admin_users');
                if ($ac && $ar = mysqli_fetch_assoc($ac)) {
                    $adminCount = (int) $ar['c'];
                    mysqli_free_result($ac);
                }
                if ($adminCount === 0) {
                    $hash = password_hash($adminPass, PASSWORD_DEFAULT);
                    $stmt = mysqli_prepare($conn, 'INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
                    if (!$stmt) {
                        throw new RuntimeException(mysqli_error($conn));
                    }
                    mysqli_stmt_bind_param($stmt, 'ss', $adminUser, $hash);
                    if (!mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_close($stmt);
                        throw new RuntimeException(mysqli_error($conn));
                    }
                    mysqli_stmt_close($stmt);
                    $messages[] = 'Admin user created.';
                } else {
                    $messages[] = 'Admin user already exists; skipped creating a new one.';
                }

                file_put_contents($lockFile, date('c'));
                mysqli_commit($conn);
                $messages[] = 'Install lock written. You can open the admin panel.';
                $installed = true;
            } catch (Throwable $e) {
                mysqli_rollback($conn);
                $error = $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Install — Fruitwala Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-body">
<div class="admin-login-wrap">
  <div class="admin-login-card" style="max-width:480px">
    <h1><i class="fas fa-database" style="color:var(--admin-accent)"></i> Database setup</h1>
    <p>Creates <code>nav_menus</code>, <code>admin_users</code>, and <code>home_page_fields</code> in your configured database, seeds the header menu, and adds your first admin account. Homepage copy is managed from the admin <strong>Home page</strong> menu after login.</p>

    <?php if ($installed && is_file($lockFile) && $messages === []): ?>
      <div class="alert alert-success">Already installed. <a href="login.php" style="color:inherit;font-weight:700">Go to login</a>.</div>
    <?php endif; ?>

    <?php foreach ($messages as $m): ?>
      <div class="alert alert-success"><?= htmlspecialchars($m, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endforeach; ?>

    <?php if ($error !== ''): ?>
      <div class="alert alert-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if (!$installed): ?>
      <form method="post" class="admin-form" action="install.php">
        <div class="form-group">
          <label for="admin_username">Admin username</label>
          <input type="text" id="admin_username" name="admin_username" required maxlength="64" value="admin">
        </div>
        <div class="form-group">
          <label for="admin_password">Admin password (min 8 characters)</label>
          <input type="password" id="admin_password" name="admin_password" required minlength="8" autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;margin-top:0.5rem"><i class="fas fa-bolt"></i> Run install</button>
      </form>
    <?php else: ?>
      <a class="btn btn-primary" style="width:100%;margin-top:0.5rem;text-align:center" href="login.php">Open admin login</a>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
