class DotField {
  constructor(container, opts) {
    this.container = container;
    const p = Object.assign({
      dotRadius: 1.5,
      dotSpacing: 14,
      cursorRadius: 500,
      cursorForce: 0.1,
      bulgeOnly: true,
      bulgeStrength: 67,
      glowRadius: 160,
      sparkle: false,
      waveAmplitude: 0,
      gradientFrom: 'rgba(255, 252, 0, 0.85)',
      gradientTo: 'rgba(255, 252, 0, 0.35)',
      glowColor: '#FFFC00',
    }, opts);
    this.p = p;

    this.canvas = document.createElement('canvas');
    this.canvas.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;display:block';
    container.appendChild(this.canvas);
    this.ctx = this.canvas.getContext('2d', { alpha: true });

    this.svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    this.svg.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;pointer-events:none';

    this.glowId = 'dot-field-glow-' + Math.random().toString(36).slice(2, 9);
    const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
    const radialGrad = document.createElementNS('http://www.w3.org/2000/svg', 'radialGradient');
    radialGrad.setAttribute('id', this.glowId);
    const stop1 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
    stop1.setAttribute('offset', '0%');
    stop1.setAttribute('stop-color', p.glowColor);
    const stop2 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
    stop2.setAttribute('offset', '100%');
    stop2.setAttribute('stop-color', p.glowColor);
    stop2.setAttribute('stop-opacity', '0');
    radialGrad.appendChild(stop1);
    radialGrad.appendChild(stop2);
    defs.appendChild(radialGrad);
    this.svg.appendChild(defs);

    this.glowEl = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    this.glowEl.setAttribute('cx', '-9999');
    this.glowEl.setAttribute('cy', '-9999');
    this.glowEl.setAttribute('r', p.glowRadius);
    this.glowEl.setAttribute('fill', `url(#${this.glowId})`);
    this.glowEl.style.cssText = 'opacity:0;will-change:opacity';
    this.svg.appendChild(this.glowEl);
    container.appendChild(this.svg);

    this.mouse = { x: -9999, y: -9999, prevX: -9999, prevY: -9999, speed: 0 };
    this.dots = [];
    this.size = { w: 0, h: 0, offsetX: 0, offsetY: 0 };
    this.glowOpacity = 0;
    this.engagement = 0;
    this.frameCount = 0;
    this.rafId = null;
    this.destroyed = false;

    this._onMouseMove = this._onMouseMove.bind(this);
    this._onResize = this._onResize.bind(this);

    this._init();
  }

  _init() {
    this._resize();
    if (this.size.w === 0 || this.size.h === 0) {
      var self = this;
      (function retry() {
        if (self.destroyed) return;
        self._resize();
        if (self.size.w === 0 || self.size.h === 0) requestAnimationFrame(retry);
      })();
    }
    window.addEventListener('resize', this._onResize);
    window.addEventListener('mousemove', this._onMouseMove, { passive: true });

    this._speedInterval = setInterval(() => {
      const m = this.mouse;
      const dx = m.prevX - m.x;
      const dy = m.prevY - m.y;
      const dist = Math.sqrt(dx * dx + dy * dy);
      m.speed += (dist - m.speed) * 0.5;
      if (m.speed < 0.001) m.speed = 0;
      m.prevX = m.x;
      m.prevY = m.y;
    }, 20);

    this._tick();
  }

  _onMouseMove(e) {
    const s = this.size;
    this.mouse.x = e.pageX - s.offsetX;
    this.mouse.y = e.pageY - s.offsetY;
  }

  _onResize() {
    this._resize();
  }

  _resize() {
    const rect = this.container.getBoundingClientRect();
    const w = rect.width;
    const h = rect.height;
    if (w === this.size.w && h === this.size.h) return;

    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    this.canvas.width = w * dpr;
    this.canvas.height = h * dpr;
    this.canvas.style.width = w + 'px';
    this.canvas.style.height = h + 'px';
    this.ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

    this.size = { w, h, offsetX: rect.left + window.scrollX, offsetY: rect.top + window.scrollY };
    this._buildDots(w, h);
  }

  _buildDots(w, h) {
    const p = this.p;
    const step = p.dotRadius + p.dotSpacing;
    const cols = Math.floor(w / step);
    const rows = Math.floor(h / step);
    const padX = (w % step) / 2;
    const padY = (h % step) / 2;
    const dots = new Array(rows * cols);
    let idx = 0;
    for (let row = 0; row < rows; row++) {
      for (let col = 0; col < cols; col++) {
        const ax = padX + col * step + step / 2;
        const ay = padY + row * step + step / 2;
        dots[idx++] = { ax, ay, sx: ax, sy: ay, vx: 0, vy: 0, x: ax, y: ay };
      }
    }
    this.dots = dots;
  }

  _tick() {
    if (this.destroyed) return;
    this.frameCount++;
    const dots = this.dots;
    const m = this.mouse;
    const { w, h } = this.size;
    const p = this.p;
    const len = dots.length;
    const t = this.frameCount * 0.02;

    const targetEng = Math.min(m.speed / 5, 1);
    this.engagement += (targetEng - this.engagement) * 0.06;
    if (this.engagement < 0.001) this.engagement = 0;
    const eng = this.engagement;

    this.glowOpacity += (eng - this.glowOpacity) * 0.08;

    this.glowEl.setAttribute('cx', m.x);
    this.glowEl.setAttribute('cy', m.y);
    this.glowEl.style.opacity = this.glowOpacity;

    const ctx = this.ctx;
    ctx.clearRect(0, 0, w, h);

    const grad = ctx.createLinearGradient(0, 0, w, h);
    grad.addColorStop(0, p.gradientFrom);
    grad.addColorStop(1, p.gradientTo);
    ctx.fillStyle = grad;

    const cr = p.cursorRadius;
    const crSq = cr * cr;
    const rad = p.dotRadius / 2;
    const isBulge = p.bulgeOnly;

    ctx.beginPath();

    for (let i = 0; i < len; i++) {
      const d = dots[i];
      const dx = m.x - d.ax;
      const dy = m.y - d.ay;
      const distSq = dx * dx + dy * dy;

      if (distSq < crSq && eng > 0.01) {
        const dist = Math.sqrt(distSq);
        if (isBulge) {
          const t = 1 - dist / cr;
          const push = t * t * p.bulgeStrength * eng;
          const angle = Math.atan2(dy, dx);
          d.sx += (d.ax - Math.cos(angle) * push - d.sx) * 0.15;
          d.sy += (d.ay - Math.sin(angle) * push - d.sy) * 0.15;
        } else {
          const angle = Math.atan2(dy, dx);
          const move = (500 / dist) * (m.speed * p.cursorForce);
          d.vx += Math.cos(angle) * -move;
          d.vy += Math.sin(angle) * -move;
        }
      } else if (isBulge) {
        d.sx += (d.ax - d.sx) * 0.1;
        d.sy += (d.ay - d.sy) * 0.1;
      }

      if (!isBulge) {
        d.vx *= 0.9;
        d.vy *= 0.9;
        d.x = d.ax + d.vx;
        d.y = d.ay + d.vy;
        d.sx += (d.x - d.sx) * 0.1;
        d.sy += (d.y - d.sy) * 0.1;
      }

      let drawX = d.sx;
      let drawY = d.sy;
      if (p.waveAmplitude > 0) {
        drawY += Math.sin(d.ax * 0.03 + t) * p.waveAmplitude;
        drawX += Math.cos(d.ay * 0.03 + t * 0.7) * p.waveAmplitude * 0.5;
      }

      if (p.sparkle) {
        const hash = ((i * 2654435761) ^ (this.frameCount >> 3)) >>> 0;
        if ((hash % 100) < 3) {
          ctx.moveTo(drawX + rad * 1.8, drawY);
          ctx.arc(drawX, drawY, rad * 1.8, 0, Math.PI * 2);
        } else {
          ctx.moveTo(drawX + rad, drawY);
          ctx.arc(drawX, drawY, rad, 0, Math.PI * 2);
        }
      } else {
        ctx.moveTo(drawX + rad, drawY);
        ctx.arc(drawX, drawY, rad, 0, Math.PI * 2);
      }
    }

    ctx.fill();
    this.rafId = requestAnimationFrame(() => this._tick());
  }

  rebuild() {
    const { w, h } = this.size;
    if (w > 0 && h > 0) this._buildDots(w, h);
  }

  destroy() {
    this.destroyed = true;
    if (this.rafId) cancelAnimationFrame(this.rafId);
    if (this._speedInterval) clearInterval(this._speedInterval);
    window.removeEventListener('resize', this._onResize);
    window.removeEventListener('mousemove', this._onMouseMove);
    this.canvas.remove();
    this.svg.remove();
  }
}

window.DotField = DotField;
