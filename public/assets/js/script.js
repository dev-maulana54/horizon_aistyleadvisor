// ========== GLOBAL VARIABLES ==========
let wardrobeItems = [];
let wardrobeImages = {};
let currentWardrobeCategory = "shirts";
let currentWardrobePreview = null;
let currentPage = "home";
let isDarkMode = false;
let currentTheme = "maroon";
let uploadedImage = null;

// Chat history data (enhanced)
let chatHistories = [
  {
    id: 1,
    title: "Rekomendasi kondangan",
    preview: "Outfit formal untuk acara...",
    updatedAt: Date.now() - 100000,
  },
  {
    id: 2,
    title: "Outfit untuk kuliah besok",
    preview: "Smart casual untuk kuliah...",
    updatedAt: Date.now() - 90000,
  },
  {
    id: 3,
    title: "Cari warna cocok",
    preview: "Kombinasi warna navy...",
    updatedAt: Date.now() - 80000,
  },
  {
    id: 4,
    title: "Tips interview kerja",
    preview: "Professional outfit untuk...",
    updatedAt: Date.now() - 70000,
  },
];
let selectedHistory = null;
let chatMessagesByHistory = {}; // { [id]: [{role:'user'|'ai', text, image, ts}] }

let currentModalImages = [];
let currentModalImageIndex = 0;
let wardrobePreviewList = [];

// Virtual Try On
let tryOnPersonImage = null;
let tryOnOutfitImage = null;

// ========== EARLY FUNCTION DEFINITIONS (before HTML runs) ==========
function showPage(page) {
  if (page == "home") {
    window.location.href = baseUrl + "summary";
  } else if (page == "chat") {
    window.location.href = baseUrl + "ai";
  } else if (page == "settings") {
    window.location.href = baseUrl + "settings";
  } else if (page == "logout") {
    window.location.href = baseUrl + "user/logout";
  } else if (page == "tryon") {
    window.location.href = baseUrl + "ai_tryon";
  }
}

function updateNavStates() {
  // Desktop nav
  document.querySelectorAll(".nav-btn").forEach((btn) => {
    if (btn.dataset.page === currentPage) {
      btn.classList.add("bg-white/20");
    } else {
      btn.classList.remove("bg-white/20");
    }
  });

  // Mobile nav
  document.querySelectorAll(".mobile-nav-btn").forEach((btn) => {
    if (btn.dataset.page === currentPage) {
      btn.classList.add("primary-bg", "text-white");
    } else {
      btn.classList.remove("primary-bg", "text-white");
    }
  });
}

function showToast(message) {
  const toast = document.createElement("div");
  toast.className =
    "fixed bottom-20 lg:bottom-8 left-1/2 transform -translate-x-1/2 primary-bg text-white px-4 py-2 rounded-full text-sm z-50 fade-in";
  toast.textContent = message;
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 2000);
}

function switchSettingsTab(tab) {
  document.querySelectorAll(".settings-tab").forEach((el) => {
    el.classList.add("hidden");
  });

  const tabEl = document.getElementById(`${tab}-tab`);
  if (tabEl) {
    tabEl.classList.remove("hidden");
  }

  document.querySelectorAll(".settings-tab-btn").forEach((btn) => {
    btn.classList.remove("primary-text", "border-b-current");
    btn.classList.add("border-transparent");
  });

  const btnEl = document.querySelector(`[data-tab="${tab}"]`);
  if (btnEl) {
    btnEl.classList.add("primary-text", "border-b-current");
  }
}

// ========== DATA SDK INITIALIZATION ==========
const dataHandler = {
  onDataChanged(data) {
    wardrobeItems = data;
    renderWardrobeItems();
    refreshTryOnWardrobeSelect();
  },
};

async function initializeDataSDK() {
  if (!window.dataSdk) return;
  const result = await window.dataSdk.init(dataHandler);
  if (result.isOk) {
    console.log("Data SDK initialized");
  }
}

// Call on load
initializeDataSDK();

// ========== DEFAULT CONFIGURATION ==========
const defaultConfig = {
  user_name: "Maulana Saepul Akbar",
  greeting_text: "👋 Apa kabar?",
  primary_color: "#7B1E2B",
  background_color: "#FDF8F8",
  card_color: "#FFFFFF",
  text_color: "#2D2D2D",
  text_muted_color: "#6B6B6B",
};

// Initialize Element SDK
if (window.elementSdk) {
  window.elementSdk.init({
    defaultConfig,
    onConfigChange: async (config) => {
      // Update user name displays
      const userName = config.user_name || defaultConfig.user_name;
      document.getElementById("username-display").textContent = userName;
      document.getElementById("sidebar-username").textContent = userName;
      document.getElementById("settings-username").textContent = userName;

      // Update greeting
      const greeting = config.greeting_text || defaultConfig.greeting_text;
      document.getElementById("greeting-display").textContent = greeting;

      // Update colors
      applyColors(config);
    },
    mapToCapabilities: (config) => ({
      recolorables: [
        {
          get: () => config.background_color || defaultConfig.background_color,
          set: (value) =>
            window.elementSdk.setConfig({
              background_color: value,
            }),
        },
        {
          get: () => config.card_color || defaultConfig.card_color,
          set: (value) =>
            window.elementSdk.setConfig({
              card_color: value,
            }),
        },
        {
          get: () => config.text_color || defaultConfig.text_color,
          set: (value) =>
            window.elementSdk.setConfig({
              text_color: value,
            }),
        },
        {
          get: () => config.primary_color || defaultConfig.primary_color,
          set: (value) =>
            window.elementSdk.setConfig({
              primary_color: value,
            }),
        },
        {
          get: () => config.text_muted_color || defaultConfig.text_muted_color,
          set: (value) =>
            window.elementSdk.setConfig({
              text_muted_color: value,
            }),
        },
      ],
      borderables: [],
      fontEditable: undefined,
      fontSizeable: undefined,
    }),
    mapToEditPanelValues: (config) =>
      new Map([
        ["user_name", config.user_name || defaultConfig.user_name],
        ["greeting_text", config.greeting_text || defaultConfig.greeting_text],
      ]),
  });
}

function applyColors(config) {
  const root = document.documentElement;
  if (config.primary_color) {
    root.style.setProperty("--primary", config.primary_color);
  }
}

// ========== AI FEATURES NAV (Requirement #4) ==========
function openFeature(featureKey) {
  const meta = getFeatureMeta(featureKey);
  if (featureKey === "tryon") {
    showPage("tryon");
    return;
  }
  // Default: open feature detail page
  document.getElementById("feature-title").textContent = meta.title;
  document.getElementById("feature-subtitle").textContent = meta.subtitle;
  document.getElementById("feature-desc").textContent = meta.desc;
  showPage("feature");
}

function getFeatureMeta(key) {
  const map = {
    tryon: {
      title: "AI Virtual Try On",
      subtitle: "Coba outfit secara virtual",
      desc: "Upload foto kamu dan outfit untuk melihat preview try-on. Demo ini menggunakan compositing sederhana.",
    },
    mixmatch: {
      title: "Mix & Match Generator",
      subtitle: "Bikin kombinasi outfit",
      desc: "Buat kombinasi outfit dari item wardrobe kamu. Kamu bisa lanjutkan di Chat untuk rekomendasi detail.",
    },
    minmax: {
      title: "Min-Max Wardrobe",
      subtitle: "Optimasi isi lemari",
      desc: "Pilih item yang paling fleksibel dipakai di banyak acara. Chat bisa bantu analisis lebih personal.",
    },
    dresscode: {
      title: "Dress Code Helper",
      subtitle: "Cocokkan outfit dengan acara",
      desc: "Tentukan dress code dari event kamu (formal, semi-formal, casual) lalu dapatkan saran outfit.",
    },
    weather: {
      title: "Weather Suggestion",
      subtitle: "Outfit sesuai cuaca",
      desc: "Rekomendasi outfit berdasarkan cuaca dan aktivitas. Untuk hasil yang lebih akurat, tulis lokasi & jam di Chat.",
    },
  };
  return (
    map[key] || {
      title: "AI Feature",
      subtitle: "Detail",
      desc: "Detail fitur.",
    }
  );
}

// ========== WARDROBE MANAGEMENT ==========
function switchWardrobeCategory(category, el) {
  $(".wardrobe-category-btn").removeClass("active");
  $(el).addClass("active");

  // lanjutkan logic lama kamu di sini
  $("#category-name").text($(el).text().trim());
  $("#category-empty").text($(el).text().trim().toLowerCase());
}

// Wardrobe Image Upload - Multiple (Max 5)
function handleWardrobeImageUpload(event) {
  const files = event.target.files;
  if (!files || files.length === 0) return;

  const totalAfterUpload = wardrobePreviewList.length + files.length;
  if (totalAfterUpload > 5) {
    showToast(
      `⚠️ Maximum 5 images. You can add ${5 - wardrobePreviewList.length} more.`,
    );
    return;
  }

  for (let file of files) {
    if (wardrobePreviewList.length >= 5) break;

    const reader = new FileReader();
    reader.onload = (e) => {
      wardrobePreviewList.push(e.target.result);
      renderWardrobePreviewGrid();
    };
    reader.readAsDataURL(file);
  }
}

function renderWardrobePreviewGrid() {
  const container = document.getElementById("wardrobe-preview");
  container.innerHTML = "";

  wardrobePreviewList.forEach((preview, index) => {
    const previewDiv = document.createElement("div");
    previewDiv.className = "relative inline-block mr-2 mb-2";
    previewDiv.innerHTML = `
          <img src="${preview}" alt="Preview ${index + 1}" class="h-20 rounded-lg object-contain bg-black/5 border">
          <button type="button" onclick="removeWardrobePreview(${index})" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-red-600">
            ×
          </button>
        `;
    container.appendChild(previewDiv);
  });

  if (wardrobePreviewList.length > 0) {
    container.classList.remove("hidden");
  }
}

function removeWardrobePreview(index) {
  wardrobePreviewList.splice(index, 1);
  renderWardrobePreviewGrid();
  if (wardrobePreviewList.length === 0) {
    document.getElementById("wardrobe-preview").classList.add("hidden");
    document.getElementById("wardrobe-upload").value = "";
  }
}

function clearWardrobePreview() {
  wardrobePreviewList = [];
  document.getElementById("wardrobe-preview").classList.add("hidden");
  document.getElementById("wardrobe-upload").value = "";
}

// Add Wardrobe Item
async function addWardrobeItem() {
  const name = document.getElementById("item-name").value.trim();
  const desc = document.getElementById("item-desc").value.trim();

  if (!name) {
    showToast("Please enter item name");
    return;
  }

  if (wardrobePreviewList.length === 0) {
    showToast("Please upload at least 1 image");
    return;
  }

  if (wardrobeItems.length >= 999) {
    showToast("Maximum 999 items reached!");
    return;
  }

  // Get button reference properly
  const btn = document.querySelector(
    '#wardrobe-tab .add-item-section button[onclick="addWardrobeItem()"]',
  );
  if (btn) {
    btn.disabled = true;
    btn.textContent = "Adding...";
  }

  // Store images locally and create items with image index reference
  let successCount = 0;
  for (let i = 0; i < wardrobePreviewList.length; i++) {
    const imageUrl = wardrobePreviewList[i];
    const imageId = Date.now().toString() + "_" + i;

    // Store image locally
    wardrobeImages[imageId] = imageUrl;

    const newItem = {
      id: imageId,
      category: currentWardrobeCategory,
      itemName: name,
      description: desc,
      imageIndex: i, // Just store the index, not the actual image
      addedDate: new Date().toISOString(),
    };

    if (window.dataSdk) {
      const result = await window.dataSdk.create(newItem);
      if (result.isOk) {
        successCount++;
      } else {
        console.error("Failed to create item:", result.error);
      }
    } else {
      console.error("Data SDK not available");
    }
  }

  if (successCount > 0) {
    document.getElementById("item-name").value = "";
    document.getElementById("item-desc").value = "";
    clearWardrobePreview();
    showToast(`✅ ${successCount} item(s) added successfully!`);
  } else {
    showToast("❌ Failed to add items. Check console.");
  }

  if (btn) {
    btn.disabled = false;
    btn.textContent = "Add Item";
  }
}

// Render Wardrobe Items
function renderWardrobeItems() {
  const container = document.getElementById("wardrobe-items");
  const emptyState = document.getElementById("empty-wardrobe");
  const filtered = wardrobeItems.filter(
    (item) => item.category === currentWardrobeCategory,
  );

  if (filtered.length === 0) {
    container.innerHTML = "";
    emptyState.classList.remove("hidden");
    return;
  }

  emptyState.classList.add("hidden");

  container.innerHTML = filtered
    .map((item) => {
      // Get the actual image from local storage
      const imageUrl =
        wardrobeImages[item.id] ||
        "https://via.placeholder.com/200x200?text=Image+Unavailable";

      return `
        <div class="card-bg rounded-2xl overflow-hidden shadow-md hover:shadow-lg transition-shadow group flex flex-col h-full">
          <div class="relative flex-1 overflow-hidden bg-gray-200">
            <img src="${imageUrl}" alt="${item.itemName}" class="w-full h-full object-cover group-hover:scale-105 transition-transform" loading="lazy" onerror="this.style.background='linear-gradient(135deg, #7B1E2B, #9B3545)'; this.alt='Item';">
            <button type="button" onclick="deleteWardrobeItem('${item.__backendId}')" class="absolute top-2 right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600" title="Delete">
              ×
            </button>
          </div>
          <div class="p-3">
            <h4 class="font-semibold text-sm mb-1 truncate">${item.itemName}</h4>
            ${item.description ? `<p class="text-xs text-muted line-clamp-2 mb-2">${item.description}</p>` : ""}
            <span class="text-xs text-muted">${new Date(item.addedDate).toLocaleDateString()}</span>
          </div>
        </div>
      `;
    })
    .join("");
}

// Delete Wardrobe Item
async function deleteWardrobeItem(itemId) {
  const item = wardrobeItems.find((w) => w.__backendId === itemId);
  if (!item) return;

  const confirmed = confirm(`Delete "${item.itemName}"?`);
  if (!confirmed) return;

  if (window.dataSdk) {
    const result = await window.dataSdk.delete(item);
    if (result.isOk) {
      showToast("Item deleted");
    }
  }
}

// Update Body Shape UI
function updateBodyShapeUI() {
  document.querySelectorAll(".body-shape-option").forEach((label) => {
    const radio = label.querySelector('input[type="radio"]');
    if (radio.checked) {
      label.classList.add("scale-105");
    } else {
      label.classList.remove("scale-105");
    }
  });
}

// Update Style Preferences UI
function updateStylePrefUI() {
  document.querySelectorAll(".style-pref-option").forEach((label) => {
    const checkbox = label.querySelector('input[type="checkbox"]');
    if (checkbox.checked) {
      label.classList.add("scale-105");
    } else {
      label.classList.remove("scale-105");
    }
  });
}

// Update Personal Data
function updatePersonalData() {
  const bodyShape =
    document.querySelector('input[name="body_shape"]:checked')?.value || "";
  const stylePref = Array.from(
    document.querySelectorAll('input[name="style_pref"]:checked'),
  )
    .map((el) => el.value)
    .join(", ");
  const favColors = Array.from(
    document.querySelectorAll('input[name="fav_color"]:checked'),
  )
    .map((el) => el.value)
    .join(", ");

  showToast("Personal data saved ✓");
}

// ========== THEME & APPEARANCE ==========
function toggleDarkMode() {
  isDarkMode = !isDarkMode;
  const body = document.body;

  if (isDarkMode) {
    body.classList.remove("light-mode");
    body.classList.add("dark-mode");
  } else {
    body.classList.remove("dark-mode");
    body.classList.add("light-mode");
  }

  // Update toggle checkbox
  const toggle = document.getElementById("dark-mode-toggle");
  if (toggle) {
    toggle.checked = isDarkMode;
  }

  // Update icon
  updateThemeIcon();
}

function updateThemeIcon() {
  const iconMobile = document.getElementById("theme-icon-mobile");
  if (isDarkMode) {
    iconMobile.className = "fas fa-moon text-white";
  } else {
    iconMobile.className = "fas fa-sun text-white";
  }
}

// Theme Change
function changeTheme(theme) {
  currentTheme = theme;
  const body = document.body;

  body.classList.remove("theme-navy");

  if (theme === "navy") {
    body.classList.add("theme-navy");
  }

  // Update primary color in config
  const newPrimary = theme === "navy" ? "#1E3A5F" : "#7B1E2B";
  if (window.elementSdk) {
    window.elementSdk.setConfig({
      primary_color: newPrimary,
    });
  }
}

// Reset Preferences
function resetPreferences() {
  // Show inline confirmation
  const btn = event.target;
  const originalText = btn.textContent;
  btn.textContent = "Click again to confirm";
  btn.classList.add("bg-red-500", "text-white");

  btn.onclick = () => {
    // Reset dark mode
    if (isDarkMode) toggleDarkMode();

    // Reset theme
    changeTheme("maroon");
    document.querySelector('input[value="maroon"]').checked = true;

    // Reset notifications
    document
      .querySelectorAll('#appearance-tab input[type="checkbox"]')
      .forEach((cb) => {
        if (cb.id !== "dark-mode-toggle") cb.checked = true;
      });

    // Show success message
    btn.textContent = "✓ Reset Complete!";
    btn.classList.remove("bg-red-500");
    btn.classList.add("bg-green-500");

    setTimeout(() => {
      btn.textContent = originalText;
      btn.classList.remove("bg-green-500", "text-white");
      btn.onclick = resetPreferences;
    }, 2000);
  };

  // Reset after 3 seconds if not confirmed
  setTimeout(() => {
    if (btn.textContent === "Click again to confirm") {
      btn.textContent = originalText;
      btn.classList.remove("bg-red-500", "text-white");
      btn.onclick = resetPreferences;
    }
  }, 3000);
}

// ========== CHAT HISTORY (Requirement #3) ==========
function toggleHistoryDrawer(open) {
  const drawer = document.getElementById("history-drawer");
  if (!drawer) return;
  if (open) {
    drawer.classList.remove("hidden");
    // renderHistoryList(document.getElementById("history-search")?.value || "");
    // // sync inline search to drawer search
    // const inlineVal =
    //   document.getElementById("history-search-inline")?.value || "";
    // const hs = document.getElementById("history-search");
    // if (hs && inlineVal && !hs.value) {
    //   hs.value = inlineVal;
    //   renderHistoryList(hs.value);
    // }
  } else {
    drawer.classList.add("hidden");
  }
}

// function renderHistoryList(query = "") {
//   const q = (query || "").toLowerCase().trim();

//   // keep both search inputs in sync
//   const drawerInput = document.getElementById("history-search");
//   const inlineInput = document.getElementById("history-search-inline");
//   if (
//     drawerInput &&
//     drawerInput.value !== query &&
//     document.activeElement !== drawerInput
//   )
//     drawerInput.value = query;
//   if (
//     inlineInput &&
//     inlineInput.value !== query &&
//     document.activeElement !== inlineInput
//   )
//     inlineInput.value = query;

//   const list = document.getElementById("history-list");
//   if (!list) return;

//   const items = [...chatHistories]
//     .sort((a, b) => (b.updatedAt || 0) - (a.updatedAt || 0))
//     .filter((h) => {
//       if (!q) return true;
//       return (
//         (h.title || "").toLowerCase().includes(q) ||
//         (h.preview || "").toLowerCase().includes(q)
//       );
//     });

//   if (items.length === 0) {
//     list.innerHTML = `<div class="p-4 text-center text-sm text-muted">Tidak ada history yang cocok.</div>`;
//     return;
//   }

//   list.innerHTML = items
//     .map((h) => {
//       const active = selectedHistory && selectedHistory.id === h.id;
//       return `
//           <button type="button"
//             onclick="loadChatHistoryById(${h.id}); toggleHistoryDrawer(false);"
//             class="w-full text-left p-3 rounded-2xl border mb-2 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors ${active ? "primary-bg text-white border-transparent" : ""}">
//             <div class="flex items-start gap-3">
//               <div class="w-9 h-9 rounded-xl ${active ? "bg-white/20" : "primary-bg"} flex items-center justify-center flex-shrink-0">
//                 <i class="fas fa-message ${active ? "text-white" : "text-white"}"></i>
//               </div>
//               <div class="flex-1 min-w-0">
//                 <p class="font-semibold text-sm truncate">${escapeHtml(h.title || "Chat")}</p>
//                 <p class="text-xs ${active ? "text-white/80" : "text-muted"} line-clamp-2 mt-0.5">${escapeHtml(h.preview || "")}</p>
//               </div>
//             </div>
//           </button>
//         `;
//     })
//     .join("");
// }

function loadChatHistoryById(id) {
  const history = chatHistories.find((h) => h.id === id);
  if (!history) return;
  loadChatHistory(history);
}

function newChat() {
  window.history.pushState({}, "", "/ai/");
  // const newId = Date.now();
  // const history = {
  //   id: newId,
  //   title: "Chat baru",
  //   preview: "Mulai percakapan baru...",
  //   updatedAt: Date.now(),
  // };
  // chatHistories.unshift(history);
  // chatMessagesByHistory[newId] = [];

  // selectedHistory = history;
  // resetChatUIToHistory(history);
  // showToast("✨ New chat created");
  // renderHistoryList(
  //   document.getElementById("history-search-inline")?.value || "",
  // );
}

function resetChatUIToHistory(history) {
  const chatContainer = document.getElementById("chat-messages");
  chatContainer.innerHTML = "";

  // Welcome message for new/current history
  const welcomeMsg = document.createElement("div");
  welcomeMsg.className = "flex gap-3 fade-in";
  welcomeMsg.innerHTML = `
        <div class="w-8 h-8 primary-bg rounded-full flex-shrink-0 flex items-center justify-center">
         <svg class="w-4 h-4 text-white" fill="currentColor" viewbox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" /></svg>
        </div>
        <div class="chat-bubble-ai p-4 max-w-xs lg:max-w-md">
         <p class="text-sm">Halo! 👋 Saya AI Style Assistant. Mau kita bahas outfit untuk apa hari ini?</p>
        </div>
      `;
  chatContainer.appendChild(welcomeMsg);

  // Render saved messages if any
  const msgs = chatMessagesByHistory[history.id] || [];
  msgs.forEach((m) => appendMessageToUI(m.role, m.text, m.image, false));

  chatContainer.scrollTop = chatContainer.scrollHeight;
}

// ========== CHAT FUNCTIONS ==========
let currentSlug = null;
let currentIdRecents = null;

async function sendMessage() {
  const input = document.getElementById("chat-input");
  const message = input.value.trim();

  if (!message && !uploadedImage) return;

  if (!selectedHistory) {
    newChat();
  }

  const chatContainer = document.getElementById("chat-messages");

  // Tampilkan pesan user di UI
  appendMessageToUI("user", message, uploadedImage, true);
  updateHistoryOnUserMessage(message);

  // Clear input dan image
  input.value = "";
  removeImagePreview();
  chatContainer.scrollTop = chatContainer.scrollHeight;

  // Kirim ke backend CI4
  const formData = new FormData();
  formData.append("message", message);
  formData.append("id_recents", currentIdRecents ?? "");
  formData.append("slug", currentSlug ?? "");

  // Tampilkan typing indicator
  const typingDiv = document.createElement("div");
  typingDiv.className = "flex gap-3 fade-in typing-msg";
  typingDiv.innerHTML = `
        <div class="w-8 h-8 primary-bg rounded-full flex-shrink-0 flex items-center justify-center">
          <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
        </div>
        <div class="chat-bubble-ai p-4">
          <div class="typing-indicator text-primary">
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
          </div>
        </div>
    `;
  chatContainer.appendChild(typingDiv);
  chatContainer.scrollTop = chatContainer.scrollHeight;

  try {
    const res = await fetch("/ai/send", {
      method: "POST",
      body: formData,
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });

    const data = await res.json();

    if (!data.success) throw new Error("Gagal");

    // Jika chat baru, update URL tanpa reload
    if (!currentSlug) {
      currentSlug = data.slug;
      currentIdRecents = data.id_recents;
      window.history.pushState({}, "", data.redirect);
    }

    // Generate gambar outfit
    let images = generateOutfitImages(message);
    const imagesJSON = JSON.stringify(images).replace(/"/g, "&quot;");

    // Replace typing indicator dengan response AI
    const bubble = typingDiv.querySelector(".chat-bubble-ai");
    bubble.innerHTML = `
            <div class="grid grid-cols-3 gap-2 mb-3 w-full" id="outfit-grid">
              ${images
                .map(
                  (img, idx) => `
                <button type="button" onclick="openImageModal(${idx}, ${imagesJSON})" class="relative group w-full aspect-square rounded-lg overflow-hidden border-0 p-0 cursor-pointer hover:shadow-lg transition-all">
                  <img src="${img.url}" alt="${img.title}" class="rounded-lg w-full h-full object-contain bg-black/5 hover:opacity-90 transition-opacity" loading="lazy" onerror="this.style.background='linear-gradient(135deg, #7B1E2B, #9B3545)'; this.alt='Outfit';">
                  <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors rounded-lg flex items-end p-1.5">
                    <p class="text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity text-center w-full">${img.title}</p>
                  </div>
                </button>
              `,
                )
                .join("")}
            </div>
            <p class="text-sm mb-3">${data.reply}</p>
            <div class="flex gap-2 mt-3 pt-3 border-t border-gray-200">
              <button onclick="handleFeedback(this, 'like')" class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-xs text-muted" title="Helpful">
                <i class="fas fa-thumbs-up"></i>
                <span class="like-count">0</span>
              </button>
              <button onclick="handleFeedback(this, 'dislike')" class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-xs text-muted" title="Not helpful">
                <i class="fas fa-thumbs-down"></i>
                <span class="dislike-count">0</span>
              </button>
              <button onclick="handleShare(this)" class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-xs text-muted" title="Share">
                <i class="fas fa-share-alt"></i>
              </button>
            </div>
        `;

    chatContainer.scrollTop = chatContainer.scrollHeight;

    // Simpan ke local state
    if (selectedHistory) {
      if (!chatMessagesByHistory[currentIdRecents])
        chatMessagesByHistory[currentIdRecents] = [];
      chatMessagesByHistory[currentIdRecents].push({
        role: "ai",
        text: data.reply,
        image: null,
        ts: Date.now(),
      });

      const h = chatHistories.find((x) => x.id === selectedHistory.id);
      if (h) h.updatedAt = Date.now();
    }
  } catch (err) {
    const bubble = typingDiv.querySelector(".chat-bubble-ai");
    bubble.innerHTML = `<p class="text-sm text-red-500">Gagal mendapatkan respons. Silakan coba lagi.</p>`;
  }
}
// function sendMessage() {
//   const input = document.getElementById("chat-input");
//   const message = input.value.trim();

//   if (!message && !uploadedImage) return;

//   // Ensure there is a selected history
//   if (!selectedHistory) {
//     newChat();
//   }

//   const chatContainer = document.getElementById("chat-messages");

//   // Save & append user message
//   appendMessageToUI("user", message, uploadedImage, true);

//   // Update history title & preview on first message
//   updateHistoryOnUserMessage(message);

//   // Clear input and image
//   input.value = "";
//   removeImagePreview();

//   // Scroll to bottom
//   chatContainer.scrollTop = chatContainer.scrollHeight;

//   // Simulate AI response
//   setTimeout(() => {
//     addAIResponse(message);
//   }, 1000);
// }

function updateHistoryOnUserMessage(message) {
  if (!selectedHistory) return;

  const h = chatHistories.find((x) => x.id === selectedHistory.id);
  if (!h) return;

  const msg = (message || "").trim();
  if (msg && (h.title === "Chat baru" || !h.title)) {
    h.title = msg.length > 28 ? msg.slice(0, 28) + "…" : msg;
  }
  if (msg) {
    h.preview = msg.length > 40 ? msg.slice(0, 40) + "…" : msg;
  }
  h.updatedAt = Date.now();
  selectedHistory = h;
  // renderHistoryList(
  //   document.getElementById("history-search-inline")?.value || "",
  // );
}

function appendMessageToUI(role, text, image, save = true) {
  const chatContainer = document.getElementById("chat-messages");

  if (role === "user") {
    const userMsg = document.createElement("div");
    userMsg.className = "flex gap-3 justify-end fade-in";
    userMsg.innerHTML = `
          <div class="chat-bubble-user p-4 max-w-xs lg:max-w-md">
            ${image ? `<img src="${image}" alt="Uploaded outfit" class="rounded-lg mb-2 max-w-full object-contain bg-black/10">` : ""}
            ${text ? `<p class="text-sm">${escapeHtml(text)}</p>` : ""}
          </div>
        `;
    chatContainer.appendChild(userMsg);
  } else {
    // AI simple text fallback (not used for main response card)
    const aiMsg = document.createElement("div");
    aiMsg.className = "flex gap-3 fade-in";
    aiMsg.innerHTML = `
          <div class="w-8 h-8 primary-bg rounded-full flex-shrink-0 flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          </div>
          <div class="chat-bubble-ai p-4 max-w-xs lg:max-w-md">
            <p class="text-sm">${escapeHtml(text || "")}</p>
          </div>
        `;
    chatContainer.appendChild(aiMsg);
  }

  // Save
  if (save && selectedHistory) {
    if (!chatMessagesByHistory[selectedHistory.id])
      chatMessagesByHistory[selectedHistory.id] = [];
    chatMessagesByHistory[selectedHistory.id].push({
      role,
      text: text || "",
      image: image || null,
      ts: Date.now(),
    });
  }
}

function addAIResponse(userMessage) {
  const chatContainer = document.getElementById("chat-messages");

  // Create typing indicator
  const typingDiv = document.createElement("div");
  typingDiv.className = "flex gap-3 fade-in typing-msg";
  typingDiv.innerHTML = `
        <div class="w-8 h-8 primary-bg rounded-full flex-shrink-0 flex items-center justify-center">
          <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
        </div>
        <div class="chat-bubble-ai p-4">
          <div class="typing-indicator text-primary">
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
          </div>
        </div>
      `;
  chatContainer.appendChild(typingDiv);
  chatContainer.scrollTop = chatContainer.scrollHeight;

  // Simulate AI response delay
  setTimeout(
    () => {
      // Generate contextual outfit images
      let images = generateOutfitImages(userMessage);

      // Replace typing indicator with actual response
      const bubble = typingDiv.querySelector(".chat-bubble-ai");
      const imagesJSON = JSON.stringify(images).replace(/"/g, "&quot;");
      bubble.innerHTML = `
          <div class="grid grid-cols-3 gap-2 mb-3 w-full" id="outfit-grid">
            ${images
              .map(
                (img, idx) => `
              <button type="button" onclick="openImageModal(${idx}, ${imagesJSON})" class="relative group w-full aspect-square rounded-lg overflow-hidden border-0 p-0 cursor-pointer hover:shadow-lg transition-all">
                <img src="${img.url}" alt="${img.title}" class="rounded-lg w-full h-full object-contain bg-black/5 hover:opacity-90 transition-opacity" loading="lazy" onerror="this.style.background='linear-gradient(135deg, #7B1E2B, #9B3545)'; this.alt='Outfit';">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/40 transition-colors rounded-lg flex items-end p-1.5">
                  <p class="text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity text-center w-full">${img.title}</p>
                </div>
              </button>
            `,
              )
              .join("")}
          </div>
          <p class="text-sm mb-3">${getOutfitDescription(userMessage)}</p>
          <div class="flex gap-2 mt-3 pt-3 border-t border-gray-200">
            <button onclick="handleFeedback(this, 'like')" class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-xs text-muted" title="Helpful">
              <i class="fas fa-thumbs-up"></i>
              <span class="like-count">0</span>
            </button>
            <button onclick="handleFeedback(this, 'dislike')" class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-xs text-muted" title="Not helpful">
              <i class="fas fa-thumbs-down"></i>
              <span class="dislike-count">0</span>
            </button>
            <button onclick="handleShare(this)" class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-xs text-muted" title="Share">
              <i class="fas fa-share-alt"></i>
            </button>
          </div>
        `;

      chatContainer.scrollTop = chatContainer.scrollHeight;

      // Save AI message (lightweight)
      if (selectedHistory) {
        if (!chatMessagesByHistory[selectedHistory.id])
          chatMessagesByHistory[selectedHistory.id] = [];
        chatMessagesByHistory[selectedHistory.id].push({
          role: "ai",
          text: getOutfitDescription(userMessage),
          image: null,
          ts: Date.now(),
        });

        // Update history preview with AI response if user message empty
        const h = chatHistories.find((x) => x.id === selectedHistory.id);
        if (h) {
          h.updatedAt = Date.now();
        }
      }
    },
    1500 + Math.random() * 1000,
  );
}

function generateOutfitImages(message) {
  const lowerMsg = (message || "").toLowerCase();

  if (lowerMsg.includes("kondangan")) {
    return [
      {
        url: "https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=400&h=300&fit=crop",
        title: "Batik Formal",
      },
      {
        url: "https://images.unsplash.com/photo-1605734326315-fe1674b77a1f?w=400&h=300&fit=crop",
        title: "Pairing Formal",
      },
      {
        url: "https://images.unsplash.com/photo-1633113845063-8e0a8ed3d4e9?w=400&h=300&fit=crop",
        title: "Complete Look",
      },
    ];
  } else if (lowerMsg.includes("kuliah")) {
    return [
      {
        url: "https://images.unsplash.com/photo-1617137968427-85924c800a22?w=400&h=300&fit=crop",
        title: "Smart Casual Shirt",
      },
      {
        url: "https://images.unsplash.com/photo-1542272604-787c62d465d1?w=400&h=300&fit=crop",
        title: "Chino Pants",
      },
      {
        url: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=300&fit=crop",
        title: "Complete Outfit",
      },
    ];
  } else if (lowerMsg.includes("hangout") || lowerMsg.includes("jalan")) {
    return [
      {
        url: "https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?w=400&h=300&fit=crop",
        title: "Casual T-Shirt",
      },
      {
        url: "https://images.unsplash.com/photo-1542272604-787c62d465d1?w=400&h=300&fit=crop",
        title: "Jeans",
      },
      {
        url: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=300&fit=crop",
        title: "Casual Look",
      },
    ];
  } else if (lowerMsg.includes("interview") || lowerMsg.includes("kerja")) {
    return [
      {
        url: "https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=400&h=300&fit=crop",
        title: "Professional Blazer",
      },
      {
        url: "https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=400&h=300&fit=crop",
        title: "Dress Pants",
      },
      {
        url: "https://images.unsplash.com/photo-1633113845063-8e0a8ed3d4e9?w=400&h=300&fit=crop",
        title: "Interview Ready",
      },
    ];
  } else if (lowerMsg.includes("warna") || lowerMsg.includes("color")) {
    return [
      {
        url: "https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=400&h=300&fit=crop",
        title: "Navy Combination",
      },
      {
        url: "https://images.unsplash.com/photo-1617137968427-85924c800a22?w=400&h=300&fit=crop",
        title: "Neutral Palette",
      },
      {
        url: "https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?w=400&h=300&fit=crop",
        title: "Warm Tones",
      },
    ];
  } else {
    return [
      {
        url: "https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=400&h=300&fit=crop",
        title: "Style Option 1",
      },
      {
        url: "https://images.unsplash.com/photo-1617137968427-85924c800a22?w=400&h=300&fit=crop",
        title: "Style Option 2",
      },
      {
        url: "https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?w=400&h=300&fit=crop",
        title: "Style Option 3",
      },
    ];
  }
}

function getOutfitDescription(message) {
  const lowerMsg = (message || "").toLowerCase();

  if (lowerMsg.includes("kondangan")) {
    return "👔 Tiga pilihan outfit formal untuk kondangan. Pilih batik dengan motif klasik dan kombinasikan dengan celana bahan untuk tampilan yang elegan dan berkesan!";
  } else if (lowerMsg.includes("kuliah")) {
    return "📚 Smart casual outfit yang nyaman untuk kuliah. Kemeja, chino pants, dan sneakers adalah kombinasi sempurna untuk tampilan yang stylish dan praktis!";
  } else if (lowerMsg.includes("hangout") || lowerMsg.includes("jalan")) {
    return "😎 Casual outfit perfect untuk hangout dengan teman-teman. Simpel tapi stylish dengan t-shirt, jeans, dan sneakers favorit kamu!";
  } else if (lowerMsg.includes("interview") || lowerMsg.includes("kerja")) {
    return "💼 Professional outfit untuk interview atau meeting kerja. Blazer, dress pants, dan sepatu formal menciptakan kesan profesional yang kuat!";
  } else if (lowerMsg.includes("warna") || lowerMsg.includes("color")) {
    return "🎨 Kombinasi warna yang cocok untuk berbagai situasi. Dari navy, neutral, hingga warm tones - semua sempurna untuk outfit kamu!";
  } else {
    return "✨ Beberapa pilihan outfit gaya untuk inspirasi kamu. Tanya lebih spesifik untuk rekomendasi yang lebih tepat!";
  }
}

function handleFeedback(button, type) {
  const isLike = type === "like";
  const likeBtn = button.parentElement.querySelector("button:nth-child(1)");
  const dislikeBtn = button.parentElement.querySelector("button:nth-child(2)");

  if (isLike) {
    likeBtn.classList.add("primary-bg", "text-white");
    dislikeBtn.classList.remove("primary-bg", "text-white");
  } else {
    dislikeBtn.classList.add("primary-bg", "text-white");
    likeBtn.classList.remove("primary-bg", "text-white");
  }

  // Show brief feedback message
  const feedbackMsg = isLike
    ? "Terima kasih! Senang bisa membantu 😊"
    : "Kami akan improve! Terima kasih feedback-nya 💪";
  showToast(feedbackMsg);
}

function handleShare(button) {
  const responseText = button
    .closest(".chat-bubble-ai")
    .querySelector("p").textContent;

  // Show inline share options
  const shareMenu = document.createElement("div");
  shareMenu.className =
    "absolute bg-white dark:bg-gray-800 rounded-lg shadow-lg p-2 z-50 text-xs";
  shareMenu.style.position = "fixed";
  shareMenu.style.right = "20px";
  shareMenu.style.top = button.getBoundingClientRect().top + "px";

  shareMenu.innerHTML = `
        <button onclick="copyToClipboard('${responseText.replace(/'/g, "\\'")}', this)" class="block w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors">
          📋 Copy
        </button>
        <button onclick="shareVia('whatsapp', '${responseText.replace(/'/g, "\\'")}', this)" class="block w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors">
          💬 WhatsApp
        </button>
        <button onclick="shareVia('twitter', '${responseText.replace(/'/g, "\\'")}', this)" class="block w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded transition-colors">
          𝕏 Twitter
        </button>
      `;

  document.body.appendChild(shareMenu);

  // Close menu when clicking elsewhere
  setTimeout(() => {
    document.addEventListener("click", function closeMenu(e) {
      if (!shareMenu.contains(e.target) && e.target !== button) {
        shareMenu.remove();
        document.removeEventListener("click", closeMenu);
      }
    });
  }, 100);
}

function copyToClipboard(text, btn) {
  navigator.clipboard.writeText(text).then(() => {
    const originalText = btn.textContent;
    btn.textContent = "✓ Copied!";
    setTimeout(() => {
      btn.textContent = originalText;
    }, 2000);
    const menu = document.querySelector(".absolute");
    if (menu) menu.remove();
  });
}

function shareVia(platform, text, btn) {
  const message = `${text}\n\n- dari AI Style Advisor`;
  let url = "";

  if (platform === "whatsapp") {
    url = `https://wa.me/?text=${encodeURIComponent(message)}`;
  } else if (platform === "twitter") {
    url = `https://twitter.com/intent/tweet?text=${encodeURIComponent(message)}`;
  }

  if (url) {
    window.open(url, "_blank", "noopener,noreferrer");
    showToast("Opened in new tab!");
    const menu = document.querySelector(".absolute");
    if (menu) menu.remove();
  }
}

function sendQuickReply(message) {
  showPage("chat");
  document.getElementById("chat-input").value = message;
  sendMessage();
}

function handleImageUpload(event) {
  const file = event.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      uploadedImage = e.target.result;
      document.getElementById("preview-img").src = uploadedImage;
      document.getElementById("image-preview").classList.remove("hidden");
    };
    reader.readAsDataURL(file);
  }
}

function removeImagePreview() {
  uploadedImage = null;
  document.getElementById("image-preview").classList.add("hidden");
  document.getElementById("preview-img").src = "";
}

function escapeHtml(text) {
  const div = document.createElement("div");
  div.textContent = text ?? "";
  return div.innerHTML;
}

function loadChatHistory(history) {
  selectedHistory = history;

  // Render UI for that history
  resetChatUIToHistory(history);

  // If no saved messages, show the small "title card" then simulate response
  const msgs = chatMessagesByHistory[history.id] || [];
  if (msgs.length === 0) {
    const chatContainer = document.getElementById("chat-messages");

    const titleMsg = document.createElement("div");
    titleMsg.className = "flex gap-3 fade-in";
    titleMsg.innerHTML = `
          <div class="w-8 h-8 primary-bg rounded-full flex-shrink-0 flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          </div>
          <div class="chat-bubble-ai p-4 max-w-xs lg:max-w-md">
            <p class="text-sm"><b>${escapeHtml(history.title || "")}</b></p>
            <p class="text-xs text-muted mt-1">${escapeHtml(history.preview || "")}</p>
          </div>
        `;
    chatContainer.appendChild(titleMsg);

    setTimeout(() => {
      addAIResponse(history.title);
    }, 500);
  }

  // renderHistoryList(
  //   document.getElementById("history-search-inline")?.value || "",
  // );
}

function handleKeyDown(event) {
  if (event.key === "Enter" && !event.shiftKey) {
    event.preventDefault();
    sendMessage();
  }
}

// ========== AI VIRTUAL TRY ON (Requirement #1) ==========
function handleTryOnUpload(event, type) {
  const file = event.target.files && event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = (e) => {
    const dataUrl = e.target.result;

    if (type === "person") {
      tryOnPersonImage = dataUrl;
      const img = document.getElementById("tryon-person-preview");
      img.src = dataUrl;
      img.classList.remove("hidden");
      document
        .getElementById("tryon-person-placeholder")
        .classList.add("hidden");
      document.getElementById("tryon-person-status").textContent = "Uploaded ✓";
    }

    if (type === "outfit") {
      tryOnOutfitImage = dataUrl;
      const img = document.getElementById("tryon-outfit-preview");
      img.src = dataUrl;
      img.classList.remove("hidden");
      document
        .getElementById("tryon-outfit-placeholder")
        .classList.add("hidden");
      document.getElementById("tryon-outfit-status").textContent = "Uploaded ✓";

      // reset wardrobe select to avoid confusion
      const sel = document.getElementById("tryon-wardrobe-select");
      if (sel) sel.value = "";
    }
  };
  reader.readAsDataURL(file);
}

function refreshTryOnWardrobeSelect() {
  const sel = document.getElementById("tryon-wardrobe-select");
  if (!sel) return;

  const currentVal = sel.value;
  sel.innerHTML = `<option value="">-- Pilih item wardrobe --</option>`;

  // Build unique wardrobe entries (per item id)
  const items = (wardrobeItems || []).slice().sort((a, b) => {
    const da = new Date(a.addedDate || 0).getTime();
    const db = new Date(b.addedDate || 0).getTime();
    return db - da;
  });

  items.forEach((item) => {
    const label = `${item.itemName || "Item"} (${item.category || "-"})`;
    const opt = document.createElement("option");
    opt.value = item.id;
    opt.textContent = label;
    sel.appendChild(opt);
  });

  if (currentVal) sel.value = currentVal;
}

function selectOutfitFromWardrobe() {
  const sel = document.getElementById("tryon-wardrobe-select");
  if (!sel) return;
  const id = sel.value;
  if (!id) return;

  const imageUrl = wardrobeImages[id];
  if (!imageUrl) {
    showToast("⚠️ Gambar wardrobe belum tersedia (upload ulang jika perlu).");
    return;
  }

  tryOnOutfitImage = imageUrl;
  const img = document.getElementById("tryon-outfit-preview");
  img.src = imageUrl;
  img.classList.remove("hidden");
  document.getElementById("tryon-outfit-placeholder").classList.add("hidden");
  document.getElementById("tryon-outfit-status").textContent =
    "From Wardrobe ✓";

  // clear file input
  const input = document.getElementById("tryon-outfit-input");
  if (input) input.value = "";
}

function resetTryOn() {
  tryOnPersonImage = null;
  tryOnOutfitImage = null;

  const pImg = document.getElementById("tryon-person-preview");
  const oImg = document.getElementById("tryon-outfit-preview");
  const rImg = document.getElementById("tryon-result");

  if (pImg) {
    pImg.src = "";
    pImg.classList.add("hidden");
  }
  if (oImg) {
    oImg.src = "";
    oImg.classList.add("hidden");
  }
  if (rImg) {
    rImg.src = "";
    rImg.classList.add("hidden");
  }

  document
    .getElementById("tryon-person-placeholder")
    ?.classList.remove("hidden");
  document
    .getElementById("tryon-outfit-placeholder")
    ?.classList.remove("hidden");
  document
    .getElementById("tryon-result-placeholder")
    ?.classList.remove("hidden");

  document.getElementById("tryon-person-status").textContent = "Belum ada";
  document.getElementById("tryon-outfit-status").textContent = "Belum ada";

  const pi = document.getElementById("tryon-person-input");
  const oi = document.getElementById("tryon-outfit-input");
  const sel = document.getElementById("tryon-wardrobe-select");
  if (pi) pi.value = "";
  if (oi) oi.value = "";
  if (sel) sel.value = "";

  showToast("Reset ✓");
}

function generateTryOn() {
  if (!tryOnPersonImage || !tryOnOutfitImage) {
    showToast("⚠️ Upload foto kamu & outfit dulu ya.");
    return;
  }

  // Simple compositing demo via canvas (fit, not cropped)
  const canvas = document.createElement("canvas");
  const ctx = canvas.getContext("2d");

  // Portrait canvas to keep result consistent
  canvas.width = 768;
  canvas.height = 1024;

  const base = new Image();
  const outfit = new Image();

  base.onload = () => {
    outfit.onload = () => {
      // background
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.fillStyle = "rgba(255,255,255,0)";
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      // draw base as contain (fit)
      drawContain(ctx, base, 0, 0, canvas.width, canvas.height, 1);

      // draw outfit as contain (fit) with some opacity
      // slight scale down so it sits inside
      const pad = 48;
      drawContain(
        ctx,
        outfit,
        pad,
        pad,
        canvas.width - pad * 2,
        canvas.height - pad * 2,
        0.82,
      );

      const outUrl = canvas.toDataURL("image/png");
      const rImg = document.getElementById("tryon-result");
      rImg.src = outUrl;
      rImg.classList.remove("hidden");
      document
        .getElementById("tryon-result-placeholder")
        .classList.add("hidden");

      showToast("✨ Try on generated!");
    };
    outfit.onerror = () => showToast("❌ Outfit image error");
    outfit.src = tryOnOutfitImage;
  };
  base.onerror = () => showToast("❌ Person image error");
  base.src = tryOnPersonImage;
}

function drawContain(ctx, img, x, y, w, h, alpha = 1) {
  const iw = img.naturalWidth || img.width;
  const ih = img.naturalHeight || img.height;
  if (!iw || !ih) return;

  const scale = Math.min(w / iw, h / ih);
  const dw = iw * scale;
  const dh = ih * scale;
  const dx = x + (w - dw) / 2;
  const dy = y + (h - dh) / 2;

  ctx.save();
  ctx.globalAlpha = alpha;
  ctx.imageSmoothingEnabled = true;
  ctx.imageSmoothingQuality = "high";
  ctx.drawImage(img, dx, dy, dw, dh);
  ctx.restore();
}

// ========== IMAGE MODAL & PREVIEW ==========
function openImageModal(index, imagesArray) {
  try {
    if (typeof imagesArray === "string") {
      currentModalImages = JSON.parse(imagesArray.replace(/\\'/g, "'"));
    } else {
      currentModalImages = imagesArray;
    }
    currentModalImageIndex = index;
    displayModalImage();
    document.getElementById("image-modal").classList.remove("hidden");
  } catch (err) {
    console.error("Error opening modal:", err);
  }
}

function closeImageModal() {
  document.getElementById("image-modal").classList.add("hidden");
}

function displayModalImage() {
  if (currentModalImages.length === 0) return;

  const image = currentModalImages[currentModalImageIndex];
  document.getElementById("modal-image").src = image.url;
  document.getElementById("modal-title").textContent = image.title;
  document.getElementById("modal-description").textContent =
    `Image ${currentModalImageIndex + 1} of ${currentModalImages.length}`;
  document.getElementById("image-counter").textContent =
    `${currentModalImageIndex + 1} / ${currentModalImages.length}`;

  document.getElementById("prev-image-btn").disabled =
    currentModalImageIndex === 0;
  document.getElementById("next-image-btn").disabled =
    currentModalImageIndex === currentModalImages.length - 1;
}

function nextImage() {
  if (currentModalImageIndex < currentModalImages.length - 1) {
    currentModalImageIndex++;
    displayModalImage();
  }
}

function previousImage() {
  if (currentModalImageIndex > 0) {
    currentModalImageIndex--;
    displayModalImage();
  }
}

// Keyboard navigation for modal
document.addEventListener("keydown", (e) => {
  const modal = document.getElementById("image-modal");
  if (modal.classList.contains("hidden")) return;

  if (e.key === "ArrowRight") nextImage();
  if (e.key === "ArrowLeft") previousImage();
  if (e.key === "Escape") closeImageModal();
});

// Carousel drag functionality with ultra-smooth momentum
const carousels = document.querySelectorAll(".carousel-container");
carousels.forEach((carousel) => {
  let isDown = false;
  let startX = 0;
  let scrollLeft = 0;
  let animationId = null;
  let velocityTracker = [];
  let lastScrollTime = 0;

  function getVelocity() {
    if (velocityTracker.length === 0) return 0;

    let totalVelocity = 0;
    let totalWeight = 0;

    velocityTracker.forEach((vel, index) => {
      const weight = (index + 1) / velocityTracker.length;
      totalVelocity += vel * weight;
      totalWeight += weight;
    });

    return totalVelocity / totalWeight;
  }

  carousel.addEventListener("mousedown", (e) => {
    isDown = true;
    carousel.classList.add("grabbing");
    startX = e.pageX;
    scrollLeft = carousel.scrollLeft;
    velocityTracker = [];
    lastScrollTime = performance.now();

    if (animationId) {
      cancelAnimationFrame(animationId);
    }
  });

  carousel.addEventListener("mouseleave", () => {
    if (isDown) {
      isDown = false;
      carousel.classList.remove("grabbing");
      applyMomentum();
    }
  });

  carousel.addEventListener("mouseup", () => {
    if (isDown) {
      isDown = false;
      carousel.classList.remove("grabbing");
      applyMomentum();
    }
  });

  carousel.addEventListener("mousemove", (e) => {
    if (!isDown) return;

    const now = performance.now();
    const walk = e.pageX - startX;
    const newScrollLeft = scrollLeft - walk;

    carousel.scrollLeft = newScrollLeft;

    const timeDelta = Math.max(now - lastScrollTime, 1);
    const velocity = (-(e.pageX - startX) / timeDelta) * 10;

    velocityTracker.push(velocity);

    if (velocityTracker.length > 5) {
      velocityTracker.shift();
    }

    lastScrollTime = now;
  });

  function applyMomentum() {
    let velocity = getVelocity();
    if (Math.abs(velocity) < 0.5) return;

    const startTime = performance.now();
    const duration = 1500;

    const momentum = (currentTime) => {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);

      const decay = Math.pow(1 - progress, 2.5);
      const currentVelocity = velocity * decay;

      carousel.scrollLeft += currentVelocity;

      if (progress < 1) {
        animationId = requestAnimationFrame(momentum);
      } else {
        animationId = null;
      }
    };

    animationId = requestAnimationFrame(momentum);
  }
});

// Initialize
window.addEventListener("load", () => {
  // showPage("home");
  // updateNavStates();
  const profileTabEl = document.getElementById("profile-tab");
  if (profileTabEl) {
    switchSettingsTab("profile");
  }
  // Prepare default selected history
  selectedHistory = chatHistories[0] || null;
  if (selectedHistory) {
    chatMessagesByHistory[selectedHistory.id] =
      chatMessagesByHistory[selectedHistory.id] || [];
  }
  // renderHistoryList("");
  refreshTryOnWardrobeSelect();
});

$(".add-wardrobe-item").click(function () {
  var itemName = $("#item-name").val().trim();

  var files = $("#wardrobe-upload")[0].files;
  var idJenisWardrobe = $(".wardrobe-category-btn.active").data("id");
  if (!itemName) {
    showToast("Please enter item name");
    return;
  }

  if (files.length === 0) {
    showToast("Please upload at least 1 image");
    return;
  }

  if (files.length > 5) {
    showToast("Max upload 5 images");
    return;
  }

  var formData = new FormData();
  formData.append("nama_item", itemName);
  formData.append("id_jenis_wardrobe", idJenisWardrobe);

  for (let i = 0; i < files.length; i++) {
    formData.append("wardrobe_images[]", files[i]); // ← fix utama
  }

  $.ajax({
    url: baseUrl + "settings/saveWardrobe",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    beforeSend: function () {
      $(".add-wardrobe-item").prop("disabled", true);
    },
    success: function (response) {
      $(".add-wardrobe-item").prop("disabled", false);

      if (response.status == true) {
        showToast(response.message);

        location.reload();
        console.log(response.data);
      } else {
        showToast(response.message);
      }
    },
    error: function (xhr) {
      $(".add-wardrobe-item").prop("disabled", false);
      showToast("Terjadi kesalahan server");
      console.log(xhr.responseText);
    },
  });
});
