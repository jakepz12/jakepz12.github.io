NOIR — Черно‑белый ресторанный сайт

Страницы:
- Главная: `index.html`
- Меню: `pages/menu.html`
- О нас: `pages/about.html`
- Галерея: `pages/gallery.html`
- Бронирование: `pages/reservations.html`
- Контакты: `pages/contact.html`

Запуск
- Просто откройте `index.html` в браузере, всё работает локально.
- Либо локальный сервер: `python3 -m http.server 8000` и откройте `http://localhost:8000/index.html`.

Кастомизация
- Стили: `css/main.css`
- Скрипты: `js/*.js`
- Изображения: замените пути на свои. Сейчас используется файл `vitali-adutskevich-Fy4X_cp0dSk-unsplash.jpg` в корне проекта.

Анимации
- Canvas‑анимация на главной (`js/canvas-hero.js`).
- Появление блоков при скролле (`js/scroll-animations.js`).
- «Магнитные» кнопки (`js/ui.js`).

Бэкенд (PHP + MySQL)
1) Создайте БД через phpMyAdmin (логин: root, пароль: пусто):
   - Откройте phpMyAdmin и выполните SQL из `backend/schema.sql` (вкладка SQL)
2) Конфиг подключения: `backend/config.php`
   - По умолчанию: host=localhost, user=root, pass='', db=noir_restaurant
3) Маршруты/страницы:
   - Регистрация: `pages/register.php`
   - Вход: `pages/login.php`
   - Личный кабинет: `pages/account.php`
   - Бронирование (API): `backend/reservations.php`
   - Админ: `pages/admin/index.php`, `pages/admin/users.php`

Запуск с PHP (локально)
- Поднимите PHP dev-сервер из корня проекта:
  `php -S localhost:8001 -t .`
- Откройте `http://localhost:8001/index.html`
- Для phpMyAdmin используйте установленный у вас стек (XAMPP/MAMP/классический LAMP).
