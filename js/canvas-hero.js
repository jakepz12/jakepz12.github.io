(() => {
  const canvas = document.getElementById('hero-canvas');
  if (!(canvas instanceof HTMLCanvasElement)) return;
  const ctx = canvas.getContext('2d');
  if (!ctx) return;

  let width = 0;
  let height = 0;
  let deviceRatio = Math.min(2, window.devicePixelRatio || 1);
  let startTime = performance.now();
  let rafId = 0;

  function resize() {
    width = canvas.clientWidth;
    height = canvas.clientHeight;
    deviceRatio = Math.min(2, window.devicePixelRatio || 1);
    canvas.width = Math.floor(width * deviceRatio);
    canvas.height = Math.floor(height * deviceRatio);
    ctx.setTransform(deviceRatio, 0, 0, deviceRatio, 0, 0);
  }

  function easeInOutSine(t) {
    return -(Math.cos(Math.PI * t) - 1) / 2;
  }

  function draw(time) {
    const t = (time - startTime) / 1000;
    ctx.clearRect(0, 0, width, height);

    // Background subtle vignette
    const g = ctx.createRadialGradient(width/2, height/2, Math.min(width,height)*0.2, width/2, height/2, Math.max(width,height)*0.8);
    g.addColorStop(0, '#0a0a0a');
    g.addColorStop(1, '#000000');
    ctx.fillStyle = g;
    ctx.fillRect(0, 0, width, height);

    // Draw layered sine waves
    const layers = 7;
    for (let i = 0; i < layers; i++) {
      const progress = i / (layers - 1);
      const alpha = 0.06 + progress * 0.07;
      const amplitude = 10 + progress * 60;
      const wavelength = 300 - progress * 200; // px per cycle
      const speed = 0.4 + progress * 0.5; // cycles per second
      const yBase = height * (0.35 + progress * 0.3);

      ctx.beginPath();
      for (let x = 0; x <= width; x += 2) {
        const phase = t * speed * Math.PI * 2 + (progress * Math.PI * 1.5);
        const y = yBase + Math.sin((x / wavelength) * Math.PI * 2 + phase) * amplitude * easeInOutSine((Math.sin(t*0.5)+1)/2);
        if (x === 0) ctx.moveTo(x, y);
        else ctx.lineTo(x, y);
      }
      ctx.strokeStyle = `rgba(255,255,255,${alpha.toFixed(3)})`;
      ctx.lineWidth = 1.2;
      ctx.stroke();
    }

    // Particle sparkle
    const particles = 60;
    for (let p = 0; p < particles; p++) {
      const px = (p * 73 + (t * 30)) % width;
      const py = height * (0.25 + ((p * 97) % 60) / 100);
      const size = 0.6 + ((p * 19) % 7) / 10;
      ctx.fillStyle = 'rgba(255,255,255,0.08)';
      ctx.fillRect(px, py, size, size);
    }

    if (document.visibilityState !== 'hidden') {
      rafId = requestAnimationFrame(draw);
    }
  }

  const onVisibility = () => {
    if (document.visibilityState === 'hidden') {
      cancelAnimationFrame(rafId);
    } else {
      startTime = performance.now();
      rafId = requestAnimationFrame(draw);
    }
  };

  window.addEventListener('resize', resize);
  document.addEventListener('visibilitychange', onVisibility);
  resize();
  rafId = requestAnimationFrame(draw);
})();