<?php
require_once __DIR__ . '/../backend/auth.php';

$msg = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($name && $email && $password) {
        [$ok, $message] = register_user($name, $email, $phone, $password);
        if ($ok) {
            $msg = $message;
        } else {
            $error = $message;
        }
    } else {
        $error = 'Заполните обязательные поля';
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Регистрация — NOIR</title>
  <link rel="stylesheet" href="../css/main.css" />
</head>
<body>
  <header class="header">
    <div class="container nav">
      <div class="nav__logo">NOIR</div>
      <nav class="nav__links" aria-label="Навигация">
        <a class="nav__link" href="../index.html">Главная</a>
        <a class="nav__link" href="menu.html">Меню</a>
        <a class="nav__link" href="login.php">Вход</a>
        <a class="nav__link" href="register.php">Регистрация</a>
        <a class="nav__link" href="support.html">Поддержка</a>
      </nav>
    </div>
  </header>

  <main class="section full-section">
    <div class="container" style="max-width: 520px;">
      <h1 class="section__title">Регистрация</h1>
      <?php if ($error): ?>
        <div class="card" style="margin: 12px 0; border-color:#442222; background:#120f0f;">
          <div class="card__text"><?= h($error) ?></div>
        </div>
      <?php endif; ?>
      <?php if ($msg): ?>
        <div class="card" style="margin: 12px 0; border-color:#224422; background:#0f120f;">
          <div class="card__text"><?= h($msg) ?></div>
        </div>
      <?php endif; ?>
      <form method="post" class="form">
        <input class="input" type="text" name="name" placeholder="Имя*" required />
        <input class="input" type="email" name="email" placeholder="Email*" required />
        <input class="input" type="tel" name="phone" placeholder="Телефон" />
        <input class="input" type="password" name="password" placeholder="Пароль*" required />
        <button class="btn btn--primary" type="submit">Зарегистрироваться</button>
      </form>
      <p class="muted" style="margin-top:10px;">Уже есть аккаунт? <a class="nav__link" href="login.php">Войти</a></p>
    </div>
  </main>

  <script src="../js/main.js"></script>
</body>
</html>