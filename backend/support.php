<?php
require_once __DIR__ . '/auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['ok'=>false]); exit; }

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
if ($name === '' || $email === '' || $message === '') { http_response_code(400); echo json_encode(['ok'=>false, 'message'=>'Заполните обязательные поля']); exit; }

$conn = db_connect();
$user = current_user();
$userId = $user ? $user['id'] : null;
$stmt = $conn->prepare('INSERT INTO support_requests (user_id, name, email, subject, message) VALUES (?,?,?,?,?)');
$stmt->bind_param('issss', $userId, $name, $email, $subject, $message);
if (!$stmt->execute()) { http_response_code(500); echo json_encode(['ok'=>false, 'message'=>'Ошибка']); exit; }

echo json_encode(['ok'=>true]);