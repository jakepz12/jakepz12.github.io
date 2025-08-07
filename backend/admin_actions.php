<?php
require_once __DIR__ . '/guard.php';
require_admin();
header('Content-Type: application/json; charset=utf-8');
$conn = db_connect();
$type = $_POST['type'] ?? '';

switch ($type) {
    case 'force_logout': {
        $userId = (int)($_POST['user_id'] ?? 0);
        if ($userId > 0) {
            $stmt = $conn->prepare('UPDATE users SET force_logout = 1 WHERE id = ?');
            $stmt->bind_param('i', $userId);
            $ok = $stmt->execute();
            echo json_encode(['ok' => $ok]);
        } else { http_response_code(400); echo json_encode(['ok'=>false]); }
        break;
    }
    case 'reservation_status': {
        $resId = (int)($_POST['reservation_id'] ?? 0);
        $status = $_POST['status'] ?? '';
        if (!in_array($status, ['confirmed','cancelled','new'], true)) { http_response_code(400); echo json_encode(['ok'=>false]); break; }
        $stmt = $conn->prepare('UPDATE reservations SET status = ? WHERE id = ?');
        $stmt->bind_param('si', $status, $resId);
        echo json_encode(['ok' => $stmt->execute()]);
        break;
    }
    case 'menu_category_save': {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $position = (int)($_POST['position'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE menu_categories SET title=?, position=? WHERE id=?');
            $stmt->bind_param('sii', $title, $position, $id);
            echo json_encode(['ok' => $stmt->execute()]);
        } else {
            $stmt = $conn->prepare('INSERT INTO menu_categories (title, position) VALUES (?,?)');
            $stmt->bind_param('si', $title, $position);
            echo json_encode(['ok' => $stmt->execute(), 'id' => $conn->insert_id]);
        }
        break;
    }
    case 'menu_category_delete': {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $conn->query('DELETE FROM menu_items WHERE category_id = '.(int)$id);
            $conn->query('DELETE FROM menu_categories WHERE id = '.(int)$id);
            echo json_encode(['ok' => true]);
        } else { http_response_code(400); echo json_encode(['ok'=>false]); }
        break;
    }
    case 'menu_item_save': {
        $id = (int)($_POST['id'] ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $position = (int)($_POST['position'] ?? 0);
        if ($id > 0) {
            $stmt = $conn->prepare('UPDATE menu_items SET category_id=?, name=?, description=?, price=?, position=? WHERE id=?');
            $stmt->bind_param('issdii', $categoryId, $name, $desc, $price, $position, $id);
            echo json_encode(['ok' => $stmt->execute()]);
        } else {
            $stmt = $conn->prepare('INSERT INTO menu_items (category_id, name, description, price, position) VALUES (?,?,?,?,?)');
            $stmt->bind_param('issdi', $categoryId, $name, $desc, $price, $position);
            echo json_encode(['ok' => $stmt->execute(), 'id' => $conn->insert_id]);
        }
        break;
    }
    case 'menu_item_delete': {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            $conn->query('DELETE FROM menu_items WHERE id = '.(int)$id);
            echo json_encode(['ok' => true]);
        } else { http_response_code(400); echo json_encode(['ok'=>false]); }
        break;
    }
    default:
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'unknown action']);
}