<?php
require_once __DIR__ . '/../backend/guard.php';
require_auth();
$conn = db_connect();
$user = current_user();

$stmt = $conn->prepare('SELECT id, date, time, people, status, created_at FROM reservations WHERE user_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $user['id']);
$stmt->execute();
$reservations = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Личный кабинет — NOIR</title>
  <link rel="stylesheet" href="../css/main.css" />
</head>
<body>
  <header class="header">
    <div class="container nav">
      <div class="nav__logo">NOIR</div>
      <nav class="nav__links" aria-label="Навигация">
        <a class="nav__link" href="../index.html">Главная</a>
        <a class="nav__link" href="menu.html">Меню</a>
        <a class="nav__link" href="account.php">Кабинет</a>
        <?php if (is_admin()): ?><a class="nav__link" href="/pages/admin/index.php">Админ</a><?php endif; ?>
      </nav>
    </div>
  </header>

  <main class="section">
    <div class="container">
      <h1 class="section__title">Здравствуйте, <?= h($user['name']) ?></h1>
      <p class="section__desc">Email: <?= h($user['email']) ?><?php if ($user['phone']): ?> · Телефон: <?= h($user['phone']) ?><?php endif; ?></p>

      <div class="section">
        <h2 class="section__title" style="font-size:24px;">Ваши бронирования</h2>
        <div class="grid">
          <?php if (!$reservations): ?>
            <div class="muted">Пока нет бронирований.</div>
          <?php else: foreach ($reservations as $r): ?>
            <div class="card">
              <div class="card__title">Дата: <?= h($r['date']) ?> · Время: <?= h(substr($r['time'],0,5)) ?> · Гостей: <?= h($r['people']) ?></div>
              <div class="card__text">Статус: <?= h($r['status']) ?> · Создано: <?= h($r['created_at']) ?></div>
            </div>
          <?php endforeach; endif; ?>
        </div>
      </div>

      <a class="btn btn--ghost" href="logout.php">Выйти</a>
    </div>
  </main>
</body>
</html>