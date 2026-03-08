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

        <!-- AI VIRTUAL TRY ON PAGE (Requirement #1) -->


        <!-- CHAT PAGE -->


        <!-- SETTINGS PAGE -->
        <div
            id="page-settings"
            class="page-content pb-20 lg:pb-0 fade-in">
            <div class="p-4 lg:p-8">
                <h2 class="text-2xl font-bold mb-6">Settings</h2>
                <!-- Settings Tabs -->
                <div class="flex gap-2 mb-6 border-b overflow-x-auto">
                    <button
                        onclick="switchSettingsTab('profile')"
                        class="settings-tab-btn pb-3 px-4 font-medium border-b-2 border-current transition-colors whitespace-nowrap primary-text"
                        data-tab="profile">
                        👤 Profile
                    </button>
                    <button
                        onclick="switchSettingsTab('personal')"
                        class="settings-tab-btn pb-3 px-4 font-medium border-b-2 border-transparent hover:border-gray-300 transition-colors whitespace-nowrap"
                        data-tab="personal">
                        🎨 Personal Data
                    </button>
                    <button
                        onclick="switchSettingsTab('wardrobe')"
                        class="settings-tab-btn pb-3 px-4 font-medium border-b-2 border-transparent hover:border-gray-300 transition-colors whitespace-nowrap"
                        data-tab="wardrobe">
                        👗 My Wardrobe
                    </button>
                </div>
                <!-- Profile Tab -->
                <div id="profile-tab" class="settings-tab">
                    <!-- Profile Card -->
                    <div class="card-bg rounded-2xl p-6 shadow-md mb-6">
                        <div class="flex items-center gap-4">
                            <img
                                src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&amp;h=100&amp;fit=crop&amp;crop=face"
                                alt="Profile"
                                class="w-16 h-16 rounded-2xl object-cover"
                                loading="lazy"
                                onerror="
                        this.style.background = '#9B3545';
                        this.alt = 'User';
                      " />
                            <div class="flex-1">
                                <h3 class="font-bold text-lg" id="settings-username">
                                    Maulana Saepul Akbar
                                </h3>
                                <p class="text-muted text-sm">Premium Member</p>
                                <div class="mt-2 flex gap-2">
                                    <span
                                        class="px-2 py-0.5 primary-bg text-white rounded-full text-xs">Style Enthusiast</span>
                                </div>
                            </div>
                            <button
                                class="w-10 h-10 rounded-full border flex items-center justify-center hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                        </div>
                        <div class="card-bg rounded-2xl p-6 shadow-md mb-6">
                            <h4 class="font-semibold mb-4 flex items-center gap-2">
                                <svg
                                    class="w-5 h-5 primary-text"
                                    fill="none"
                                    stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                                </svg>
                                Appearance
                            </h4>
                            <!-- Dark Mode Toggle -->
                            <div
                                class="flex items-center justify-between py-3 border-b">
                                <div>
                                    <p class="font-medium">Dark Mode</p>
                                    <p class="text-muted text-sm">Switch to dark theme</p>
                                </div>
                                <label
                                    class="relative inline-flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        id="dark-mode-toggle"
                                        class="sr-only peer"
                                        onchange="toggleDarkMode()" />
                                    <div
                                        class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                </label>
                            </div>
                            <!-- Theme Selection -->
                            <div class="py-3">
                                <p class="font-medium mb-3">Theme Color</p>
                                <div class="flex gap-3">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="radio"
                                            name="theme"
                                            value="maroon"
                                            checked
                                            onchange="changeTheme('maroon')"
                                            class="w-4 h-4 accent-current" />
                                        <span
                                            class="w-6 h-6 rounded-full bg-[#7B1E2B] border-2 border-white shadow"></span>
                                        <span class="text-sm">Maroon</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input
                                            type="radio"
                                            name="theme"
                                            value="navy"
                                            onchange="changeTheme('navy')"
                                            class="w-4 h-4 accent-current" />
                                        <span
                                            class="w-6 h-6 rounded-full bg-[#1E3A5F] border-2 border-white shadow"></span>
                                        <span class="text-sm">Navy Blue</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Notifications Section -->
                        <div class="card-bg rounded-2xl p-6 shadow-md mb-6">
                            <h4 class="font-semibold mb-4 flex items-center gap-2">
                                <svg
                                    class="w-5 h-5 primary-text"
                                    fill="none"
                                    stroke="currentColor"
                                    viewbox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                Notifications
                            </h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between py-2">
                                    <div>
                                        <p class="font-medium text-sm">Push Notifications</p>
                                        <p class="text-muted text-xs">
                                            Receive style tips &amp; updates
                                        </p>
                                    </div>
                                    <label
                                        class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer" />
                                        <div
                                            class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between py-2">
                                    <div>
                                        <p class="font-medium text-sm">Daily Style Tips</p>
                                        <p class="text-muted text-xs">
                                            Get daily outfit inspiration
                                        </p>
                                    </div>
                                    <label
                                        class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" checked class="sr-only peer" />
                                        <div
                                            class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Reset Button -->
                        <button
                            onclick="resetPreferences()"
                            class="w-full card-bg border-2 border-red-500 text-red-500 rounded-2xl py-3 font-medium hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors mb-24 lg:mb-8">
                            Reset All Preferences
                        </button>
                    </div>
                </div>
            </div>
            <!-- Personal Data Tab -->
            <div id="personal-tab" class="settings-tab hidden p-4 lg:p-8">
                <div class="card-bg rounded-2xl p-6 shadow-md mb-6">
                    <h4 class="font-semibold mb-4">👤 Body Shape</h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group body-shape-option">
                            <input
                                type="radio"
                                name="body_shape"
                                value="hourglass"
                                class="hidden peer"
                                onchange="updateBodyShapeUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-pink-500 group-hover:border-gray-300 transition-all">
                                <img
                                    src="assets/img/hourglass.png"
                                    alt="Hourglass body shape"
                                    class="w-full h-full object-contain transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-pink-500/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-pink-500 peer-checked:bg-pink-500 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Hourglass</span>
                        </label>
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group body-shape-option">
                            <input
                                type="radio"
                                name="body_shape"
                                value="pear"
                                class="hidden peer"
                                onchange="updateBodyShapeUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-purple-500 group-hover:border-gray-300 transition-all">
                                <img
                                    src="assets/img/pear.png"
                                    alt="Pear body shape"
                                    class="w-full h-full object-contain transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-purple-500/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-purple-500 peer-checked:bg-purple-500 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Pear</span>
                        </label>
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group body-shape-option">
                            <input
                                type="radio"
                                name="body_shape"
                                value="apple"
                                class="hidden peer"
                                onchange="updateBodyShapeUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-orange-500 group-hover:border-gray-300 transition-all">
                                <img
                                    src="assets/img/apple.png"
                                    alt="Apple body shape"
                                    class="w-full h-full object-contain transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-orange-500/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-orange-500 peer-checked:bg-orange-500 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Apple</span>
                        </label>
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group body-shape-option">
                            <input
                                type="radio"
                                name="body_shape"
                                value="rectangle"
                                class="hidden peer"
                                onchange="updateBodyShapeUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-blue-500 group-hover:border-gray-300 transition-all">
                                <img
                                    src="assets/img/rectangle.png"
                                    alt="Rectangle body shape"
                                    class="w-full h-full object-contain transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-blue-500/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-blue-500 peer-checked:bg-blue-500 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Rectangle</span>
                        </label>
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group body-shape-option">
                            <input
                                type="radio"
                                name="body_shape"
                                value="inverted-triangle"
                                class="hidden peer"
                                onchange="updateBodyShapeUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-green-500 group-hover:border-gray-300 transition-all">
                                <img
                                    src="assets/img/inverted.png"
                                    alt="Inverted Triangle body shape"
                                    class="w-full h-full object-contain transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-green-500/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-green-500 peer-checked:bg-green-500 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Inverted</span>
                        </label>
                    </div>
                </div>
                <div class="card-bg rounded-2xl p-6 shadow-md mb-6">
                    <h4 class="font-semibold mb-4">
                        🎨 Style Preferences (Select multiple)
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group style-pref-option">
                            <input
                                type="checkbox"
                                name="style_pref"
                                value="minimalist"
                                class="hidden peer"
                                onchange="updateStylePrefUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-gray-600 group-hover:border-gray-300 transition-all">
                                <img
                                    src="https://images.unsplash.com/photo-1490578174853-bc519f5ee775?w=300&amp;h=300&amp;fit=crop"
                                    alt="Minimalist style"
                                    class="w-full h-full object-cover transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-gray-500/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-gray-600 peer-checked:bg-gray-600 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Minimalist</span>
                        </label>
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group style-pref-option">
                            <input
                                type="checkbox"
                                name="style_pref"
                                value="bohemian"
                                class="hidden peer"
                                onchange="updateStylePrefUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-amber-600 group-hover:border-gray-300 transition-all">
                                <img
                                    src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=300&amp;h=300&amp;fit=crop"
                                    alt="Bohemian style"
                                    class="w-full h-full object-cover transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-amber-600/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-amber-600 peer-checked:bg-amber-600 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Bohemian</span>
                        </label>
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group style-pref-option">
                            <input
                                type="checkbox"
                                name="style_pref"
                                value="classic"
                                class="hidden peer"
                                onchange="updateStylePrefUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-blue-700 group-hover:border-gray-300 transition-all">
                                <img
                                    src="https://images.unsplash.com/photo-1591195853828-11db59a44f6b?w=300&amp;h=300&amp;fit=crop"
                                    alt="Classic style"
                                    class="w-full h-full object-cover transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-blue-700/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-blue-700 peer-checked:bg-blue-700 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Classic</span>
                        </label>
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group style-pref-option">
                            <input
                                type="checkbox"
                                name="style_pref"
                                value="trendy"
                                class="hidden peer"
                                onchange="updateStylePrefUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-pink-600 group-hover:border-gray-300 transition-all">
                                <img
                                    src="https://images.unsplash.com/photo-1559631065-cd4628902d4a?w=300&amp;h=300&amp;fit=crop"
                                    alt="Trendy style"
                                    class="w-full h-full object-cover transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-pink-600/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-pink-600 peer-checked:bg-pink-600 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Trendy</span>
                        </label>
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group style-pref-option">
                            <input
                                type="checkbox"
                                name="style_pref"
                                value="sporty"
                                class="hidden peer"
                                onchange="updateStylePrefUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-green-600 group-hover:border-gray-300 transition-all">
                                <img
                                    src="https://images.unsplash.com/photo-1622268537308-d3b1b0e17f7d?w=300&amp;h=300&amp;fit=crop"
                                    alt="Sporty style"
                                    class="w-full h-full object-cover transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-green-600/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-green-600 peer-checked:bg-green-600 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Sporty</span>
                        </label>
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group style-pref-option">
                            <input
                                type="checkbox"
                                name="style_pref"
                                value="edgy"
                                class="hidden peer"
                                onchange="updateStylePrefUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-gray-900 group-hover:border-gray-300 transition-all">
                                <img
                                    src="https://images.unsplash.com/photo-1611003228941-98852ba62227?w=300&amp;h=300&amp;fit=crop"
                                    alt="Edgy style"
                                    class="w-full h-full object-cover transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-gray-900/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-gray-900 peer-checked:bg-gray-900 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Edgy</span>
                        </label>
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group style-pref-option">
                            <input
                                type="checkbox"
                                name="style_pref"
                                value="romantic"
                                class="hidden peer"
                                onchange="updateStylePrefUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-rose-600 group-hover:border-gray-300 transition-all">
                                <img
                                    src="https://images.unsplash.com/photo-1595777712802-303e5edd5e06?w=300&amp;h=300&amp;fit=crop"
                                    alt="Romantic style"
                                    class="w-full h-full object-cover transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-rose-600/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-rose-600 peer-checked:bg-rose-600 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Romantic</span>
                        </label>
                        <label
                            class="flex flex-col items-center gap-3 cursor-pointer group style-pref-option">
                            <input
                                type="checkbox"
                                name="style_pref"
                                value="casual"
                                class="hidden peer"
                                onchange="updateStylePrefUI()" />
                            <div
                                class="relative w-full aspect-square rounded-lg overflow-hidden border-3 border-transparent peer-checked:border-cyan-600 group-hover:border-gray-300 transition-all">
                                <img
                                    src="https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?w=300&amp;h=300&amp;fit=crop"
                                    alt="Casual style"
                                    class="w-full h-full object-cover transition-all"
                                    loading="lazy" />
                                <div
                                    class="absolute inset-0 bg-black/0 peer-checked:bg-cyan-600/20 transition-colors"></div>
                            </div>
                            <div
                                class="w-5 h-5 rounded-full border-2 border-gray-400 peer-checked:border-cyan-600 peer-checked:bg-cyan-600 transition-all"></div>
                            <span class="text-sm font-semibold text-center">Casual</span>
                        </label>
                    </div>
                </div>
                <div class="card-bg rounded-2xl p-6 shadow-md mb-6">
                    <h4 class="font-semibold mb-4">
                        💚 Favorite Colors (Select multiple)
                    </h4>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="fav_color"
                                value="red"
                                class="w-4 h-4 accent-current" />
                            <span
                                class="inline-block w-4 h-4 rounded-full bg-red-500 border-2 border-gray-300"></span>
                            <span class="text-sm">Red</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="fav_color"
                                value="blue"
                                class="w-4 h-4 accent-current" />
                            <span
                                class="inline-block w-4 h-4 rounded-full bg-blue-500 border-2 border-gray-300"></span>
                            <span class="text-sm">Blue</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="fav_color"
                                value="green"
                                class="w-4 h-4 accent-current" />
                            <span
                                class="inline-block w-4 h-4 rounded-full bg-green-500 border-2 border-gray-300"></span>
                            <span class="text-sm">Green</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="fav_color"
                                value="purple"
                                class="w-4 h-4 accent-current" />
                            <span
                                class="inline-block w-4 h-4 rounded-full bg-purple-500 border-2 border-gray-300"></span>
                            <span class="text-sm">Purple</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="fav_color"
                                value="black"
                                class="w-4 h-4 accent-current" />
                            <span
                                class="inline-block w-4 h-4 rounded-full bg-black border-2 border-gray-300"></span>
                            <span class="text-sm">Black</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                name="fav_color"
                                value="white"
                                class="w-4 h-4 accent-current" />
                            <span
                                class="inline-block w-4 h-4 rounded-full bg-white border-2 border-gray-300"></span>
                            <span class="text-sm">White</span>
                        </label>
                    </div>
                </div>
                <button
                    onclick="updatePersonalData()"
                    class="w-full primary-bg text-white rounded-2xl py-3 font-medium hover:opacity-90 transition-opacity mb-24 lg:mb-8">
                    Save Personal Data
                </button>
            </div>
            <!-- Wardrobe Tab -->
            <div id="wardrobe-tab" class="settings-tab hidden p-4 lg:p-8">
                <div class="card-bg rounded-2xl p-6 shadow-md mb-6">
                    <h4 class="font-semibold mb-4 flex items-center gap-2">
                        <i class="fas fa-folder-open primary-text"></i>
                        <span id="category-name">Shirts</span>
                    </h4>
                    <!-- Category Buttons -->
                    <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
                        <button
                            onclick="switchWardrobeCategory('shirts')"
                            class="wardrobe-category-btn whitespace-nowrap px-4 py-2 rounded-xl border-2 font-medium transition-all primary-bg text-white border-current"
                            data-category="shirts">
                            👕 Shirts
                        </button>
                        <button
                            onclick="switchWardrobeCategory('pants')"
                            class="wardrobe-category-btn whitespace-nowrap px-4 py-2 rounded-xl border-2 font-medium transition-all border-gray-200"
                            data-category="pants">
                            👖 Pants
                        </button>
                        <button
                            onclick="switchWardrobeCategory('shoes')"
                            class="wardrobe-category-btn whitespace-nowrap px-4 py-2 rounded-xl border-2 font-medium transition-all border-gray-200"
                            data-category="shoes">
                            👟 Shoes
                        </button>
                        <button
                            onclick="switchWardrobeCategory('accessories')"
                            class="wardrobe-category-btn whitespace-nowrap px-4 py-2 rounded-xl border-2 font-medium transition-all border-gray-200"
                            data-category="accessories">
                            💍 Accessories
                        </button>
                    </div>
                    <!-- Add Item Section -->
                    <div class="add-item-section rounded-2xl p-4 mb-6">
                        <h5 class="font-semibold text-sm mb-3">Add New Item</h5>
                        <div class="space-y-3">
                            <input
                                type="text"
                                id="item-name"
                                placeholder="Item Name (e.g., Blue Polo Shirt)"
                                class="w-full px-3 py-2 card-bg border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-opacity-50" />
                            <textarea
                                id="item-desc"
                                placeholder="Description (optional)"
                                rows="2"
                                class="w-full px-3 py-2 card-bg border rounded-lg text-sm resize-none focus:outline-none focus:ring-2 focus:ring-opacity-50"></textarea>
                            <label class="block">
                                <input
                                    type="file"
                                    id="wardrobe-upload"
                                    accept="image/*"
                                    multiple
                                    onchange="handleWardrobeImageUpload(event)"
                                    class="hidden" />
                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center cursor-pointer hover:border-current transition-colors">
                                    <i
                                        class="fas fa-cloud-upload-alt text-2xl text-muted mb-2"></i>
                                    <p class="text-sm font-medium">Upload Images (Max 5)</p>
                                    <p class="text-xs text-muted">
                                        Click to select or drag images
                                    </p>
                                </div>
                            </label>
                            <div
                                id="wardrobe-preview"
                                class="hidden p-3 card-bg border rounded-lg flex flex-wrap gap-2 items-start"></div>
                            <button
                                onclick="addWardrobeItem()"
                                class="w-full primary-bg text-white rounded-lg py-2 font-medium hover:opacity-90 transition-opacity text-sm">
                                Add Item
                            </button>
                        </div>
                    </div>
                    <!-- Items Display -->
                    <div id="empty-wardrobe" class="text-center py-8">
                        <i
                            class="fas fa-inbox text-4xl text-muted opacity-30 mb-2"></i>
                        <p class="text-muted text-sm">
                            No items yet. Add your first
                            <span id="category-empty" class="lowercase">shirt</span>!
                        </p>
                    </div>
                    <div
                        id="wardrobe-items"
                        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 w-full"></div>
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


</body>

</html>