<?php
require_once __DIR__ . '/auth.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$date = trim($_POST['date'] ?? '');
$time = trim($_POST['time'] ?? '');
$people = (int)($_POST['people'] ?? 0);
$message = trim($_POST['message'] ?? '');

if ($name === '' || $phone === '' || $date === '' || $time === '' || $people < 1) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Заполните обязательные поля']);
    exit;
}

$conn = db_connect();
$user = current_user();
$userId = $user ? $user['id'] : null;
$stmt = $conn->prepare('INSERT INTO reservations (user_id, name, phone, email, date, time, people, message) VALUES (?,?,?,?,?,?,?,?)');
$stmt->bind_param('isssssis', $userId, $name, $phone, $email, $date, $time, $people, $message);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Ошибка сохранения']);
    exit;
}

echo json_encode(['ok' => true, 'message' => 'Заявка принята', 'id' => $stmt->insert_id]);