<?php
declare(strict_types=1);

function admin_require_login(): void
{
    if (empty($_SESSION['admin_user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function admin_current_username(): string
{
    return isset($_SESSION['admin_username']) ? (string) $_SESSION['admin_username'] : '';
}
