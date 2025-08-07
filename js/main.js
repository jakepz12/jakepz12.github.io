document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('.header');
  const anchorLinks = document.querySelectorAll('a[href^="#"]');

  const applyHeaderState = () => {
    if (window.scrollY > 10) {
      if (header) header.classList.add('scrolled');
    } else {
      if (header) header.classList.remove('scrolled');
    }
  };
  applyHeaderState();
  window.addEventListener('scroll', applyHeaderState, { passive: true });

  // Smooth scroll for anchors
  anchorLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      const targetLink = e.currentTarget;
      if (!(targetLink instanceof HTMLAnchorElement)) return;
      const href = targetLink.getAttribute('href');
      if (!href || href === '#' || !href.startsWith('#')) return;
      const target = document.querySelector(href);
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  // Session-aware nav links
  (async () => {
    try {
      const res = await fetch('../backend/session.php').catch(()=>null) || await fetch('backend/session.php');
      if (!res || !res.ok) return;
      const json = await res.json();
      const nav = document.querySelector('.nav__links');
      if (!nav) return;

      const hasLink = (hrefEnds) => Array.from(nav.querySelectorAll('a.nav__link')).some(a => (a instanceof HTMLAnchorElement) && (a.getAttribute('href') || '').endsWith(hrefEnds));
      const removeIfExists = (hrefEnds) => Array.from(nav.querySelectorAll('a.nav__link')).forEach(a => {
        if (!(a instanceof HTMLAnchorElement)) return;
        const href = a.getAttribute('href') || '';
        if (href.endsWith(hrefEnds)) a.remove();
      });

      if (json.authenticated) {
        // hide login/register if present
        removeIfExists('/pages/login.php');
        removeIfExists('pages/login.php');
        removeIfExists('/pages/register.php');
        removeIfExists('pages/register.php');
        // append account/admin/logout
        if (!hasLink('/pages/account.php') && !hasLink('pages/account.php')) {
          const accountLink = document.createElement('a');
          accountLink.className = 'nav__link';
          accountLink.href = '/pages/account.php';
          accountLink.textContent = 'Кабинет';
          nav.appendChild(accountLink);
        }
        if (json.user && json.user.role === 'admin' && !hasLink('/pages/admin/index.php') && !hasLink('pages/admin/index.php')) {
          const adminLink = document.createElement('a');
          adminLink.className = 'nav__link';
          adminLink.href = '/pages/admin/index.php';
          adminLink.textContent = 'Админ';
          nav.appendChild(adminLink);
        }
        if (!hasLink('/pages/logout.php') && !hasLink('pages/logout.php')) {
          const logoutLink = document.createElement('a');
          logoutLink.className = 'nav__link';
          logoutLink.href = '/pages/logout.php';
          logoutLink.textContent = 'Выйти';
          nav.appendChild(logoutLink);
        }
      } else {
        // not authenticated: remove account/admin/logout if present, add login/register if missing
        removeIfExists('/pages/account.php');
        removeIfExists('pages/account.php');
        removeIfExists('/pages/admin/index.php');
        removeIfExists('pages/admin/index.php');
        removeIfExists('/pages/logout.php');
        removeIfExists('pages/logout.php');
        if (!hasLink('/pages/login.php') && !hasLink('pages/login.php')) {
          const loginLink = document.createElement('a');
          loginLink.className = 'nav__link';
          loginLink.href = '/pages/login.php';
          loginLink.textContent = 'Вход';
          nav.appendChild(loginLink);
        }
        if (!hasLink('/pages/register.php') && !hasLink('pages/register.php')) {
          const regLink = document.createElement('a');
          regLink.className = 'nav__link';
          regLink.href = '/pages/register.php';
          regLink.textContent = 'Регистрация';
          nav.appendChild(regLink);
        }
      }
    } catch {}
  })();

  // Form handling for reservations page (fallback toast)
  const reservationForm = document.querySelector('#reservation-form');
  const toastEl = document.querySelector('#toast');
  if (reservationForm instanceof HTMLFormElement) {
    reservationForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const formData = new FormData(reservationForm);
      const requiredFields = ['name', 'phone', 'date', 'time', 'people'];
      for (const field of requiredFields) {
        const value = String(formData.get(field) || '').trim();
        if (!value) {
          showToast('Пожалуйста, заполните все обязательные поля');
          return;
        }
      }
      showToast('Спасибо! Мы свяжемся с вами для подтверждения.');
      reservationForm.reset();
    });
  }

  function showToast(message) {
    if (!(toastEl instanceof HTMLElement)) {
      alert(message);
      return;
    }
    toastEl.textContent = message;
    toastEl.classList.add('show');
    setTimeout(() => toastEl.classList.remove('show'), 2800);
  }
});