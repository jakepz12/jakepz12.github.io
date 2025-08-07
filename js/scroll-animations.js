(() => {
  const elements = Array.from(document.querySelectorAll('.reveal, [data-reveal]'));
  const parallaxEls = Array.from(document.querySelectorAll('[data-parallax]'));
  if (!elements.length && !parallaxEls.length) return;

  const observer = new IntersectionObserver((entries) => {
    for (const entry of entries) {
      if (entry.isIntersecting) {
        const el = entry.target as HTMLElement;
        const delay = el.getAttribute('data-delay');
        if (delay) el.style.transitionDelay = `${parseFloat(delay)}ms`;
        el.classList.add('is-visible');
        observer.unobserve(el);
      }
    }
  }, { rootMargin: '0px 0px -10% 0px', threshold: 0.15 });

  elements.forEach((el, i) => {
    const d = el.getAttribute('data-stagger');
    if (d) el.setAttribute('data-delay', String(i * parseInt(d,10)));
    observer.observe(el);
  });

  // Simple parallax on scroll
  const onScroll = () => {
    const y = window.scrollY || 0;
    parallaxEls.forEach((el) => {
      const factor = parseFloat((el as HTMLElement).getAttribute('data-parallax') || '0.15');
      (el as HTMLElement).style.transform = `translateY(${y * factor}px)`;
    });
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
})();