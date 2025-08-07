<?php
require_once __DIR__ . '/auth.php';

function require_auth() {
    if (!current_user()) {
        header('Location: /pages/login.php');
        exit;
    }
}

function require_admin() {
    if (!is_admin()) {
        header('Location: /pages/account.php');
        exit;
    }
}