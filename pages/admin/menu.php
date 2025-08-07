<?php
require_once __DIR__ . '/../../backend/guard.php';
require_admin();
$conn = db_connect();
$cats = $conn->query('SELECT id, title, position FROM menu_categories ORDER BY position, id')->fetch_all(MYSQLI_ASSOC);
$items = $conn->query('SELECT i.id, i.category_id, i.name, i.description, i.price, i.position, c.title AS category FROM menu_items i JOIN menu_categories c ON c.id=i.category_id ORDER BY c.position, i.position')->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Управление меню — NOIR</title>
  <link rel="stylesheet" href="../../css/main.css" />
</head>
<body>
  <header class="header">
    <div class="container nav">
      <div class="nav__logo">NOIR</div>
      <nav class="nav__links" aria-label="Навигация">
        <a class="nav__link" href="../../index.html">Главная</a>
        <a class="nav__link" href="index.php">Админ</a>
        <a class="nav__link" href="menu.php">Меню</a>
      </nav>
    </div>
  </header>
  <main class="section">
    <div class="container">
      <h1 class="section__title">Категории</h1>
      <div class="grid" id="cats">
        <?php foreach ($cats as $c): ?>
          <form class="card" data-id="<?= h($c['id']) ?>">
            <div class="input-row" style="grid-template-columns: 2fr 1fr 1fr;">
              <input class="input" name="title" value="<?= h($c['title']) ?>" />
              <input class="input" name="position" type="number" value="<?= h($c['position']) ?>" />
              <div style="display:flex; gap:8px;">
                <button class="btn" data-action="save-cat" type="submit">Сохранить</button>
                <button class="btn" data-action="delete-cat">Удалить</button>
              </div>
            </div>
          </form>
        <?php endforeach; ?>
        <form class="card" data-id="0">
          <div class="input-row" style="grid-template-columns: 2fr 1fr 1fr;">
            <input class="input" name="title" placeholder="Новая категория" />
            <input class="input" name="position" type="number" value="0" />
            <button class="btn" data-action="save-cat" type="submit">Добавить</button>
          </div>
        </form>
      </div>

      <h1 class="section__title" style="margin-top:36px;">Блюда</h1>
      <div class="grid" id="items">
        <?php foreach ($items as $i): ?>
          <form class="card" data-id="<?= h($i['id']) ?>">
            <div class="card__title">Категория: <?= h($i['category']) ?></div>
            <div class="input-row" style="grid-template-columns: 2fr 1fr 1fr 1fr;">
              <input class="input" name="name" value="<?= h($i['name']) ?>" />
              <input class="input" name="price" type="number" step="0.01" value="<?= h($i['price']) ?>" />
              <input class="input" name="position" type="number" value="<?= h($i['position']) ?>" />
              <select class="input" name="category_id">
                <?php foreach ($cats as $c): ?>
                  <option value="<?= h($c['id']) ?>" <?= $c['id']==$i['category_id']?'selected':'' ?>><?= h($c['title']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <textarea class="input" name="description" rows="2" placeholder="Описание"><?= h($i['description']) ?></textarea>
            <div style="display:flex; gap:8px; margin-top:8px;">
              <button class="btn" data-action="save-item" type="submit">Сохранить</button>
              <button class="btn" data-action="delete-item">Удалить</button>
            </div>
          </form>
        <?php endforeach; ?>
        <form class="card" data-id="0">
          <div class="card__title">Добавить блюдо</div>
          <div class="input-row" style="grid-template-columns: 2fr 1fr 1fr 1fr;">
            <input class="input" name="name" placeholder="Название" />
            <input class="input" name="price" type="number" step="0.01" value="0" />
            <input class="input" name="position" type="number" value="0" />
            <select class="input" name="category_id">
              <?php foreach ($cats as $c): ?>
                <option value="<?= h($c['id']) ?>"><?= h($c['title']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <textarea class="input" name="description" rows="2" placeholder="Описание"></textarea>
          <button class="btn" data-action="save-item" type="submit">Добавить</button>
        </form>
      </div>
    </div>
  </main>

  <script>
  async function post(type, data) {
    const form = new FormData();
    form.append('type', type);
    for (const [k,v] of Object.entries(data)) form.append(k, v);
    const res = await fetch('../../backend/admin_actions.php', { method: 'POST', body: form });
    return res.json();
  }

  document.getElementById('cats')?.addEventListener('click', async (e) => {
    const del = e.target.closest('button[data-action="delete-cat"]');
    if (!del) return;
    e.preventDefault();
    const id = del.closest('form').getAttribute('data-id');
    const json = await post('menu_category_delete', { id });
    if (json.ok) location.reload();
  });

  document.getElementById('cats')?.addEventListener('submit', async (e) => {
    if (!(e.target instanceof HTMLFormElement)) return;
    e.preventDefault();
    const formEl = e.target;
    const id = formEl.getAttribute('data-id');
    const title = formEl.querySelector('[name=title]').value;
    const position = formEl.querySelector('[name=position]').value;
    const json = await post('menu_category_save', { id, title, position });
    if (json.ok) location.reload();
  });

  document.getElementById('items')?.addEventListener('click', async (e) => {
    const del = e.target.closest('button[data-action="delete-item"]');
    if (!del) return;
    e.preventDefault();
    const id = del.closest('form').getAttribute('data-id');
    const json = await post('menu_item_delete', { id });
    if (json.ok) location.reload();
  });

  document.getElementById('items')?.addEventListener('submit', async (e) => {
    if (!(e.target instanceof HTMLFormElement)) return;
    e.preventDefault();
    const f = e.target;
    const id = f.getAttribute('data-id');
    const payload = {
      id,
      name: f.querySelector('[name=name]').value,
      description: f.querySelector('[name=description]').value,
      price: f.querySelector('[name=price]').value,
      position: f.querySelector('[name=position]').value,
      category_id: f.querySelector('[name=category_id]').value,
    };
    const json = await post('menu_item_save', payload);
    if (json.ok) location.reload();
  });
  </script>
</body>
</html>