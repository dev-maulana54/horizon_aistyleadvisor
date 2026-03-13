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
        <div id="page-home" class="page-content p-4 lg:p-8 fade-in">
            <!-- Welcome Card -->
            <div
                class="primary-bg rounded-3xl p-6 lg:p-8 text-white mb-6 relative overflow-hidden">
                <div
                    class="absolute top-5 right-5 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div
                    class="absolute bottom-5 left-5 w-24 h-24 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                <div
                    class="relative flex flex-col lg:flex-row items-center lg:items-start gap-4 lg:gap-6">
                    <img
                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&amp;h=200&amp;fit=crop&amp;crop=face"
                        alt="User Avatar"
                        class="w-20 h-20 lg:w-24 lg:h-24 rounded-2xl object-cover border-4 border-white/30 shadow-lg"
                        loading="lazy"
                        onerror="
                    this.style.background = '#9B3545';
                    this.alt = 'User Avatar';
                  " />
                    <div class="text-center lg:text-left flex-1">
                        <p class="text-sm opacity-80 mb-1" id="greeting-display">
                            👋 Apa kabar?
                        </p>
                        <h2 class="text-2xl lg:text-3xl font-bold mb-2">
                            Welcome,
                            <span id="username-display"><?= $nama_user ?? 'User' ?></span>
                        </h2>
                        <p class="opacity-90 text-sm lg:text-base">
                            Mau pergi ke mana hari ini? Mau pakai outfit apa?
                        </p>
                        <div
                            class="mt-4 flex flex-wrap gap-2 justify-center lg:justify-start">
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs">🌤️ 28°C Cerah</span>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs">📅 Rabu</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Outfit Recommendations Carousel -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold">Outfit Recommendations</h3>
                    <button
                        class="primary-text text-sm font-medium hover:underline">
                        See All →
                    </button>
                </div>
                <div class="carousel-container flex gap-4 pb-4 -mx-4 px-4">
                    <!-- Kondangan -->
                    <div class="carousel-item w-64 lg:w-72 flex-shrink-0">
                        <div
                            class="card-bg rounded-2xl overflow-hidden shadow-lg feature-card h-80">
                            <div class="h-48 relative">
                                <img
                                    src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=400&amp;h=300&amp;fit=crop"
                                    alt="Kondangan Outfit"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                    onerror="
                          this.style.background =
                            'linear-gradient(135deg, #7B1E2B, #9B3545)';
                          this.alt = 'Kondangan';
                        " />
                                <div
                                    class="absolute top-3 right-3 px-2 py-1 bg-white/90 rounded-full text-xs font-medium primary-text">
                                    Formal
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-lg mb-1">Kondangan</h4>
                                <p class="text-muted text-sm mb-3">
                                    Batik + Celana Bahan
                                </p>
                                <button
                                    onclick="showPage('chat')"
                                    class="w-full primary-bg text-white py-2 rounded-xl text-sm font-medium hover:opacity-90 transition-opacity">
                                    Get Recommendation
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Kuliah -->
                    <div class="carousel-item w-64 lg:w-72 flex-shrink-0">
                        <div
                            class="card-bg rounded-2xl overflow-hidden shadow-lg feature-card h-80">
                            <div class="h-48 relative">
                                <img
                                    src="https://images.unsplash.com/photo-1617137968427-85924c800a22?w=400&amp;h=300&amp;fit=crop"
                                    alt="Kuliah Outfit"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                    onerror="
                          this.style.background =
                            'linear-gradient(135deg, #1E3A5F, #2E5A8F)';
                          this.alt = 'Kuliah';
                        " />
                                <div
                                    class="absolute top-3 right-3 px-2 py-1 bg-white/90 rounded-full text-xs font-medium primary-text">
                                    Smart Casual
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-lg mb-1">Kuliah</h4>
                                <p class="text-muted text-sm mb-3">
                                    Kemeja + Chino Pants
                                </p>
                                <button
                                    onclick="showPage('chat')"
                                    class="w-full primary-bg text-white py-2 rounded-xl text-sm font-medium hover:opacity-90 transition-opacity">
                                    Get Recommendation
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Hangout -->
                    <div class="carousel-item w-64 lg:w-72 flex-shrink-0">
                        <div
                            class="card-bg rounded-2xl overflow-hidden shadow-lg feature-card h-80">
                            <div class="h-48 relative">
                                <img
                                    src="https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?w=400&amp;h=300&amp;fit=crop"
                                    alt="Hangout Outfit"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                    onerror="
                          this.style.background =
                            'linear-gradient(135deg, #2D5A27, #4A8B3B)';
                          this.alt = 'Hangout';
                        " />
                                <div
                                    class="absolute top-3 right-3 px-2 py-1 bg-white/90 rounded-full text-xs font-medium primary-text">
                                    Casual
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-lg mb-1">Hangout</h4>
                                <p class="text-muted text-sm mb-3">T-Shirt + Jeans</p>
                                <button
                                    onclick="showPage('chat')"
                                    class="w-full primary-bg text-white py-2 rounded-xl text-sm font-medium hover:opacity-90 transition-opacity">
                                    Get Recommendation
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Interview -->
                    <div class="carousel-item w-64 lg:w-72 flex-shrink-0">
                        <div
                            class="card-bg rounded-2xl overflow-hidden shadow-lg feature-card h-80">
                            <div class="h-48 relative">
                                <img
                                    src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=400&amp;h=300&amp;fit=crop"
                                    alt="Interview Outfit"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                    onerror="
                          this.style.background =
                            'linear-gradient(135deg, #1A1A2E, #2D2D4A)';
                          this.alt = 'Interview';
                        " />
                                <div
                                    class="absolute top-3 right-3 px-2 py-1 bg-white/90 rounded-full text-xs font-medium primary-text">
                                    Professional
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-lg mb-1">Interview</h4>
                                <p class="text-muted text-sm mb-3">
                                    Blazer + Dress Pants
                                </p>
                                <button
                                    onclick="showPage('chat')"
                                    class="w-full primary-bg text-white py-2 rounded-xl text-sm font-medium hover:opacity-90 transition-opacity">
                                    Get Recommendation
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- AI Features Grid -->
            <div class="mb-24 lg:mb-8">
                <h3 class="text-xl font-bold mb-4">AI Features</h3>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
                    <!-- (Requirement #1) Removed: Color Palette Matcher, Body Type & Fit, Outfit Rating, Favorites & History -->
                    <!-- Added: AI Virtual Try On -->
                    <div
                        class="card-bg rounded-2xl p-4 shadow-md feature-card cursor-pointer"
                        onclick="openFeature('tryon')">
                        <div
                            class="w-12 h-12 primary-bg rounded-xl flex items-center justify-center mb-3">
                            <i
                                class="fas fa-wand-magic-sparkles text-white text-lg"></i>
                        </div>
                        <h4 class="font-semibold text-sm mb-1">AI Virtual Try On</h4>
                        <p class="text-muted text-xs">Coba outfit secara virtual</p>
                    </div>

                    <div
                        class="card-bg rounded-2xl p-4 shadow-md feature-card cursor-pointer"
                        onclick="openFeature('mixmatch')">
                        <div
                            class="w-12 h-12 primary-bg rounded-xl flex items-center justify-center mb-3">
                            <i class="fas fa-shirt text-white text-lg"></i>
                        </div>
                        <h4 class="font-semibold text-sm mb-1">
                            Mix &amp; Match Generator
                        </h4>
                        <p class="text-muted text-xs">Create outfit combos</p>
                    </div>

                    <div
                        class="card-bg rounded-2xl p-4 shadow-md feature-card cursor-pointer"
                        onclick="openFeature('minmax')">
                        <div
                            class="w-12 h-12 primary-bg rounded-xl flex items-center justify-center mb-3">
                            <i class="fas fa-suitcase text-white text-lg"></i>
                        </div>
                        <h4 class="font-semibold text-sm mb-1">Min-Max Wardrobe</h4>
                        <p class="text-muted text-xs">Optimize your closet</p>
                    </div>

                    <div
                        class="card-bg rounded-2xl p-4 shadow-md feature-card cursor-pointer"
                        onclick="openFeature('dresscode')">
                        <div
                            class="w-12 h-12 primary-bg rounded-xl flex items-center justify-center mb-3">
                            <i class="fas fa-calendar-check text-white text-lg"></i>
                        </div>
                        <h4 class="font-semibold text-sm mb-1">Dress Code Helper</h4>
                        <p class="text-muted text-xs">Match any occasion</p>
                    </div>

                    <div
                        class="card-bg rounded-2xl p-4 shadow-md feature-card cursor-pointer"
                        onclick="openFeature('weather')">
                        <div
                            class="w-12 h-12 primary-bg rounded-xl flex items-center justify-center mb-3">
                            <i class="fas fa-cloud-sun text-white text-lg"></i>
                        </div>
                        <h4 class="font-semibold text-sm mb-1">Weather Suggestion</h4>
                        <p class="text-muted text-xs">Dress for the weather</p>
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