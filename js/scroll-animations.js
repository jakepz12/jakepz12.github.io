(() => {
  const elements = Array.from(document.querySelectorAll('.reveal, [data-reveal]'));
  const parallaxEls = Array.from(document.querySelectorAll('[data-parallax]'));
  if (!elements.length && !parallaxEls.length) return;

  const observer = new IntersectionObserver((entries) => {
    for (const entry of entries) {
      if (entry.isIntersecting) {
        const el = entry.target;
        if (el instanceof HTMLElement) {
          const delay = el.getAttribute('data-delay');
          if (delay) el.style.transitionDelay = `${parseFloat(delay)}ms`;
          el.classList.add('is-visible');
        }
        observer.unobserve(entry.target);
      }
    }
  }, { rootMargin: '0px 0px -10% 0px', threshold: 0.15 });

  elements.forEach((el, i) => {
    if (el instanceof HTMLElement) {
      const d = el.getAttribute('data-stagger');
      if (d) el.setAttribute('data-delay', String(i * parseInt(d,10)));
    }
    observer.observe(el);
  });

  // Simple parallax on scroll
  const onScroll = () => {
    const y = window.scrollY || 0;
    parallaxEls.forEach((el) => {
      if (!(el instanceof HTMLElement)) return;
      const factor = parseFloat(el.getAttribute('data-parallax') || '0.15');
      el.style.transform = `translateY(${y * factor}px)`;
    });
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
})();