<?php
require_once __DIR__ . '/../backend/config.php';
$conn = db_connect();
$cats = $conn->query('SELECT id, title FROM menu_categories ORDER BY position, id')->fetch_all(MYSQLI_ASSOC);
$itemsByCat = [];
if ($cats) {
  $ids = implode(',', array_map('intval', array_column($cats, 'id')));
  $q = $conn->query("SELECT id, category_id, name, description, price FROM menu_items WHERE category_id IN ($ids) ORDER BY position, id");
  while ($row = $q->fetch_assoc()) { $itemsByCat[$row['category_id']][] = $row; }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Меню — NOIR</title>
  <link rel="stylesheet" href="../css/main.css" />
</head>
<body>
  <header class="header">
    <div class="container nav">
      <div class="nav__logo">NOIR</div>
      <nav class="nav__links" aria-label="Основная навигация">
        <a class="nav__link" href="../index.html">Главная</a>
        <a class="nav__link" href="menu.php">Меню</a>
        <a class="nav__link" href="about.html">О нас</a>
        <a class="nav__link" href="gallery.html">Галерея</a>
        <a class="nav__link" href="reservations.html">Бронирование</a>
        <a class="nav__link" href="contact.html">Контакты</a>
      </nav>
    </div>
  </header>

  <main class="section">
    <div class="container">
      <h1 class="section__title">Меню</h1>
      <p class="section__desc">Актуальное меню формируется из свежих продуктов.</p>

      <?php foreach ($cats as $c): ?>
      <section class="menu-section">
        <h2 class="section__title" style="font-size: 24px;"><?= h($c['title']) ?></h2>
        <div class="menu-grid">
          <?php foreach ($itemsByCat[$c['id']] ?? [] as $i): ?>
          <div class="menu-item">
            <div>
              <div class="menu-item__name"><?= h($i['name']) ?></div>
              <?php if ($i['description']): ?><div class="menu-item__desc"><?= h($i['description']) ?></div><?php endif; ?>
            </div>
            <div class="menu-item__price"><?= number_format((float)$i['price'], 0, ',', ' ') ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endforeach; ?>
    </div>
  </main>

  <footer class="footer">
    <div class="container">
      <div class="footer__top">
        <div>NOIR</div>
        <div class="muted">Ежедневно 12:00 — 23:00</div>
      </div>
      <div class="footer__bottom">© NOIR, 2025</div>
    </div>
  </footer>

  <script src="../js/main.js"></script>
  <script src="../js/scroll-animations.js"></script>
  <script src="../js/ui.js"></script>
</body>
</html>