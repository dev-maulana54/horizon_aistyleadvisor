        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col h-full overflow-hidden">
            <!-- Mobile Header -->
            <header
                class="lg: hidden primary-bg text-white px-4 py-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div
                        class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                        <svg
                            class="w-5 h-5 text-white"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <!-- AI Sparkle (LEFT - moved higher) -->
                            <path
                                d="M6 4l.5 1.5L8 6l-1.5.5L6 8l-.5-1.5L4 6l1.5-.5L6 4z"
                                fill="currentColor"
                                opacity="0.9" />

                            <!-- AI Sparkle (RIGHT) -->
                            <path
                                d="M19 2l.6 1.8L21.4 4.5l-1.8.6L19 7l-.6-1.9-1.8-.6 1.8-.7L19 2z"
                                fill="currentColor"
                                opacity="0.9" />

                            <!-- Head -->
                            <circle
                                cx="12"
                                cy="6"
                                r="2.5"
                                stroke="currentColor"
                                stroke-width="1.8" />

                            <!-- Body -->
                            <path
                                d="M6.5 20c.5-4 3-6 5.5-6s5 2 5.5 6"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round" />

                            <!-- Arms -->
                            <path
                                d="M4.5 13c2-1 4-2 7.5-2s5.5 1 7.5 2"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round" />
                        </svg>
                    </div>
                    <span class="font-bold text-lg">AI Style Advisor</span>
                </div>
                <button
                    onclick="toggleDarkMode()"
                    class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <i id="theme-icon-mobile" class="fas fa-sun text-white"></i>
                </button>
            </header>
            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto">
                <!-- CHAT BACK BUTTON HEADER (Mobile Only) -->
                <!-- (Requirement #2) Header chat mobile dihilangkan: element tetap ada tapi tidak pernah ditampilkan -->
                <div
                    id="chat-header-mobile"
                    class="lg:hidden hidden card-bg border-b px-4 py-3 items-center gap-3 sticky top-0 z-10">
                    <button
                        onclick="backToChatHistory()"
                        class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </button>
                    <div class="flex-1">
                        <h3 class="font-semibold">AI Style Assistant</h3>
                        <p class="text-xs text-muted flex items-center gap-1">
                            <span
                                class="w-2 h-2 bg-green-500 rounded-full pulse-dot"></span>
                            Online
                        </p>
                    </div>
                </div>

                <!-- HOME PAGE -->

                <!-- FEATURE DETAIL PAGE (Requirement #4) -->
                <div id="page-feature" class="page-content hidden p-4 lg:p-8 fade-in">
                    <div
                        class="card-bg rounded-2xl p-4 lg:p-6 shadow-md mb-4 flex items-center justify-between sticky top-0 z-10 border">
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                onclick="showPage('home')"
                                class="w-10 h-10 rounded-xl border flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <div>
                                <h2 class="text-lg lg:text-2xl font-bold" id="feature-title">
                                    AI Feature
                                </h2>
                                <p
                                    class="text-muted text-xs lg:text-sm"
                                    id="feature-subtitle">
                                    Detail fitur
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            onclick="showPage('chat')"
                            class="px-4 py-2 rounded-xl primary-bg text-white text-sm font-medium hover:opacity-90 transition-opacity">
                            Open Chat
                        </button>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-4">
                        <div class="lg:col-span-2 card-bg rounded-2xl p-5 shadow-md">
                            <h3 class="font-semibold mb-2">Apa yang bisa kamu lakukan?</h3>
                            <p class="text-sm text-muted leading-relaxed" id="feature-desc">
                                Pilih salah satu AI Feature untuk melihat UI/UX detail. Kamu
                                bisa lanjutkan dengan chat untuk rekomendasi yang lebih
                                personal.
                            </p>

                            <div class="mt-4 grid sm:grid-cols-2 gap-3">
                                <button
                                    type="button"
                                    class="w-full border rounded-2xl p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                    onclick="
                      showToast('✅ Siap! Coba tulis kebutuhan kamu di Chat.')
                    ">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl primary-bg flex items-center justify-center text-white">
                                            <i class="fas fa-bolt"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-sm">Quick Start</p>
                                            <p class="text-muted text-xs">
                                                Mulai dengan template prompt
                                            </p>
                                        </div>
                                    </div>
                                </button>
                                <button
                                    type="button"
                                    class="w-full border rounded-2xl p-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
                                    onclick="
                      showToast('💡 Tips: sertakan tujuan, tempat, & cuaca ya!')
                    ">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl primary-bg flex items-center justify-center text-white">
                                            <i class="fas fa-lightbulb"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-sm">Tips &amp; Examples</p>
                                            <p class="text-muted text-xs">
                                                Contoh input biar hasilnya tajam
                                            </p>
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <div class="mt-5 border-t pt-4">
                                <p class="text-xs text-muted">
                                    Catatan: fitur ini menampilkan UI/UX halaman detail. Untuk
                                    hasil AI yang real-time, gunakan Chat.
                                </p>
                            </div>
                        </div>

                        <div class="card-bg rounded-2xl p-5 shadow-md">
                            <h3 class="font-semibold mb-3">Suggested Prompts</h3>
                            <div class="space-y-2">
                                <button
                                    type="button"
                                    onclick="
                      sendQuickReply(
                        'Buatkan outfit untuk acara semi-formal malam hari',
                      )
                    "
                                    class="w-full text-left px-3 py-2 rounded-xl border hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-sm">
                                    ✨ Outfit semi-formal
                                </button>
                                <button
                                    type="button"
                                    onclick="
                      sendQuickReply(
                        'Aku mau mix & match dari wardrobe: kemeja putih + celana chino. Saran sepatu?',
                      )
                    "
                                    class="w-full text-left px-3 py-2 rounded-xl border hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-sm">
                                    👕 Mix &amp; match wardrobe
                                </button>
                                <button
                                    type="button"
                                    onclick="
                      sendQuickReply(
                        'Ada interview besok pagi. Outfit yang aman gimana?',
                      )
                    "
                                    class="w-full text-left px-3 py-2 rounded-xl border hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors text-sm">
                                    💼 Interview outfit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI VIRTUAL TRY ON PAGE (Requirement #1) -->


                <!-- CHAT PAGE -->
                <div id="page-chat" class="page-content h-full flex flex-col">
                    <!-- (Requirement #3) History container: tambah New Chat + History button + search -->
                    <div class="px-4 py-3 border-b card-bg">
                        <div id="history-container" class="flex items-center gap-2">
                            <button
                                type="button"
                                onclick="newChat()"
                                class="px-3 py-2 rounded-xl primary-bg text-white text-xs font-medium hover:opacity-90 transition-opacity flex items-center gap-2">
                                <i class="fas fa-plus"></i><span>New Chat</span>
                            </button>
                            <button
                                type="button"
                                onclick="toggleHistoryDrawer(true)"
                                class="px-3 py-2 rounded-xl border text-xs font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors flex items-center gap-2">
                                <i class="fas fa-clock-rotate-left"></i><span>History</span>
                            </button>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div
                        id="chat-messages"
                        class="flex-1 overflow-y-auto p-4 space-y-4 pb-32 lg:pb-28">
                        <!-- AI Welcome Message -->
                        <div class="flex gap-3 fade-in">
                            <div
                                class="w-8 h-8 primary-bg rounded-full flex-shrink-0 flex items-center justify-center">
                                <svg
                                    class="w-5 h-5 text-white"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <!-- AI Sparkle (LEFT - moved higher) -->
                                    <path
                                        d="M6 4l.5 1.5L8 6l-1.5.5L6 8l-.5-1.5L4 6l1.5-.5L6 4z"
                                        fill="currentColor"
                                        opacity="0.9" />

                                    <!-- AI Sparkle (RIGHT) -->
                                    <path
                                        d="M19 2l.6 1.8L21.4 4.5l-1.8.6L19 7l-.6-1.9-1.8-.6 1.8-.7L19 2z"
                                        fill="currentColor"
                                        opacity="0.9" />

                                    <!-- Head -->
                                    <circle
                                        cx="12"
                                        cy="6"
                                        r="2.5"
                                        stroke="currentColor"
                                        stroke-width="1.8" />

                                    <!-- Body -->
                                    <path
                                        d="M6.5 20c.5-4 3-6 5.5-6s5 2 5.5 6"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round" />

                                    <!-- Arms -->
                                    <path
                                        d="M4.5 13c2-1 4-2 7.5-2s5.5 1 7.5 2"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round" />
                                </svg>
                            </div>
                            <div class="chat-bubble-ai p-4 max-w-xs lg:max-w-md">
                                <p class="text-sm">
                                    Halo! 👋 Saya AI Style Assistant. Saya bisa bantu kamu
                                    memilih outfit yang cocok untuk berbagai acara. Mau pergi ke
                                    mana hari ini?
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Chat Input - Fixed Bottom -->
                    <div
                        class="fixed bottom-0 left-0 right-0 lg:bottom-auto p-4 card-bg border-t z-40"
                        style="padding-bottom: calc(1rem + env(safe-area-inset-bottom))">
                        <!-- Image Preview -->
                        <div id="image-preview" class="hidden mb-3">
                            <div class="relative inline-block">
                                <img
                                    id="preview-img"
                                    src=""
                                    alt="Preview"
                                    class="h-20 rounded-lg object-contain bg-black/5 border" />
                                <button
                                    onclick="removeImagePreview()"
                                    class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs">
                                    ×
                                </button>
                            </div>
                        </div>
                        <!-- Input Section -->
                        <div class="flex gap-2 items-end">
                            <label
                                class="w-10 h-10 rounded-full card-bg border flex items-center justify-center cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex-shrink-0">
                                <i class="fas fa-image text-muted text-lg"></i><input
                                    type="file"
                                    accept="image/*"
                                    class="hidden"
                                    onchange="handleImageUpload(event)" />
                            </label>
                            <div class="flex-1 relative">
                                <textarea
                                    id="chat-input"
                                    rows="1"
                                    placeholder="Ketik pesan..."
                                    class="w-full px-4 py-2.5 card-bg border rounded-2xl resize-none focus:outline-none focus:ring-2 focus:ring-opacity-50 text-sm"
                                    style="max-height: 100px"
                                    onkeydown="handleKeyDown(event)"></textarea>
                            </div>
                            <button
                                onclick="sendMessage()"
                                class="w-10 h-10 primary-bg text-white rounded-full flex items-center justify-center hover:opacity-90 transition-opacity flex-shrink-0">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- SETTINGS PAGE -->

            </div>
            <!-- Mobile Bottom Navigation -->
            <nav
                id="mobile-nav-bar"
                class="mobile-nav lg:hidden fixed bottom-0 left-0 right-0 card-bg bottom-nav px-2 py-2 flex justify-around items-center z-50">
                <button
                    onclick="showPage('home')"
                    class="mobile-nav-btn flex flex-col items-center gap-1 px-4 py-2 rounded-xl transition-all"
                    data-page="home">
                    <i class="fas fa-home text-lg"></i><span class="text-xs font-medium">Home</span>
                </button>
                <button
                    onclick="showPage('chat')"
                    class="mobile-nav-btn flex flex-col items-center gap-1 px-4 py-2 rounded-xl transition-all"
                    data-page="chat">
                    <i class="fas fa-comments text-lg"></i><span class="text-xs font-medium">Chat</span>
                </button>
                <button
                    onclick="showPage('settings')"
                    class="mobile-nav-btn flex flex-col items-center gap-1 px-4 py-2 rounded-xl transition-all"
                    data-page="settings">
                    <i class="fas fa-gear text-lg"></i><span class="text-xs font-medium">Settings</span>
                </button>
            </nav>
        </main>
        </div>

        <!-- (Requirement #3) Chat History Drawer -->
        <div id="history-drawer" class="hidden fixed inset-0 z-50">
            <div
                class="absolute inset-0 bg-black/40 drawer-backdrop"
                onclick="toggleHistoryDrawer(false)"></div>
            <div
                class="absolute top-0 left-0 h-full w-[85%] max-w-sm card-bg border-r shadow-2xl drawer-panel flex flex-col">
                <div class="p-4 border-b flex items-center gap-3">
                    <button
                        type="button"
                        onclick="toggleHistoryDrawer(false)"
                        class="w-10 h-10 rounded-xl border flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <i class="fas fa-xmark"></i>
                    </button>
                    <div class="flex-1">
                        <p class="font-bold">Chat History</p>
                        <p class="text-xs text-muted">Pilih atau cari riwayat chat</p>
                    </div>
                    <button
                        type="button"
                        onclick="
              newChat();
              toggleHistoryDrawer(false);
            "
                        class="px-3 py-2 rounded-xl primary-bg text-white text-xs font-medium hover:opacity-90 transition-opacity">
                        <i class="fas fa-plus mr-1"></i> New
                    </button>
                </div>

                <div class="p-4 border-b">
                    <div class="relative">
                        <i
                            class="fas fa-search text-muted absolute left-3 top-1/2 -translate-y-1/2 text-xs"></i>
                        <input
                            id="history-search"
                            type="text"
                            placeholder="Search..."
                            class="w-full pl-8 pr-3 py-2 rounded-xl border text-sm card-bg focus:outline-none focus:ring-2 focus:ring-opacity-50"
                            oninput="renderHistoryList(this.value)" />
                    </div>
                </div>

                <div id="history-list" class="flex-1 overflow-y-auto p-2">
                    <!-- rendered by JS -->
                </div>

                <div class="p-3 border-t text-xs text-muted">
                    Tips: title otomatis berubah dari pesan pertama kamu 😉
                </div>
            </div>
        </div>

        <!-- Image Preview Modal -->
        <div
            id="image-modal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center image-modal">
            <!-- Backdrop -->
            <div
                onclick="closeImageModal()"
                class="absolute inset-0 bg-black/60 image-modal-backdrop"></div>
            <!-- Modal Content -->
            <div class="image-modal-content relative max-w-2xl w-11/12 lg:w-3/4">
                <button
                    onclick="closeImageModal()"
                    class="absolute -top-10 right-0 text-white hover:text-gray-300 transition-colors lg:-top-8">
                    <svg
                        class="w-8 h-8"
                        fill="none"
                        stroke="currentColor"
                        viewbox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <!-- Image Container -->
                <div class="bg-black rounded-lg overflow-hidden">
                    <img
                        id="modal-image"
                        src=""
                        alt="Preview"
                        class="w-full h-auto object-contain" />
                </div>
                <!-- Image Title -->
                <div class="text-white mt-4 text-center">
                    <p id="modal-title" class="text-lg font-semibold"></p>
                    <p id="modal-description" class="text-sm text-gray-300 mt-1"></p>
                </div>
                <!-- Navigation Arrows -->
                <button
                    id="prev-image-btn"
                    onclick="previousImage()"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-16 text-white hover:text-gray-300 transition-colors disabled:opacity-50">
                    <svg
                        class="w-8 h-8"
                        fill="none"
                        stroke="currentColor"
                        viewbox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    id="next-image-btn"
                    onclick="nextImage()"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-16 text-white hover:text-gray-300 transition-colors disabled:opacity-50">
                    <svg
                        class="w-8 h-8"
                        fill="none"
                        stroke="currentColor"
                        viewbox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <!-- Image Counter -->
                <div class="text-white text-center mt-4 text-sm">
                    <span id="image-counter"></span>
                </div>
            </div>
        </div>