// ===== IMAGE PREVIEW PLUGIN (running live on this page) =====
(function () {
  // Buat overlay hanya sekali
  const overlay = document.createElement("div");
  overlay.className = "preview-overlay";
  overlay.innerHTML = `
      <button class="preview-close" aria-label="Tutup">✕</button>
      <img src="" alt="Preview" />
    `;
  document.body.appendChild(overlay);

  const previewImg = overlay.querySelector("img");
  const closeBtn = overlay.querySelector(".preview-close");

  function open(src, alt) {
    previewImg.src = src;
    previewImg.alt = alt || "Preview";
    overlay.classList.add("active");
    document.body.style.overflow = "hidden";
  }
  function close() {
    overlay.classList.remove("active");
    document.body.style.overflow = "";
  }

  document.addEventListener("click", function (e) {
    const img = e.target.closest("img[data-preview]");
    if (img) open(img.src, img.alt);
  });
  closeBtn.addEventListener("click", close);
  overlay.addEventListener("click", function (e) {
    if (e.target === overlay) close();
  });
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") close();
  });
})();

// ===== Copy Code Button =====
function copyCode() {
  const rawCode = `(function() {
  const overlay = document.createElement('div');
  overlay.className = 'preview-overlay';
  overlay.innerHTML = \`
    <button class="preview-close" aria-label="Tutup">✕</button>
    <img src="" alt="Preview" />
  \`;
  document.body.appendChild(overlay);

  const previewImg = overlay.querySelector('img');
  const closeBtn = overlay.querySelector('.preview-close');

  function open(src, alt) {
    previewImg.src = src;
    previewImg.alt = alt || 'Preview';
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  function close() {
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  document.addEventListener('click', function(e) {
    const img = e.target.closest('img[data-preview]');
    if (img) open(img.src, img.alt);
  });
  closeBtn.addEventListener('click', close);
  overlay.addEventListener('click', function(e) {
    if (e.target === overlay) close();
  });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') close();
  });
})();`;

  navigator.clipboard.writeText(rawCode).then(() => {
    const btn = document.getElementById("copy-btn");
    btn.innerHTML =
      '<i data-lucide="check" style="width:14px;height:14px;"></i> Tersalin!';
    btn.style.background = "#16a34a";
    lucide.createIcons();
    setTimeout(() => {
      btn.innerHTML =
        '<i data-lucide="copy" style="width:14px;height:14px;"></i> Salin Kode';
      btn.style.background = "#1a1a2e";
      lucide.createIcons();
    }, 2000);
  });
}

if (window.elementSdk) {
  window.elementSdk.init({
    defaultConfig,
    onConfigChange: async (config) => {
      const c = { ...defaultConfig, ...config };
      const app = document.getElementById("app");
      app.style.background = c.background_color;

      document.getElementById("hero-title").textContent = c.page_title;
      document.getElementById("hero-subtitle").textContent = c.subtitle;

      const font = c.font_family || defaultConfig.font_family;
      const base = c.font_size || defaultConfig.font_size;
      app.style.fontFamily = `${font}, Outfit, sans-serif`;

      document
        .querySelectorAll("h2")
        .forEach((el) => (el.style.fontSize = `${base * 1.73}px`));
      document
        .querySelectorAll("h3")
        .forEach((el) => (el.style.fontSize = `${base * 1.13}px`));
      document.querySelectorAll("p").forEach((el) => {
        if (!el.closest(".code-block")) el.style.fontSize = `${base * 0.93}px`;
      });
    },
    mapToCapabilities: (config) => {
      const c = { ...defaultConfig, ...config };
      return {
        recolorables: [
          {
            get: () => c.background_color,
            set: (v) => {
              c.background_color = v;
              window.elementSdk.setConfig({ background_color: v });
            },
          },
          {
            get: () => c.surface_color,
            set: (v) => {
              c.surface_color = v;
              window.elementSdk.setConfig({ surface_color: v });
            },
          },
          {
            get: () => c.text_color,
            set: (v) => {
              c.text_color = v;
              window.elementSdk.setConfig({ text_color: v });
            },
          },
          {
            get: () => c.accent_color,
            set: (v) => {
              c.accent_color = v;
              window.elementSdk.setConfig({ accent_color: v });
            },
          },
        ],
        borderables: [],
        fontEditable: {
          get: () => c.font_family,
          set: (v) => {
            c.font_family = v;
            window.elementSdk.setConfig({ font_family: v });
          },
        },
        fontSizeable: {
          get: () => c.font_size,
          set: (v) => {
            c.font_size = v;
            window.elementSdk.setConfig({ font_size: v });
          },
        },
      };
    },
    mapToEditPanelValues: (config) =>
      new Map([
        ["page_title", config.page_title || defaultConfig.page_title],
        ["subtitle", config.subtitle || defaultConfig.subtitle],
      ]),
  });
}

lucide.createIcons();
