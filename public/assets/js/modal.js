// ==========================================
// FITMATCH MODAL PLUGIN — JavaScript
// ==========================================
/**
 * FitMatch Modal Plugin
 * Konsep data-attribute mirip Bootstrap
 *
 * Attributes:
 *   data-fm-toggle="modal"    → trigger button
 *   data-fm-target="#modalId" → target modal selector
 *   data-fm-dismiss="modal"   → close button
 *
 * JS API:
 *   FitMatchModal.open('#modalId')
 *   FitMatchModal.close('#modalId')
 *   FitMatchModal.closeAll()
 */
const FitMatchModal = (() => {
  const backdrop = document.getElementById("fmBackdrop");
  let activeModals = [];

  function open(selector) {
    const modal =
      typeof selector === "string"
        ? document.querySelector(selector)
        : selector;
    if (!modal) return; // Safely handle missing element
    if (modal.classList.contains("fm-active")) return;

    document.body.classList.add("fm-modal-open");
    if (backdrop) backdrop.classList.add("fm-active");
    modal.classList.add("fm-active");
    activeModals.push(modal);

    // Focus first focusable element
    requestAnimationFrame(() => {
      const focusable = modal.querySelector(
        'button, input, select, textarea, [tabindex]:not([tabindex="-1"])',
      );
      if (focusable) focusable.focus();
    });
  }

  function close(selector) {
    const modal =
      typeof selector === "string"
        ? document.querySelector(selector)
        : selector;
    if (!modal) return; // Safely handle missing element

    modal.classList.remove("fm-active");
    activeModals = activeModals.filter((m) => m !== modal);

    if (activeModals.length === 0) {
      if (backdrop) backdrop.classList.remove("fm-active");
      document.body.classList.remove("fm-modal-open");
    }
  }

  function closeAll() {
    activeModals.forEach((m) => m.classList.remove("fm-active"));
    activeModals = [];
    if (backdrop) backdrop.classList.remove("fm-active");
    document.body.classList.remove("fm-modal-open");
  }

  // Delegate: toggle buttons
  document.addEventListener("click", (e) => {
    const trigger = e.target.closest('[data-fm-toggle="modal"]');
    if (trigger) {
      const target = trigger.getAttribute("data-fm-target");
      if (target) open(target);
      return;
    }

    const dismiss = e.target.closest('[data-fm-dismiss="modal"]');
    if (dismiss) {
      const modal = dismiss.closest(".fm-modal");
      if (modal) close(modal);
      return;
    }
  });

  // Close on backdrop click
  if (backdrop) {
    backdrop.addEventListener("click", () => closeAll());
  }

  // Close on clicking modal padding (outside dialog)
  document.addEventListener("click", (e) => {
    if (
      e.target &&
      e.target.classList &&
      e.target.classList.contains("fm-modal") &&
      e.target.classList.contains("fm-active")
    ) {
      close(e.target);
    }
  });

  // Close on Escape key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && activeModals.length > 0) {
      close(activeModals[activeModals.length - 1]);
    }
  });

  return { open, close, closeAll };
})();

// Form submit handler
function handleFormSubmit(e) {
  e.preventDefault();
  const toast = document.getElementById("formToast");
  if (toast) {
    toast.classList.add("fm-show");
    setTimeout(() => toast.classList.remove("fm-show"), 2500);
  }
}

// Delete handler
function handleDelete(btn) {
  if (!btn) return; // Safely handle missing button
  const footer = btn.closest(".fm-modal-footer");
  btn.textContent = "Terhapus!";
  btn.disabled = true;
  btn.style.opacity = "0.6";
  setTimeout(() => {
    FitMatchModal.close("#modalConfirm");
    setTimeout(() => {
      btn.textContent = "Ya, Hapus";
      btn.disabled = false;
      btn.style.opacity = "1";
    }, 400);
  }, 1200);
}

// Initialize Lucide icons safely
if (typeof lucide !== "undefined" && lucide.createIcons) {
  lucide.createIcons();
}

function applyConfig(config) {
  const title = config.page_title || defaultConfig.page_title;
  const el = document.getElementById("pageTitle");
  if (el) {
    el.innerHTML = `<span>${title}</span> Modal Plugin`;
  }

  const bg = config.background_color || defaultConfig.background_color;
  const surface = config.surface_color || defaultConfig.surface_color;
  const text = config.text_color || defaultConfig.text_color;
  const accent = config.accent_color || defaultConfig.accent_color;
  const muted = config.muted_color || defaultConfig.muted_color;

  document.documentElement.style.setProperty("--fm-accent", accent);
  document.documentElement.style.setProperty("--fm-text", text);
  document.documentElement.style.setProperty("--fm-text-muted", muted);
  document.documentElement.style.setProperty("--fm-modal-bg", surface);

  const page = document.querySelector(".demo-page");
  if (page) {
    page.style.background = `linear-gradient(160deg, ${bg} 0%, #e8f4f8 50%, #fdf2f8 100%)`;
  }

  const cards = document.querySelectorAll(".demo-card");
  if (cards.length > 0) {
    cards.forEach((c) => {
      c.style.background = surface;
    });
  }

  const font = config.font_family || defaultConfig.font_family;
  const baseSize = config.font_size || defaultConfig.font_size;

  const heroTitle = document.querySelector(".demo-hero h1");
  if (heroTitle) {
    heroTitle.style.fontFamily = `${font}, sans-serif`;
    heroTitle.style.fontSize = `${baseSize * 2}px`;
  }

  const headings = document.querySelectorAll(".demo-card h3, .fm-modal-title");
  if (headings.length > 0) {
    headings.forEach((el) => {
      el.style.fontFamily = `${font}, sans-serif`;
      el.style.fontSize = `${baseSize * 1.1}px`;
    });
  }

  const textElements = document.querySelectorAll(
    ".demo-card p, .fm-modal-body",
  );
  if (textElements.length > 0) {
    textElements.forEach((el) => {
      el.style.fontFamily = `DM Sans, ${font}, sans-serif`;
      el.style.fontSize = `${baseSize * 0.95}px`;
    });
  }
}

// Initialize SDK safely
if (window.elementSdk) {
  window.elementSdk.init({
    defaultConfig,
    onConfigChange: async (config) => applyConfig(config),
    mapToCapabilities: (config) => ({
      recolorables: [
        {
          get: () => config.background_color || defaultConfig.background_color,
          set: (v) => {
            config.background_color = v;
            window.elementSdk.setConfig({ background_color: v });
          },
        },
        {
          get: () => config.surface_color || defaultConfig.surface_color,
          set: (v) => {
            config.surface_color = v;
            window.elementSdk.setConfig({ surface_color: v });
          },
        },
        {
          get: () => config.text_color || defaultConfig.text_color,
          set: (v) => {
            config.text_color = v;
            window.elementSdk.setConfig({ text_color: v });
          },
        },
        {
          get: () => config.accent_color || defaultConfig.accent_color,
          set: (v) => {
            config.accent_color = v;
            window.elementSdk.setConfig({ accent_color: v });
          },
        },
        {
          get: () => config.muted_color || defaultConfig.muted_color,
          set: (v) => {
            config.muted_color = v;
            window.elementSdk.setConfig({ muted_color: v });
          },
        },
      ],
      borderables: [],
      fontEditable: {
        get: () => config.font_family || defaultConfig.font_family,
        set: (v) => {
          config.font_family = v;
          window.elementSdk.setConfig({ font_family: v });
        },
      },
      fontSizeable: {
        get: () => config.font_size || defaultConfig.font_size,
        set: (v) => {
          config.font_size = v;
          window.elementSdk.setConfig({ font_size: v });
        },
      },
    }),
    mapToEditPanelValues: (config) =>
      new Map([["page_title", config.page_title || defaultConfig.page_title]]),
  });
}
