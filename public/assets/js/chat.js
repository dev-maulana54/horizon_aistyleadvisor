// =====================================================================
// KONFIGURASI
// =====================================================================
const getApiKey = () =>
  document.querySelector('meta[name="oai"]')?.content ?? "";
const getUserData = () => window.__USER_DATA__ ?? null;
const isLoggedIn = () => getUserData() !== null;

// =====================================================================
// FORMAT CONVERSATION JSON
// Struktur seragam untuk semua pesan (user & AI):
// { "text": "...", "images": [{ "url": "...", "type": "user"|"ai" }] }
// =====================================================================
function makeConversationPayload(text = "", images = []) {
  return JSON.stringify({ text, images });
}

function parseConversation(raw) {
  // Coba parse JSON dulu, fallback ke plain text (data lama)
  try {
    const parsed = JSON.parse(raw);
    if (typeof parsed === "object" && parsed !== null) return parsed;
  } catch {}
  return { text: raw, images: [] };
}

// =====================================================================
// MARKDOWN RENDERER
// =====================================================================
function renderMarkdown(text) {
  let t = (text ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;");

  // Kumpulkan semua list item jadi satu blok dulu sebelum convert
  // Hapus baris kosong di antara list item agar tidak ada gap
  t = t.replace(/((?:^[-•*]\s+.+$\n?)+)/gm, (block) => {
    const items = block
      .split("\n")
      .filter((l) => l.trim())
      .map(
        (l) =>
          `<li>${l
            .replace(/^[-•*]\s+/, "")
            .replace(/\*\*(.+?)\*\*/g, "<strong>$1</strong>")
            .replace(/\*(.+?)\*/g, "<em>$1</em>")}</li>`,
      )
      .join("");
    return `<ul class="space-y-1 my-1 pl-4">${items}</ul>`;
  });

  t = t.replace(/((?:^\d+\.\s+.+$\n?)+)/gm, (block) => {
    const items = block
      .split("\n")
      .filter((l) => l.trim())
      .map(
        (l) =>
          `<li>${l
            .replace(/^\d+\.\s+/, "")
            .replace(/\*\*(.+?)\*\*/g, "<strong>$1</strong>")
            .replace(/\*(.+?)\*/g, "<em>$1</em>")}</li>`,
      )
      .join("");
    return `<ol class="space-y-1 my-1 pl-4 list-decimal">${items}</ol>`;
  });

  // Bold & italic untuk teks biasa (di luar list)
  t = t.replace(/\*\*(.+?)\*\*/g, "<strong>$1</strong>");
  t = t.replace(/\*(.+?)\*/g, "<em>$1</em>");

  // Newline ganda → paragraf, newline tunggal → br (kecuali sudah jadi ul/ol)
  t = t.replace(/\n\n+/g, "</p><p class=\'mt-1\'>");
  t = t.replace(/\n(?!<\/?[uo]l)/g, "<br>");

  return t;
}

// =====================================================================
// SYSTEM PROMPT
// =====================================================================
function buildSystemPrompt() {
  const user = getUserData();

  if (user) {
    const wardrobe =
      (user.wardrobe ?? []).length > 0
        ? user.wardrobe
            .map((w) => `- ${w.nama_item} (${w.type_wardrobe})`)
            .join("\n")
        : "(belum ada item wardrobe)";
    const styles =
      (user.style_preferences ?? []).map((s) => s.style_name).join(", ") ||
      "belum diketahui";
    const bodyShape = user.body_shape ?? "belum diketahui";
    const isPremium = user.is_premium ? "Premium" : "Free";

    console.log("=== [USER DATA DEBUG] ===");
    console.log("Nama      :", user.nama);
    console.log("Body Shape:", bodyShape);
    console.log("Styles    :", styles);
    console.log("Wardrobe  :", user.wardrobe);
    console.log("isPremium :", isPremium);
    console.log("=========================");

    // Set gender global untuk DALL-E prompt
    if (!window.__USER_GENDER__) {
      if (user.gender) {
        // Gender dari profil user (jika ada kolom gender di DB)
        const g = String(user.gender).toLowerCase();
        window.__USER_GENDER__ =
          g === "female" || g === "wanita" || g === "perempuan"
            ? "female"
            : "male";
      } else {
        window.__USER_GENDER__ = "male"; // default
      }
    }
    console.log("[Gender]", window.__USER_GENDER__);

    // Build wardrobe image references untuk system prompt
    const wardrobeImages =
      (user.wardrobe ?? []).length > 0
        ? user.wardrobe
            .filter((w) => w.file_name)
            .map(
              (w) =>
                `- ${w.nama_item} (${w.type_wardrobe}): ${w.image_url ?? ""}`,
            )
            .join("\n") || "(tidak ada gambar)"
        : "(tidak ada gambar wardrobe)";

    const hasWardrobe = (user.wardrobe ?? []).length > 0;
    const hasWardrobeImages = (user.wardrobe ?? []).some((w) => w.image_url);
    // Coba deteksi gender dari nama/data (fallback ke "person")
    const genderHint = window.__USER_GENDER__ ?? "male"; // bisa di-set dari profil user

    return `Kamu adalah FitMatch AI — AI Style Expert personal untuk ${user.nama}.

DATA ${user.nama}:
- Body shape   : ${bodyShape}
- Style favorit: ${styles}
- Status akun  : ${isPremium}
- Wardrobe     : ${
      hasWardrobe
        ? `\n${wardrobe}\n\nTotal ${(user.wardrobe ?? []).length} item — lakukan MIX & MATCH dari semua item ini`
        : "belum ada item wardrobe"
    }

CARA BERPIKIR KAMU:

${
  hasWardrobe
    ? `Kamu sudah punya semua data wardrobe ${user.nama}. Tugasmu:
1. Lihat SEMUA item wardrobe yang ada
2. Pilih kombinasi terbaik (mix & match) yang cocok untuk kebutuhan/acara yang diminta
3. Buat 1 set outfit lengkap: atasan + bawahan + sepatu + aksesori (jika tersedia)
4. Rekomendasikan kombinasi spesifik dengan nama item pastinya
5. JANGAN tanya "apa yang kamu punya" — kamu sudah tahu`
    : `${user.nama} belum punya data wardrobe. Rekomendasikan outfit sesuai body shape dan style favorit.`
}

Jika butuh info tambahan (acara, cuaca, dll): tanya 1-2 hal saja lalu eksekusi.
Respons natural seperti fashion expert. **Bold** + penomoran jika membantu.
Bahasa Indonesia, panggil "${user.nama}". Maksimal 200 kata.

INSTRUKSI GENERATE GAMBAR — jika ada rekomendasi outfit konkret, tambahkan DUA tag ini di baris paling akhir:

<!--DALL_E_PROMPTS:["${genderHint} wearing: {deskripsikan outfit lengkap dari wardrobe yang dipilih}"]-->
<!--OUTFIT_DATA:{"items":[{"label":"Atasan","name":"nama item","hex":"#hexcolor"},{"label":"Bawahan","name":"nama item","hex":"#hexcolor"},{"label":"Sepatu","name":"nama item","hex":"#hexcolor"},{"label":"Aksesori","name":"nama item","hex":"#hexcolor"}]}-->

PENTING untuk DALL_E_PROMPTS:
- Tulis prompt yang SPESIFIK: sebutkan warna, bahan, potongan tiap item
- Tambahkan gender secara eksplisit: "${genderHint} model" atau "men's fashion" / "women's fashion"
- Jika respons hanya sapaan/pertanyaan, JANGAN tambahkan kedua tag ini.`;
  } else {
    console.log("=== [USER DATA DEBUG] ===");
    console.log(
      "Status: TIDAK LOGIN — window.__USER_DATA__ is",
      window.__USER_DATA__,
    );
    console.log("=========================");

    return `Kamu adalah FitMatch AI — asisten fashion yang ramah dan helpful.

Kamu tidak tahu data pengguna ini, tapi kamu bisa membantu mereka tampil lebih baik. Gunakan informasi yang mereka berikan dalam percakapan untuk merekomendasikan outfit yang spesifik: warna, bahan, gaya, aksesori.

Berikan respons yang natural — seperti teman yang paham fashion. Gunakan **bold** dan penomoran jika membantu. Bahasa Indonesia yang santai. Maksimal 150 kata.

Jangan tambahkan tag DALL_E_PROMPTS — fitur gambar hanya untuk pengguna yang login. Sesekali (tidak perlu setiap pesan) sebutkan bahwa login membuka fitur rekomendasi personal dan gambar outfit AI.`;
  }
}

// =====================================================================
// STATE
// =====================================================================
let currentSlug = window.__INIT_SLUG__ ?? null;
let currentIdRecents = window.__INIT_ID_RECENTS__ ?? null;
// let uploadedImage     = null; // base64 untuk preview & OpenAI
let uploadedImageFile = null; // File object untuk upload server
let isWaitingResponse = false;

// =====================================================================
// KEY DOWN HANDLER
// =====================================================================
function handleKeyDown(event) {
  if (event.key === "Enter" && !event.shiftKey) {
    event.preventDefault();
    sendMessage();
  }
}

// =====================================================================
// IMAGE UPLOAD (user pilih file)
// =====================================================================
function handleImageUpload(event) {
  const file = event.target.files[0];
  if (!file) return;
  uploadedImageFile = file;
  const reader = new FileReader();
  reader.onload = (e) => {
    uploadedImage = e.target.result;
    document.getElementById("preview-img").src = uploadedImage;
    document.getElementById("image-preview").classList.remove("hidden");
  };
  reader.readAsDataURL(file);
}

function removeImagePreview() {
  uploadedImage = null;
  uploadedImageFile = null;
  document.getElementById("preview-img").src = "";
  document.getElementById("image-preview").classList.add("hidden");
  const fi = document.querySelector('input[type="file"]');
  if (fi) fi.value = "";
}

// =====================================================================
// PARSE TAG DALL-E & OUTFIT_DATA
// =====================================================================
function parseDallEPrompts(rawText) {
  const match = rawText.match(/<!--DALL_E_PROMPTS:(\[.*?\])-->/s);
  if (!match) {
    console.warn("[DALL-E] Tag tidak ditemukan:", rawText.slice(-200));
    return [];
  }
  try {
    const p = JSON.parse(match[1]);
    console.log("[DALL-E] Prompts:", p);
    return p;
  } catch (e) {
    console.error("[DALL-E] Parse gagal:", e);
    return [];
  }
}

function parseOutfitData(rawText) {
  const match = rawText.match(/<!--OUTFIT_DATA:(\{.*?\})-->/s);
  if (!match) return null;
  try {
    return JSON.parse(match[1]);
  } catch (e) {
    console.warn("[OUTFIT_DATA] Parse gagal:", e);
    return null;
  }
}

function cleanReplyText(text) {
  return text
    .replace(/<!--DALL_E_PROMPTS:.*?-->/gs, "")
    .replace(/<!--OUTFIT_DATA:.*?-->/gs, "")
    .trim();
}

// =====================================================================
// UPLOAD FILE KE SERVER → /ai/upload-image
// Return: URL lengkap file di server
// =====================================================================
async function uploadFileToServer(file, filename) {
  const fd = new FormData();
  fd.append("image", file, filename);
  try {
    const res = await fetch("/ai/upload-image", {
      method: "POST",
      body: fd,
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });
    const data = await res.json();
    return data.success ? data.url : null; // kembalikan URL penuh
  } catch (e) {
    console.error("[Upload] Gagal:", e);
    return null;
  }
}

// Konversi data URL/blob URL ke Blob untuk di-upload
async function dataUrlToBlob(dataUrl) {
  const res = await fetch(dataUrl);
  return await res.blob();
}

// =====================================================================
// DALL-E 3: Generate gambar → PHP download+blur+simpan (hindari CORS)
// mode: "outfit" (default) | "tryon" (ada foto user)
// =====================================================================
async function generateAndSaveOutfitImage(
  prompt,
  mode = "outfit",
  wardrobeRefs = [],
  outfitDataHint = null,
) {
  const apiKey = getApiKey();

  let openaiUrl;

  if (mode === "tryon" && uploadedImage) {
    // Try-on mode pakai gpt-image-1 (model edit gambar OpenAI terbaru)
    // Kirim foto user + prompt outfit → model "memakaikan" outfit ke foto
    // gpt-image-1 menerima input gambar + teks, output gambar yang diedit

    // Konversi base64 ke blob untuk multipart upload
    const base64Data = uploadedImage.split(",")[1];
    const mimeType = uploadedImage.split(";")[0].split(":")[1] ?? "image/jpeg";
    const byteChars = atob(base64Data);
    const byteArr = new Uint8Array(byteChars.length);
    for (let i = 0; i < byteChars.length; i++)
      byteArr[i] = byteChars.charCodeAt(i);
    const userBlob = new Blob([byteArr], { type: mimeType });

    const tryonPrompt = `The person in this photo is wearing: ${prompt}. Keep the person's face, body, and pose exactly the same. Only change the clothing to match the outfit described. Background: clean white studio. Realistic fashion photography style.`;
    console.log("[Tryon] gpt-image-1 prompt:", tryonPrompt);

    const fd = new FormData();
    fd.append("model", "gpt-image-1");
    fd.append("prompt", tryonPrompt);
    fd.append("image[]", userBlob, "user_photo.jpg"); // gpt-image-1 format
    fd.append("n", "1");
    fd.append("size", "1024x1024");

    const genRes = await fetch("https://api.openai.com/v1/images/edits", {
      method: "POST",
      headers: { Authorization: `Bearer ${apiKey}` }, // no Content-Type — biar browser set multipart boundary
      body: fd,
    });

    if (!genRes.ok) {
      const errData = await genRes.json();
      console.error("[Tryon] gpt-image-1 error:", errData);
      throw new Error(errData.error?.message ?? "gpt-image-1 error");
    }

    const genData = await genRes.json();

    // gpt-image-1 return base64 (bukan URL seperti DALL-E 3)
    const b64 = genData.data[0].b64_json;
    if (!b64) throw new Error("gpt-image-1 tidak return gambar");

    // Simpan langsung ke server via PHP — kirim base64, PHP yang simpan
    const saveRes = await fetch("/ai/save-generated-image", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ b64_image: b64, mode: "tryon" }),
    });
    if (!saveRes.ok) throw new Error("Gagal simpan gambar tryon");
    const saveData = await saveRes.json();
    if (!saveData.success) throw new Error(saveData.message ?? "Gagal simpan");
    return saveData.url;
  } else {
    let dallEPrompt;

    if (wardrobeRefs.length > 0) {
      // Ada foto wardrobe nyata → GPT-4o Vision analisis foto → buat prompt DALL-E yang spesifik
      // Pendekatan ini reliable karena hanya pakai DALL-E 3 (tidak butuh gpt-image-1)
      console.log(
        "[Wardrobe] Analysing wardrobe photos via GPT-4o:",
        wardrobeRefs.map((w) => w.nama_item),
      );

      // Ambil base64 tiap foto wardrobe
      const wardrobeB64s = await Promise.allSettled(
        wardrobeRefs.map(async (w) => {
          try {
            const res = await fetch(
              `/ai/proxy-image?url=${encodeURIComponent(w.image_url)}`,
              {
                headers: { "X-Requested-With": "XMLHttpRequest" },
              },
            );
            if (!res.ok) return null;
            const blob = await res.blob();
            // Validasi MIME — hanya terima image
            if (!blob.type.startsWith("image/")) return null;
            return new Promise((resolve) => {
              const reader = new FileReader();
              reader.onload = () => resolve({ b64: reader.result, item: w });
              reader.onerror = () => resolve(null);
              reader.readAsDataURL(blob);
            });
          } catch {
            return null;
          }
        }),
      );

      const validRefs = wardrobeB64s
        .filter((r) => r.status === "fulfilled" && r.value?.b64)
        .map((r) => r.value);

      if (validRefs.length > 0) {
        // GPT-4o Vision: lihat foto wardrobe → describe secara detail untuk DALL-E prompt
        // Ambil context dari outfitData hint (nama item yang dipilih AI)
        const chosenItems =
          outfitDataHint?.items
            ?.map((i) => `${i.label}: ${i.name}`)
            .join(", ") ?? "";
        const genderCtx = window.__USER_GENDER__ ?? "male";

        const analysisContent = [
          {
            type: "text",
            text: `You are a fashion photographer's AI assistant. You see photos of clothing items from a user's wardrobe.
${chosenItems ? `The AI has selected these items for the outfit: ${chosenItems}.` : "Select the best mix & match combination from these wardrobe items."}

Your task:
1. Identify each clothing item in the photos (color, material, cut, pattern — be VERY specific)
2. Choose the best combination that works together as one cohesive outfit
3. Write ONE highly specific DALL-E 3 prompt in English that:
   - Shows a ${genderCtx} model wearing these EXACT items (mention the specific colors/styles you see)
   - Full body shot from head to toe
   - Shows face (not blurred), but focused on the outfit
   - Modern fashion editorial style, clean white studio background
   - The model should look like a real ${genderCtx} person, not a mannequin
   - DO NOT use "headless mannequin" — use "${genderCtx} model"

Reply with ONLY the DALL-E prompt. No explanation, no prefix, just the prompt.`,
          },
          ...validRefs.map((ref) => ({
            type: "image_url",
            image_url: { url: ref.b64, detail: "high" },
          })),
        ];

        const analysisRes = await fetch(
          "https://api.openai.com/v1/chat/completions",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${apiKey}`,
            },
            body: JSON.stringify({
              model: "gpt-4o",
              max_tokens: 400,
              messages: [{ role: "user", content: analysisContent }],
            }),
          },
        );

        if (analysisRes.ok) {
          const analysisData = await analysisRes.json();
          const visionPrompt =
            analysisData.choices?.[0]?.message?.content?.trim();
          if (visionPrompt) {
            dallEPrompt = visionPrompt; // sudah lengkap dari GPT-4o
            console.log("[Wardrobe] Vision-generated prompt:", dallEPrompt);
          }
        }
      }
    }

    // Jika tidak ada wardrobe refs atau vision gagal → pakai prompt dari AI response
    if (!dallEPrompt) {
      const genderFallback = window.__USER_GENDER__ ?? "male";
      dallEPrompt = `${genderFallback} model wearing: ${prompt}, full body shot, modern fashion editorial photography, clean white studio background, professional lighting, fashion magazine style`;
    }

    console.log("[DALL-E] Final prompt:", dallEPrompt);
    const res = await fetch("https://api.openai.com/v1/images/generations", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${apiKey}`,
      },
      body: JSON.stringify({
        model: "dall-e-3",
        prompt: dallEPrompt,
        n: 1,
        size: "1024x1024",
        quality: "standard",
      }),
    });
    if (!res.ok) {
      const e = await res.json();
      throw new Error(e.error?.message ?? "DALL-E error");
    }
    openaiUrl = (await res.json()).data[0].url;
  }

  // Outfit mode: PHP proxy download + blur + simpan
  console.log("[DALL-E] OpenAI URL:", openaiUrl);

  const proxyFd = new FormData();
  proxyFd.append("openai_url", openaiUrl);
  proxyFd.append("mode", "outfit");
  const proxyRes = await fetch("/ai/save-generated-image", {
    method: "POST",
    body: proxyFd,
    headers: { "X-Requested-With": "XMLHttpRequest" },
  });
  if (!proxyRes.ok) throw new Error("Gagal menyimpan gambar ke server");
  const proxyData = await proxyRes.json();
  if (!proxyData.success)
    throw new Error(proxyData.message ?? "Gagal simpan gambar");
  return proxyData.url;
}

// =====================================================================
// RENDER OUTFIT CARD — format seperti referensi gambar
// =====================================================================
function renderOutfitCard(imageUrl, outfitData) {
  const items = outfitData?.items ?? [];

  const itemsHTML = items
    .map(
      (item) => `
    <div class="flex items-center gap-2 py-1.5 border-b border-gray-100 dark:border-gray-700 last:border-0">
      <div class="w-5 h-5 rounded-full border border-gray-200 flex-shrink-0 shadow-sm"
        style="background:${escapeHtml(item.hex ?? "#ccc")}"></div>
      <div class="flex-1 min-w-0">
        <span class="text-xs font-semibold text-muted">${escapeHtml(item.label)}</span>
        <span class="text-xs ml-1">${escapeHtml(item.name)}</span>
      </div>
    </div>`,
    )
    .join("");

  const swatchesHTML = items
    .map(
      (item) => `
    <div class="flex flex-col items-center gap-1">
      <div class="w-6 h-6 rounded-full border border-gray-200 shadow-sm"
        style="background:${escapeHtml(item.hex ?? "#ccc")}"></div>
    </div>`,
    )
    .join("");

  return `
    <div class="outfit-card rounded-2xl overflow-hidden mb-3 border border-gray-100 dark:border-gray-700" style="background:var(--bg)">
      <div class="flex gap-0">

        <!-- Gambar outfit (kiri) -->
        <div class="relative flex-shrink-0 w-36 cursor-pointer" onclick="openImageViewer('${escapeHtml(imageUrl)}')">
          <img src="${escapeHtml(imageUrl)}"
            class="w-full h-full object-cover hover:opacity-90 transition-opacity"
            style="min-height:200px;max-height:260px;object-position:center top"
            loading="lazy"
            onerror="this.parentElement.style.display='none'">
          <!-- Color dots overlay -->
          <div class="absolute bottom-2 left-0 right-0 flex justify-center gap-1.5">
            ${items
              .slice(0, 4)
              .map(
                (item) => `
              <div class="w-4 h-4 rounded-full border-2 border-white shadow"
                style="background:${escapeHtml(item.hex ?? "#ccc")}"></div>`,
              )
              .join("")}
          </div>
        </div>

        <!-- Panel detail (kanan) -->
        <div class="flex-1 p-3 flex flex-col justify-between" style="background:var(--card)">
          <div class="text-xs font-bold mb-2 primary-text">Outfit Recommendation</div>
          <div class="flex-1">${itemsHTML}</div>
          <div class="flex gap-1.5 mt-2 pt-2 border-t border-gray-100 dark:border-gray-700">
            ${swatchesHTML}
          </div>
        </div>

      </div>
    </div>`;
}

// =====================================================================
// RENDER BUBBLE AI dari conversation JSON
// =====================================================================
function renderAIBubble(conv) {
  // conv = { text, images, outfitData }
  const aiImages = (conv.images ?? []).filter((i) => i.type === "ai");
  const outfitData = conv.outfitData ?? null;

  // Outfit card (gambar + panel detail)
  const imagesHTML =
    aiImages.length > 0 ? renderOutfitCard(aiImages[0].url, outfitData) : "";

  return `
    <div class="flex gap-3 fade-in typing-msg">
      <div class="w-8 h-8 primary-bg rounded-full flex-shrink-0 flex items-center justify-center">
        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none">
          <path d="M6 4l.5 1.5L8 6l-1.5.5L6 8l-.5-1.5L4 6l1.5-.5L6 4z" fill="currentColor" opacity="0.9"/>
          <path d="M19 2l.6 1.8L21.4 4.5l-1.8.6L19 7l-.6-1.9-1.8-.6 1.8-.7L19 2z" fill="currentColor" opacity="0.9"/>
          <circle cx="12" cy="6" r="2.5" stroke="currentColor" stroke-width="1.8"/>
          <path d="M6.5 20c.5-4 3-6 5.5-6s5 2 5.5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          <path d="M4.5 13c2-1 4-2 7.5-2s5.5 1 7.5 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
      </div>
      <div class="chat-bubble-ai p-4 max-w-sm lg:max-w-lg">
        ${imagesHTML}
        <div class="text-sm mb-3">${renderMarkdown(conv.text)}</div>
        <div class="flex gap-2 mt-3 pt-3 border-t border-gray-200">
          <button onclick="handleFeedback(this,'like')"
            class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-xs text-muted">
            <i class="fas fa-thumbs-up"></i> <span class="like-count">0</span>
          </button>
          <button onclick="handleFeedback(this,'dislike')"
            class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-xs text-muted">
            <i class="fas fa-thumbs-down"></i> <span class="dislike-count">0</span>
          </button>
          <button onclick="handleShare()"
            class="flex items-center gap-1 px-2 py-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-xs text-muted">
            <i class="fas fa-share-alt"></i>
          </button>
        </div>
      </div>
    </div>`;
}

// =====================================================================
// UI HELPERS
// =====================================================================
function appendUserMessage(text, imageUrls = []) {
  const container = document.getElementById("chat-messages");
  const div = document.createElement("div");
  div.className = "flex gap-3 fade-in justify-end";
  div.innerHTML = `
    <div class="chat-bubble-user p-4 max-w-sm lg:max-w-lg">
      ${imageUrls.map((u) => `<img src="${escapeHtml(u)}" class="rounded-lg mb-2 max-h-40 object-contain"/>`).join("")}
      ${text ? `<div class="text-sm">${renderMarkdown(text)}</div>` : ""}
    </div>`;
  container.appendChild(div);
  container.scrollTop = container.scrollHeight;
}

function createTypingIndicator() {
  const container = document.getElementById("chat-messages");
  const div = document.createElement("div");
  div.className = "flex gap-3 fade-in typing-msg";
  div.id = "typing-indicator";
  div.innerHTML = `
    <div class="w-8 h-8 primary-bg rounded-full flex-shrink-0 flex items-center justify-center">
      <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none">
        <path d="M6 4l.5 1.5L8 6l-1.5.5L6 8l-.5-1.5L4 6l1.5-.5L6 4z" fill="currentColor" opacity="0.9"/>
        <circle cx="12" cy="6" r="2.5" stroke="currentColor" stroke-width="1.8"/>
        <path d="M6.5 20c.5-4 3-6 5.5-6s5 2 5.5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
        <path d="M4.5 13c2-1 4-2 7.5-2s5.5 1 7.5 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
      </svg>
    </div>
    <div class="chat-bubble-ai p-4">
      <div class="typing-indicator text-primary">
        <div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>
      </div>
    </div>`;
  container.appendChild(div);
  container.scrollTop = container.scrollHeight;
  return div;
}

// =====================================================================
// REPLACE TYPING → AI MESSAGE
// Return: conversation JSON object (untuk disimpan ke DB)
// =====================================================================
async function replaceTypingWithAIMessage(typingDiv, rawReply) {
  const bubble = typingDiv.querySelector(".chat-bubble-ai");
  const cleanText = cleanReplyText(rawReply);
  const prompts = isLoggedIn() ? parseDallEPrompts(rawReply) : [];
  const images = []; // [{url, type:"ai"}]

  console.log("[Chat] isLoggedIn:", isLoggedIn(), "| Prompts:", prompts.length);

  if (prompts.length > 0) {
    // Skeleton loading — hanya 1 gambar
    bubble.innerHTML = `
      <div class="mb-3">
        <div class="w-full max-w-xs aspect-square rounded-xl bg-gray-200 animate-pulse flex items-center justify-center">
          <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
      </div>
      <p class="text-xs text-muted mb-2">✨ Membuat gambar outfit...</p>
      <div class="text-sm">${renderMarkdown(cleanText)}</div>`;
    document.getElementById("chat-messages").scrollTop = 99999;

    // Deteksi tryon mode
    const tryonKeywords = [
      "implementasikan",
      "masukkan ke foto",
      "coba ke foto",
      "pakaikan",
      "tampilkan di foto",
      "try on",
      "foto ini",
      "foto saya",
    ];
    const isTryon =
      uploadedImage &&
      tryonKeywords.some((kw) =>
        (window.__lastUserMessage__ ?? "").toLowerCase().includes(kw),
      );
    const mode = isTryon ? "tryon" : "outfit";

    // Ambil SEMUA wardrobe dengan gambar untuk mix & match yang akurat
    const user = getUserData();
    const allWardrobe = (user?.wardrobe ?? []).filter((w) => w.image_url);

    // Kirim semua ke vision (max 8 agar token tidak meledak)
    // Priority: 1 atasan + semua celana/bawahan + 1 per jenis lain
    const byType = {};
    allWardrobe.forEach((w) => {
      if (!byType[w.type_wardrobe]) byType[w.type_wardrobe] = [];
      byType[w.type_wardrobe].push(w);
    });
    // Ambil semua celana/pants, max 2 atasan, max 1 per jenis lain
    const wardrobeRefs = [
      ...(byType["Pants"] ?? byType["Celana"] ?? []).slice(0, 3), // semua celana (max 3)
      ...(byType["Shirts"] ?? byType["Atasan"] ?? []).slice(0, 2), // max 2 atasan
      ...Object.entries(byType)
        .filter(([k]) => !["Pants", "Celana", "Shirts", "Atasan"].includes(k))
        .flatMap(([, v]) => v.slice(0, 1)), // 1 per jenis lain
    ].slice(0, 8);

    console.log(
      "[Mix&Match] Wardrobe refs:",
      wardrobeRefs.map((w) => `${w.nama_item}(${w.type_wardrobe})`),
    );

    // Parse outfit data dari AI reply untuk context tambahan ke vision
    const outfitDataForGen = parseOutfitData(rawReply);

    // Generate gambar
    try {
      const serverUrl = await generateAndSaveOutfitImage(
        prompts[0],
        mode,
        wardrobeRefs,
        outfitDataForGen,
      );
      images.push({ url: serverUrl, type: "ai", isTryon });
    } catch (e) {
      console.error("[DALL-E] Gagal generate/simpan gambar:", e);
    }
  }

  // Parse outfit data (color swatches + item list)
  const outfitData = parseOutfitData(rawReply);

  // Bangun conversation JSON
  const conv = { text: cleanText, images, outfitData };

  // Render bubble dengan data final
  const el = document.createElement("div");
  el.innerHTML = renderAIBubble(conv);
  typingDiv.replaceWith(el.firstElementChild);
  document.getElementById("chat-messages").scrollTop = 99999;

  return conv; // dikembalikan untuk disimpan ke DB
}

// =====================================================================
// LOAD CHAT BY SLUG — AJAX tanpa reload (klik sidebar)
// Dipanggil dari PHP: loadChatBySlug('8042c05a8dcc2653')
// =====================================================================
async function loadChatBySlug(slug) {
  if (!slug) return;

  // Update active state sidebar
  document.querySelectorAll(".history-item").forEach((el) => {
    const isActive = el.dataset.slug === slug;
    el.classList.toggle("primary-bg", isActive);
    el.classList.toggle("text-white", isActive);
    el.classList.toggle("text-muted", !isActive);
  });

  const container = document.getElementById("chat-messages");
  container.innerHTML = `
    <div class="flex justify-center py-8">
      <div class="typing-indicator text-primary">
        <div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>
      </div>
    </div>`;

  try {
    // Controller detect header X-Requested-With → return JSON bukan HTML
    const res = await fetch(`/ai/${slug}`, {
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });
    const data = await res.json();
    if (!data.success) throw new Error("Gagal memuat percakapan");

    currentSlug = slug;
    currentIdRecents = data.id_recents;
    window.__CHAT_HISTORY__ = data.messages; // konteks untuk OpenAI
    window.history.pushState({}, "", `/ai/${slug}`);

    container.innerHTML = "";
    data.messages.forEach((m) => {
      const conv = parseConversation(m.conversation);
      if (m.role === 1) {
        const imgs = (conv.images ?? [])
          .filter((i) => i.type === "user")
          .map((i) => i.url);
        appendUserMessage(conv.text, imgs);
      } else {
        const el = document.createElement("div");
        el.innerHTML = renderAIBubble(conv);
        container.appendChild(el.firstElementChild);
      }
    });
    container.scrollTop = container.scrollHeight;
  } catch (err) {
    console.error("[loadChatBySlug]", err);
    container.innerHTML = `<p class="text-center text-red-500 text-sm py-8">Gagal memuat percakapan.</p>`;
  }
}

// Alias untuk kompatibilitas jika masih ada onclick lama
const loadChatHistoryById = (id) =>
  console.warn("Gunakan loadChatBySlug(slug) — loadChatHistoryById deprecated");

// =====================================================================
// OPENAI: GENERATE REPLY
// =====================================================================
async function callOpenAI(userMessage) {
  const apiKey = getApiKey();
  if (!apiKey) throw new Error("API key tidak ditemukan.");

  const messages = [{ role: "system", content: buildSystemPrompt() }];

  // History percakapan
  (window.__CHAT_HISTORY__ ?? []).forEach((m) => {
    const conv = parseConversation(m.conversation);
    messages.push({
      role: m.role === 1 ? "user" : "assistant",
      content: conv.text,
    });
  });

  // Pesan user saat ini — bisa berupa teks + gambar upload user
  const userContent = [];
  if (userMessage) userContent.push({ type: "text", text: userMessage });
  if (uploadedImage)
    userContent.push({ type: "image_url", image_url: { url: uploadedImage } });

  // Sertakan gambar wardrobe sebagai referensi visual ke GPT-4o
  // Dikirim SELALU (tidak hanya saat tidak ada upload) agar AI tahu tampilan nyata wardrobe
  const user = getUserData();
  const wardrobeWithImages = (user?.wardrobe ?? []).filter((w) => w.image_url);

  if (wardrobeWithImages.length > 0) {
    // Kirim max 6 gambar wardrobe, semua jenis (atasan, bawahan, dll)
    // Group by type dulu agar representatif — 1-2 per jenis
    const byType = {};
    wardrobeWithImages.forEach((w) => {
      if (!byType[w.type_wardrobe]) byType[w.type_wardrobe] = [];
      byType[w.type_wardrobe].push(w);
    });
    const selected = Object.values(byType)
      .flatMap((items) => items.slice(0, 2))
      .slice(0, 6);

    // Konversi tiap gambar ke base64 via PHP proxy
    // (OpenAI tidak bisa akses URL lokal 192.168.x.x atau domain internal)
    const b64Results = await Promise.allSettled(
      selected.map(async (w) => {
        try {
          const res = await fetch(
            `/ai/proxy-image?url=${encodeURIComponent(w.image_url)}`,
            {
              headers: { "X-Requested-With": "XMLHttpRequest" },
            },
          );
          if (!res.ok) return null;
          const blob = await res.blob();
          const b64 = await new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = () => resolve(reader.result); // data:image/jpeg;base64,...
            reader.readAsDataURL(blob);
          });
          return { ...w, base64: b64 };
        } catch {
          return null;
        }
      }),
    );

    const selectedWithB64 = b64Results
      .filter((r) => r.status === "fulfilled" && r.value?.base64)
      .map((r) => r.value);

    // Validasi: hanya kirim base64 yang benar-benar image (bukan HTML error page)
    const validB64 = selectedWithB64.filter((w) => {
      const mime = w.base64?.split(";")[0]?.replace("data:", "") ?? "";
      return mime.startsWith("image/");
    });

    if (validB64.length > 0) {
      messages.push({
        role: "user",
        content: [
          {
            type: "text",
            text: `Ini foto-foto wardrobe saya:
${validB64.map((w) => `- ${w.nama_item} (${w.type_wardrobe})`).join("")}
Gunakan sebagai referensi visual saat merekomendasikan outfit.`,
          },
          ...validB64.map((w) => ({
            type: "image_url",
            image_url: { url: w.base64, detail: "low" },
          })),
        ],
      });
      messages.push({
        role: "assistant",
        content: `Oke, sudah saya lihat wardrobemu — ${validB64.map((w) => w.nama_item).join(", ")}. Saya akan gunakan ini sebagai referensi.`,
      });
    }
  }

  // Pakai gpt-4o jika ada gambar di userContent ATAU ada wardrobe images di messages
  const hasUserImages = userContent.some((c) => c.type === "image_url");
  const hasWardrobeInMsg = messages.some(
    (m) =>
      Array.isArray(m.content) && m.content.some((c) => c.type === "image_url"),
  );
  const needsVision = hasUserImages || hasWardrobeInMsg;

  messages.push({
    role: "user",
    content: hasUserImages ? userContent : userMessage,
  });

  const res = await fetch("https://api.openai.com/v1/chat/completions", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${apiKey}`,
    },
    body: JSON.stringify({
      model: needsVision ? "gpt-4o" : "gpt-4o-mini", // gpt-4o wajib jika ada gambar
      messages,
      max_tokens: 800,
      temperature: 0.8,
    }),
  });
  if (!res.ok) {
    const e = await res.json();
    throw new Error(e.error?.message ?? "OpenAI error");
  }
  const reply = (await res.json()).choices[0].message.content.trim();
  console.log("[GPT] Raw reply (last 300):", reply.slice(-300));
  return reply;
}

// =====================================================================
// OPENAI: GENERATE TITLE
// =====================================================================
async function generateTitle(userMessage) {
  const apiKey = getApiKey();
  if (!apiKey) return userMessage.slice(0, 50);
  try {
    const res = await fetch("https://api.openai.com/v1/chat/completions", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${apiKey}`,
      },
      body: JSON.stringify({
        model: "gpt-4o-mini",
        max_tokens: 20,
        temperature: 0.5,
        messages: [
          {
            role: "system",
            content:
              "Buat judul singkat maksimal 50 karakter Bahasa Indonesia. Balas judulnya saja tanpa tanda kutip.",
          },
          { role: "user", content: userMessage },
        ],
      }),
    });
    if (!res.ok) throw new Error();
    return (
      (await res.json()).choices[0].message.content.trim() ||
      userMessage.slice(0, 50)
    );
  } catch {
    return userMessage.slice(0, 50);
  }
}

// =====================================================================
// SIMPAN KE SERVER
// conversation dikirim sebagai JSON string
// =====================================================================
async function saveToServer({ userConvJson, aiConvJson, aiTitle }) {
  const fd = new FormData();
  fd.append("user_conversation", JSON.stringify(userConvJson)); // JSON string
  fd.append("ai_conversation", JSON.stringify(aiConvJson)); // JSON string
  fd.append("ai_title", aiTitle);
  fd.append("id_recents", currentIdRecents ?? "");
  fd.append("slug", currentSlug ?? "");

  const res = await fetch("/ai/send", {
    method: "POST",
    body: fd,
    headers: { "X-Requested-With": "XMLHttpRequest" },
  });
  if (!res.ok) throw new Error("Server error");
  return await res.json();
}

// =====================================================================
// SEND MESSAGE — MAIN
// =====================================================================
async function sendMessage() {
  if (isWaitingResponse) return;

  const input = document.getElementById("chat-input");
  const message = input.value.trim();
  if (!message && !uploadedImage) return;

  isWaitingResponse = true;
  setSendButtonState(false);

  const imageSnapshot = uploadedImage; // base64 untuk preview
  const imageFileSnapshot = uploadedImageFile; // File untuk upload

  // Tampilkan pesan user di UI (preview dulu, sebelum upload)
  appendUserMessage(message, imageSnapshot ? [imageSnapshot] : []);

  if (!window.__CHAT_HISTORY__) window.__CHAT_HISTORY__ = [];
  window.__lastUserMessage__ = message; // untuk deteksi tryon mode

  input.value = "";
  autoResizeTextarea(input);
  removeImagePreview();

  const typingDiv = createTypingIndicator();

  try {
    // 1. Upload gambar user ke server — hanya jika login
    let userImageUrl = null;
    const uploadPromise =
      imageFileSnapshot && isLoggedIn()
        ? uploadFileToServer(
            imageFileSnapshot,
            `user_${Date.now()}_${imageFileSnapshot.name}`,
          )
        : Promise.resolve(null);

    // 2. Panggil GPT + generate title
    const [aiReply, aiTitle, resolvedUserImageUrl] = await Promise.all([
      callOpenAI(message),
      currentSlug ? Promise.resolve("") : generateTitle(message),
      uploadPromise,
    ]);
    userImageUrl = resolvedUserImageUrl;

    // Bangun conversation JSON untuk user
    const userConvJson = {
      text: message,
      images: userImageUrl ? [{ url: userImageUrl, type: "user" }] : [],
    };

    // Simpan ke local history (plain text untuk konteks OpenAI)
    window.__CHAT_HISTORY__.push({
      role: 1,
      conversation: JSON.stringify(userConvJson),
    });

    // 3. Generate DALL-E + render AI bubble → dapat conv JSON
    const aiConvJson = await replaceTypingWithAIMessage(typingDiv, aiReply);

    window.__CHAT_HISTORY__.push({
      role: 0,
      conversation: JSON.stringify(aiConvJson),
    });

    // 4. Simpan ke DB — hanya jika user login
    if (isLoggedIn()) {
      const serverData = await saveToServer({
        userConvJson,
        aiConvJson,
        aiTitle: aiTitle ?? "",
      });
      if (serverData.success && !currentSlug) {
        currentSlug = serverData.slug;
        currentIdRecents = serverData.id_recents;
        window.history.pushState({}, "", serverData.redirect);
        if (typeof addHistoryToSidebar === "function") {
          addHistoryToSidebar(serverData.slug, aiTitle, serverData.id_recents);
        }
      }
    }
  } catch (err) {
    console.error("[sendMessage]", err);
    const bubble = typingDiv.querySelector(".chat-bubble-ai");
    if (bubble)
      bubble.innerHTML = `<p class="text-sm text-red-500">⚠️ ${escapeHtml(err.message ?? "Gagal mendapatkan respons.")}</p>`;
  } finally {
    isWaitingResponse = false;
    setSendButtonState(true);
  }
}

// =====================================================================
// FEEDBACK & SHARE
// =====================================================================
function handleFeedback(btn, type) {
  const c = btn.querySelector(
    type === "like" ? ".like-count" : ".dislike-count",
  );
  if (c) c.textContent = parseInt(c.textContent) + 1;
  btn.classList.add("text-primary");
}
function handleShare() {
  if (navigator.share)
    navigator.share({ title: "FitMatch AI", url: window.location.href });
  else {
    navigator.clipboard.writeText(window.location.href);
    alert("Link disalin!");
  }
}

// =====================================================================
// HELPERS
// =====================================================================
function escapeHtml(str) {
  return String(str ?? "")
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}
function setSendButtonState(enabled) {
  const btn = document.querySelector(".send-btn");
  if (!btn) return;
  btn.disabled = !enabled;
  btn.style.opacity = enabled ? "1" : "0.5";
}
function autoResizeTextarea(el) {
  el.style.height = "auto";
  el.style.height = Math.min(el.scrollHeight, 100) + "px";
}
// =====================================================================
// NEW CHAT — reset semua state & UI ke kondisi awal
// Dipanggil dari tombol New Chat di sidebar
// =====================================================================
function newChat() {
  // Reset state global
  currentSlug = null;
  currentIdRecents = null;
  uploadedImage = null;
  uploadedImageFile = null;
  window.__CHAT_HISTORY__ = [];
  window.__lastUserMessage__ = "";

  // Reset URL ke /ai
  window.history.pushState({}, "", "/ai");

  // Reset active state sidebar
  document.querySelectorAll(".history-item").forEach((el) => {
    el.classList.remove("primary-bg", "text-white");
    el.classList.add("text-muted");
  });

  // Reset chat area ke welcome message
  const container = document.getElementById("chat-messages");
  if (container) {
    const user = getUserData();
    const nama = user?.nama ?? "";
    container.innerHTML = `
      <div class="flex gap-3 fade-in typing-msg">
        <div class="w-8 h-8 primary-bg rounded-full flex-shrink-0 flex items-center justify-center">
          <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none">
            <path d="M6 4l.5 1.5L8 6l-1.5.5L6 8l-.5-1.5L4 6l1.5-.5L6 4z" fill="currentColor" opacity="0.9"/>
            <path d="M19 2l.6 1.8L21.4 4.5l-1.8.6L19 7l-.6-1.9-1.8-.6 1.8-.7L19 2z" fill="currentColor" opacity="0.9"/>
            <circle cx="12" cy="6" r="2.5" stroke="currentColor" stroke-width="1.8"/>
            <path d="M6.5 20c.5-4 3-6 5.5-6s5 2 5.5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M4.5 13c2-1 4-2 7.5-2s5.5 1 7.5 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
          </svg>
        </div>
        <div class="chat-bubble-ai p-4 max-w-sm lg:max-w-lg">
          <p class="text-sm">Halo${nama ? " " + nama : ""}! 👋 Saya FitMatch AI, style assistant kamu. Mau tampil seperti apa hari ini?</p>
        </div>
      </div>`;
    container.scrollTop = 0;
  }

  // Reset input
  const input = document.getElementById("chat-input");
  if (input) {
    input.value = "";
    autoResizeTextarea(input);
  }
  removeImagePreview();
}

document.addEventListener("DOMContentLoaded", () => {
  const ta = document.getElementById("chat-input");
  if (ta) ta.addEventListener("input", () => autoResizeTextarea(ta));

  // Inject image viewer modal ke DOM
  if (!document.getElementById("img-viewer-modal")) {
    document.body.insertAdjacentHTML(
      "beforeend",
      `
      <div id="img-viewer-modal"
        class="fixed inset-0 z-[9999] hidden flex-col"
        style="background:rgba(0,0,0,0.92)">

        <!-- Toolbar atas -->
        <div class="flex items-center justify-between px-4 py-3 flex-shrink-0">
          <span class="text-white text-sm font-medium opacity-70" id="img-viewer-title">Outfit</span>
          <div class="flex items-center gap-3">
            <!-- Download -->
            <a id="img-viewer-download" href="#" download="outfit.jpg" target="_blank"
              class="text-white opacity-70 hover:opacity-100 transition-opacity p-2 rounded-lg hover:bg-white/10"
              title="Download">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
              </svg>
            </a>
            <!-- Open in new tab -->
            <a id="img-viewer-open" href="#" target="_blank"
              class="text-white opacity-70 hover:opacity-100 transition-opacity p-2 rounded-lg hover:bg-white/10"
              title="Buka di tab baru">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
            </a>
            <!-- Close -->
            <button onclick="closeImageViewer()"
              class="text-white opacity-70 hover:opacity-100 transition-opacity p-2 rounded-lg hover:bg-white/10"
              title="Tutup">
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Gambar -->
        <div class="flex-1 flex items-center justify-center p-4 overflow-auto"
          onclick="closeImageViewer()">
          <img id="img-viewer-img" src="" alt="Outfit"
            class="max-w-full max-h-full object-contain rounded-xl shadow-2xl"
            style="max-height:85vh"
            onclick="event.stopPropagation()">
        </div>

      </div>`,
    );
  }
});

// Buka image viewer (seperti ChatGPT)
function openImageViewer(url, title = "Outfit") {
  const modal = document.getElementById("img-viewer-modal");
  const img = document.getElementById("img-viewer-img");
  const dl = document.getElementById("img-viewer-download");
  const open = document.getElementById("img-viewer-open");
  const titleEl = document.getElementById("img-viewer-title");

  if (!modal) return;
  img.src = url;
  dl.href = url;
  open.href = url;
  titleEl.textContent = title;

  modal.classList.remove("hidden");
  modal.classList.add("flex");
  document.body.style.overflow = "hidden";

  // Close dengan ESC
  document.addEventListener("keydown", _viewerEscHandler);
}

function closeImageViewer() {
  const modal = document.getElementById("img-viewer-modal");
  if (!modal) return;
  modal.classList.add("hidden");
  modal.classList.remove("flex");
  document.body.style.overflow = "";
  document.removeEventListener("keydown", _viewerEscHandler);
}

function _viewerEscHandler(e) {
  if (e.key === "Escape") closeImageViewer();
}
