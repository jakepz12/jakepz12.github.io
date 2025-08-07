<?php
require_once __DIR__ . '/auth.php';
header('Content-Type: application/json; charset=utf-8');

$user = current_user();
if (!$user) {
    echo json_encode(['authenticated' => false]);
    exit;
}

echo json_encode(['authenticated' => true, 'user' => ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email'], 'role' => $user['role']]]);