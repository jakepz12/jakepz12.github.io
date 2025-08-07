<?php
require_once __DIR__ . '/../../backend/guard.php';
require_admin();
$conn = db_connect();

$rows = $conn->query("SELECT r.id, r.name, r.phone, r.date, r.time, r.people, r.status, r.created_at, u.email AS user_email FROM reservations r LEFT JOIN users u ON u.id = r.user_id ORDER BY r.created_at DESC LIMIT 25")->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Админ — NOIR</title>
  <link rel="stylesheet" href="../../css/main.css" />
</head>
<body>
  <header class="header">
    <div class="container nav">
      <div class="nav__logo">NOIR</div>
      <nav class="nav__links" aria-label="Навигация">
        <a class="nav__link" href="../../index.html">Главная</a>
        <a class="nav__link" href="../account.php">Кабинет</a>
        <a class="nav__link" href="index.php">Админ</a>
      </nav>
    </div>
  </header>

  <main class="section">
    <div class="container">
      <h1 class="section__title">Администратор</h1>
      <p class="section__desc">Последние заявки на бронирование</p>

      <div class="grid" id="res-list">
        <?php foreach ($rows as $row): ?>
          <div class="card" data-id="<?= h($row['id']) ?>">
            <div class="card__title">#<?= h($row['id']) ?> · <?= h($row['name']) ?> — <?= h($row['people']) ?> г.</div>
            <div class="card__text">Дата: <?= h($row['date']) ?> · Время: <?= h(substr($row['time'],0,5)) ?> · Тел: <?= h($row['phone']) ?></div>
            <div class="card__text">Статус: <span class="status"><?= h($row['status']) ?></span> · Пользователь: <?= h($row['user_email'] ?? '—') ?></div>
            <div style="margin-top:10px; display:flex; gap:8px;">
              <button class="btn" data-action="confirm">Принять</button>
              <button class="btn" data-action="cancel">Отклонить</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="section">
        <a class="btn" href="users.php">Управление пользователями</a>
        <a class="btn" href="menu.php">Управление меню</a>
      </div>
    </div>
  </main>

  <script src="../../js/main.js"></script>
  <script>
  document.getElementById('res-list')?.addEventListener('click', async (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    const card = btn.closest('.card');
    const id = card?.getAttribute('data-id');
    const action = btn.getAttribute('data-action');
    const status = action === 'confirm' ? 'confirmed' : 'cancelled';
    const form = new FormData();
    form.append('type','reservation_status');
    form.append('reservation_id', id);
    form.append('status', status);
    const res = await fetch('../../backend/admin_actions.php', { method:'POST', body: form });
    const json = await res.json();
    if (json.ok) {
      card.querySelector('.status').textContent = status;
    }
  });
  </script>
</body>
</html>