<?php
require_once __DIR__ . '/../../backend/guard.php';
require_admin();
$conn = db_connect();
$users = $conn->query('SELECT id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC LIMIT 100')->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Пользователи — NOIR</title>
  <link rel="stylesheet" href="../../css/main.css" />
</head>
<body>
  <header class="header">
    <div class="container nav">
      <div class="nav__logo">NOIR</div>
      <nav class="nav__links" aria-label="Навигация">
        <a class="nav__link" href="../../index.html">Главная</a>
        <a class="nav__link" href="index.php">Админ</a>
      </nav>
    </div>
  </header>

  <main class="section">
    <div class="container">
      <h1 class="section__title">Пользователи</h1>
      <div class="grid">
        <?php foreach ($users as $u): ?>
          <div class="card">
            <div class="card__title">#<?= h($u['id']) ?> · <?= h($u['name']) ?> (<?= h($u['role']) ?>)</div>
            <div class="card__text">Email: <?= h($u['email']) ?><?php if ($u['phone']): ?> · Тел: <?= h($u['phone']) ?><?php endif; ?> · С <?= h($u['created_at']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </main>
</body>
</html>