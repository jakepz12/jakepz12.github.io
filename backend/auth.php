<?php
session_start();
require_once __DIR__ . '/config.php';

function current_user() {
    return isset($_SESSION['user']) ? $_SESSION['user'] : null;
}
function is_admin(): bool { return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'; }

function login($email, $password): bool {
    $conn = db_connect();
    $stmt = $conn->prepare('SELECT id, email, password_hash, name, phone, role FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();
    if (!$user) return false;
    // Passwords hashed with SHA2 in seed for simplicity; accept SHA2
    $sha2 = hash('sha256', $password);
    if (!hash_equals($user['password_hash'], $sha2)) return false;
    $_SESSION['user'] = [
        'id' => (int)$user['id'],
        'email' => $user['email'],
        'name' => $user['name'],
        'phone' => $user['phone'],
        'role' => $user['role'],
    ];
    return true;
}

function register_user($name, $email, $phone, $password): array {
    $conn = db_connect();
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    if ($stmt->get_result()->fetch_assoc()) {
        return [false, 'Email уже зарегистрирован'];
    }
    $hash = hash('sha256', $password);
    $stmt = $conn->prepare('INSERT INTO users (email, password_hash, name, phone) VALUES (?,?,?,?)');
    $stmt->bind_param('ssss', $email, $hash, $name, $phone);
    if (!$stmt->execute()) {
        return [false, 'Ошибка регистрации'];
    }
    return [true, 'Регистрация успешна'];
}

function logout() { session_destroy(); }