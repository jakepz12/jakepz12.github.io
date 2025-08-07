(() => {
  const magnets = document.querySelectorAll('.magnetic');
  magnets.forEach((magnet) => {
    if (!(magnet instanceof HTMLElement)) return;
    let rect;
    const strength = 16;
    const snapBackMs = 180;

    const onEnter = () => { rect = magnet.getBoundingClientRect(); };
    const onMove = (e) => {
      if (!rect) return;
      const mx = e.clientX - rect.left - rect.width / 2;
      const my = e.clientY - rect.top - rect.height / 2;
      magnet.style.transform = `translate(${mx / strength}px, ${my / strength}px)`;
    };
    const onLeave = () => {
      magnet.style.transition = `transform ${snapBackMs}ms ease`;
      magnet.style.transform = 'translate(0,0)';
      setTimeout(() => { magnet.style.transition = ''; }, snapBackMs);
    };

    magnet.addEventListener('mouseenter', onEnter);
    magnet.addEventListener('mousemove', onMove);
    magnet.addEventListener('mouseleave', onLeave);
  });

  // Ripple on buttons
  document.addEventListener('click', (e) => {
    const btn = (e.target as HTMLElement).closest('.btn');
    if (!(btn instanceof HTMLElement)) return;
    const rect = btn.getBoundingClientRect();
    const ripple = document.createElement('span');
    ripple.className = 'ripple';
    const size = Math.max(rect.width, rect.height);
    ripple.style.width = ripple.style.height = size + 'px';
    ripple.style.left = (e.clientX - rect.left - size/2) + 'px';
    ripple.style.top = (e.clientY - rect.top - size/2) + 'px';
    btn.appendChild(ripple);
    setTimeout(() => ripple.remove(), 650);
  });
})();