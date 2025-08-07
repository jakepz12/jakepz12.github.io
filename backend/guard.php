<?php
require_once __DIR__ . '/auth.php';

function require_auth() {
    check_forced_logout();
    if (!current_user()) {
        header('Location: /pages/login.php');
        exit;
    }
}

function require_admin() {
    check_forced_logout();
    if (!is_admin()) {
        header('Location: /pages/account.php');
        exit;
    }
}