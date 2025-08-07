<?php
require_once __DIR__ . '/../backend/auth.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($email && $password && login($email, $password)) {
        header('Location: account.php');
        exit;
    } else {
        $error = 'Неверный email или пароль';
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Вход — NOIR</title>
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
    <div class="container" style="max-width: 480px;">
      <h1 class="section__title">Вход</h1>
      <?php if ($error): ?>
        <div class="card" style="margin: 12px 0; border-color:#442222; background:#120f0f;">
          <div class="card__text"><?= h($error) ?></div>
        </div>
      <?php endif; ?>
      <form method="post" class="form">
        <input class="input" type="email" name="email" placeholder="Email" required />
        <input class="input" type="password" name="password" placeholder="Пароль" required />
        <button class="btn btn--primary" type="submit">Войти</button>
      </form>
      <p class="muted" style="margin-top:10px;">Нет аккаунта? <a class="nav__link" href="register.php">Регистрация</a></p>
    </div>
  </main>

  <script src="../js/main.js"></script>
</body>
</html>