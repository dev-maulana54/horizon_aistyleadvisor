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
            <span class="font-bold text-lg">FitMatch AI</span>
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
        <div id="page-tryon" class="page-content p-4 lg:p-8 fade-in">
            <div class="card-bg rounded-2xl p-4 lg:p-6 shadow-md mb-4 flex items-center justify-between sticky top-0 z-10 border">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="showPage('home')" class="w-10 h-10 rounded-xl border flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" fdprocessedid="w58d4j">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div>
                        <h2 class="text-lg lg:text-2xl font-bold">
                            AI Virtual Try On
                        </h2>
                        <p class="text-muted text-xs lg:text-sm">
                            Upload foto kamu + outfit, lalu generate
                        </p>
                    </div>
                </div>

            </div>

            <div class="grid lg:grid-cols-3 gap-4">
                <div class="lg:col-span-2 card-bg rounded-2xl p-5 shadow-md">
                    <h3 class="font-semibold mb-1">1) Upload Images</h3>
                    <p class="text-muted text-sm mb-4">
                        Hasil preview &amp; result selalu
                        <b>fit (tidak terpotong)</b>.
                    </p>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="border rounded-2xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-semibold text-sm">Foto Kamu</p>
                                <span class="text-xs text-muted" id="tryon-person-status">Belum ada</span>
                            </div>
                            <label class="block cursor-pointer">
                                <input id="tryon-person-input" type="file" accept="image/*" class="hidden" onchange="handleTryOnUpload(event, 'person')">
                                <div class="border-2 border-dashed rounded-2xl p-4 text-center hover:border-current transition-colors">
                                    <i class="fas fa-user text-2xl text-muted mb-2"></i>
                                    <p class="text-sm font-medium">Klik untuk upload</p>
                                    <p class="text-xs text-muted">PNG/JPG</p>
                                </div>
                            </label>
                            <div class="mt-3 rounded-2xl overflow-hidden bg-black/5 border aspect-[3/4] flex items-center justify-center">
                                <img id="tryon-person-preview" src="" alt="Person Preview" class="w-full h-full object-contain hidden">
                                <div id="tryon-person-placeholder" class="text-xs text-muted p-3 text-center">
                                    Preview akan muncul di sini
                                </div>
                            </div>
                        </div>

                        <div class="border rounded-2xl p-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="font-semibold text-sm">Foto Outfit</p>
                                <span class="text-xs text-muted" id="tryon-outfit-status">Belum ada</span>
                            </div>
                            <label class="block cursor-pointer">
                                <input id="tryon-outfit-input" type="file" accept="image/*" class="hidden" onchange="handleTryOnUpload(event, 'outfit')">
                                <div class="border-2 border-dashed rounded-2xl p-4 text-center hover:border-current transition-colors">
                                    <i class="fas fa-shirt text-2xl text-muted mb-2"></i>
                                    <p class="text-sm font-medium">Klik untuk upload</p>
                                    <p class="text-xs text-muted">
                                        Atau pilih dari Wardrobe
                                    </p>
                                </div>
                            </label>

                            <div class="mt-3">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-folder-open text-muted"></i>
                                    <p class="text-xs text-muted">
                                        Pilih dari Wardrobe (opsional)
                                    </p>
                                </div>
                                <select id="tryon-wardrobe-select" class="mt-2 w-full px-3 py-2 card-bg border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-opacity-50" onchange="selectOutfitFromWardrobe()" fdprocessedid="5keksf">
                                    <option value="">-- Pilih item wardrobe --</option>
                                </select>
                            </div>

                            <div class="mt-3 rounded-2xl overflow-hidden bg-black/5 border aspect-[3/4] flex items-center justify-center">
                                <img id="tryon-outfit-preview" src="" alt="Outfit Preview" class="w-full h-full object-contain hidden">
                                <div id="tryon-outfit-placeholder" class="text-xs text-muted p-3 text-center">
                                    Preview akan muncul di sini
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-col sm:flex-row gap-3">
                        <button type="button" onclick="generateTryOn()" class="flex-1 primary-bg text-white py-3 rounded-2xl font-medium hover:opacity-90 transition-opacity" fdprocessedid="32p6o">
                            <i class="fas fa-wand-magic-sparkles mr-2"></i> Generate Try
                            On
                        </button>
                        <button type="button" onclick="resetTryOn()" class="flex-1 border py-3 rounded-2xl font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" fdprocessedid="xrdna8">
                            Reset
                        </button>
                    </div>

                    <div class="mt-6">
                        <h3 class="font-semibold mb-2">2) Result</h3>
                        <div class="rounded-2xl overflow-hidden border bg-black/5 aspect-[3/4] flex items-center justify-center">
                            <img id="tryon-result" src="" alt="Try On Result" class="w-full h-full object-contain hidden">
                            <div id="tryon-result-placeholder" class="text-sm text-muted p-5 text-center">
                                Hasil try on akan muncul di sini setelah kamu klik
                                <b>Generate Try On</b>.
                            </div>
                        </div>
                        <p class="text-xs text-muted mt-2">
                            *Demo compositing (tanpa backend). Untuk hasil AI asli,
                            sambungkan model try-on kamu.
                        </p>
                    </div>
                </div>

                <div class="card-bg rounded-2xl p-5 shadow-md">
                    <h3 class="font-semibold mb-3">Tips Biar Hasil Bagus</h3>
                    <ul class="text-sm text-muted space-y-2 list-disc pl-5">
                        <li>Pakai foto full-body, background polos.</li>
                        <li>Outfit sebaiknya foto lurus (front view).</li>
                        <li>Kalau outfit dari wardrobe, pastikan gambarnya jelas.</li>
                        <li>Result selalu <b>fit</b> (tidak terpotong).</li>
                    </ul>
                    <div class="mt-4 border-t pt-4">
                        <button type="button" onclick="showToast('✅ Siap! Upload dulu 2 gambar ya.')" class="w-full border rounded-2xl py-3 font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors" fdprocessedid="mc4ntf">
                            Quick Guide
                        </button>
                    </div>
                </div>
            </div>
        </div>


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