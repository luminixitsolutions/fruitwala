<?php
declare(strict_types=1);

function admin_csrf_token(): string
{
    if (empty($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
    }
    return (string) $_SESSION['admin_csrf'];
}

function admin_csrf_field(): string
{
    $t = htmlspecialchars(admin_csrf_token(), ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="csrf_token" value="' . $t . '">';
}

function admin_csrf_verify(): bool
{
    $sent = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
    $stored = isset($_SESSION['admin_csrf']) ? (string) $_SESSION['admin_csrf'] : '';
    return $sent !== '' && $stored !== '' && hash_equals($stored, $sent);
}
