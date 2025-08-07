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
})();