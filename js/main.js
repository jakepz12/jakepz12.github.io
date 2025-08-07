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

  // Form handling for reservations page
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